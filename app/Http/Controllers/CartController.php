<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    private function getCartTotals(array $cart): array
    {
        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $tax = round($subtotal * 0.05, 2);
        $shipping = 0;
        $discount = 0;
        $total = round($subtotal - $discount + $tax + $shipping, 2);

        return compact('subtotal', 'tax', 'shipping', 'discount', 'total');
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        $saved = session()->get('saved', []);
        return view('pages.cart', compact('cart', 'saved'));
    }
    
    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        $attributes = [
            'size'     => $request->input('size', ''),
            'color'    => $request->input('color', ''),
            'material' => $request->input('material', ''),
        ];

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
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
        return redirect()->route('cart')->with('success', 'Product added to cart!');
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
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
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

        DB::transaction(function () use ($cart, $user, $validated, $totals) {
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
            }
        });

        session()->forget('cart');

        return redirect()->route('user.orders')->with('success', 'Order placed successfully.');
    }
}
