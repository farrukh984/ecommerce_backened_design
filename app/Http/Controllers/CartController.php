<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\Admin\NewOrderAlert;

class CartController extends Controller
{
    private function getCartTotals(array $cart): array
    {
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $tax = round($subtotal * 0.05, 2);
        $shipping = 0;
        
        // Dynamic Discount from Session
        $couponDiscount = session()->get('coupon_discount', 0);
        $discount = $couponDiscount > 0 ? $couponDiscount : 0;
        
        $total = round($subtotal - $discount + $tax + $shipping, 2);

        return compact('subtotal', 'tax', 'shipping', 'discount', 'total');
    }

    public function applyCoupon(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->back()->with('error', 'Please login to apply coupon.');
        }

        $coupon = $request->input('coupon');
        $user = auth()->user();

        if ($coupon === $user->unique_coupon_code) {
            if ($user->is_coupon_eligible) {
                // Apply 10% discount on subtotal
                $cart = session()->get('cart', []);
                $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
                $discount = round($subtotal * 0.10, 2);
                
                session()->put('coupon_discount', $discount);
                session()->put('applied_coupon', $coupon);
                
                return redirect()->back()->with('success', 'Coupon applied successfully! You got 10% off.');
            } else {
                return redirect()->back()->with('error', 'This coupon is not yet active for your account.');
            }
        }

        return redirect()->back()->with('error', 'Invalid coupon code or does not belong to you.');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $saved = session()->get('saved', []);
        $totals = $this->getCartTotals($cart);
        return view('pages.cart', compact('cart', 'saved', 'totals'));
    }
    
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->stock_quantity <= 0) {
            return redirect()->back()->with('error', 'Sorry, this product is currently sold out.');
        }

        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        $attributes = [
            'size'     => $request->input('size', ''),
            'color'    => $request->input('color', ''),
            'material' => $request->input('material', ''),
        ];

        if (isset($cart[$id])) {
            $newQty = $cart[$id]['quantity'] + $quantity;
            if ($newQty > $product->stock_quantity) {
                $cart[$id]['quantity'] = $product->stock_quantity;
                session()->put('cart', $cart);
                return redirect()->route('cart')->with('warning', 'Only ' . $product->stock_quantity . ' items available. Quantity adjusted.');
            }
            $cart[$id]['quantity'] = $newQty;
        } else {
            if ($quantity > $product->stock_quantity) {
                $quantity = $product->stock_quantity;
                $warning = 'Only ' . $product->stock_quantity . ' items available. Quantity adjusted.';
            }
            $cart[$id] = [
                "name"       => $product->name,
                "quantity"   => $quantity,
                "price"      => $product->price,
                "image"      => $product->image,
                "id"         => $product->id,
                "attributes" => $attributes,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart')->with('success', $warning ?? 'Product added to cart!');
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        } else {
            // Remove all items
            session()->forget('cart');
            session()->flash('success', 'Cart cleared successfully');
        }
        return redirect()->back();
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $product = Product::find($request->id);
            if (!$product) return redirect()->back();

            $cart = session()->get('cart');
            $requestedQty = (int) $request->quantity;

            if ($requestedQty > $product->stock_quantity) {
                $cart[$request->id]["quantity"] = $product->stock_quantity;
                session()->put('cart', $cart);
                return redirect()->back()->with('warning', 'Only ' . $product->stock_quantity . ' items available.');
            }

            $cart[$request->id]["quantity"] = $requestedQty;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
        return redirect()->back();
    }

    public function saveForLater($id)
    {
        $cart = session()->get('cart', []);
        $saved = session()->get('saved', []);

        if (isset($cart[$id])) {
            $saved[$id] = $cart[$id];
            unset($cart[$id]);
            session()->put('cart', $cart);
            session()->put('saved', $saved);
            return redirect()->back()->with('success', 'Product saved for later!');
        }
        return redirect()->back();
    }

    public function moveToCart($id)
    {
        $cart = session()->get('cart', []);
        $saved = session()->get('saved', []);

        if (isset($saved[$id])) {
            $cart[$id] = $saved[$id];
            unset($saved[$id]);
            session()->put('cart', $cart);
            session()->put('saved', $saved);
            return redirect()->back()->with('success', 'Product moved to cart!');
        }
        return redirect()->back();
    }

    public function removeSaved($id)
    {
        $saved = session()->get('saved', []);
        if (isset($saved[$id])) {
            unset($saved[$id]);
            session()->put('saved', $saved);
        }
        return redirect()->back()->with('success', 'Removed from saved items');
    }

    public function showCheckout()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty');
        }

        $totals = $this->getCartTotals($cart);

        return view('pages.checkout', compact('cart', 'totals'));
    }

    public function placeOrder(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'phone' => 'required|string|max:40',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:120',
            'state' => 'nullable|string|max:120',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = auth()->user();
        $totals = $this->getCartTotals($cart);

        $order = null;
        DB::transaction(function () use ($cart, $user, $validated, $totals, &$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'total_amount' => $totals['total'],
                'discount_amount' => $totals['discount'],
                'shipping_amount' => $totals['shipping'],
                'tax_amount' => $totals['tax'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'] ?? null,
                'zip_code' => $validated['zip_code'],
                'country' => $validated['country'],
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'attributes' => $item['attributes'] ?? null,
                ]);

                // Update Product Stock
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock_quantity', $item['quantity']);
                    $product->increment('sold_count', $item['quantity']);
                    
                    // Update in_stock status if quantity is 0 or less
                    if ($product->stock_quantity <= 0) {
                        $product->update(['in_stock' => false, 'stock_quantity' => 0]);
                    }
                }
            }
        });

        // Notify Admin of New Order
        $admin = User::where('role', 'admin')->first();
        if ($admin && $order) {
            try {
                $order->load('items.product');
                Mail::to($admin->email)->send(new NewOrderAlert($order));
            } catch (\Exception $e) { \Log::error("Admin Order Mail Error: " . $e->getMessage()); }
        }

        session()->forget('cart');

        // If AJAX request, return JSON for SweetAlert
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your order has been placed successfully!',
                'redirect' => route('user.orders'),
            ]);
        }

        return redirect()->route('user.orders')->with('success', 'Order placed successfully.');
    }
}
