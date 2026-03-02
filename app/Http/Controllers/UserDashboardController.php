<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Order;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $recentOrders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        $totalOrders = $user->orders()->count();
        $wishlistCount = $user->wishlistItems()->count();
        $totalSpent = (float) $user->orders()
            ->where('status', '!=', 'canceled')
            ->sum('total_amount');

        $activeOrders = $user->orders()
            ->whereIn('status', ['pending', 'processing', 'shipped'])
            ->count();

        $stats = [
            'total_orders' => $totalOrders,
            'wishlist_items' => $wishlistCount,
            'loyalty_points' => (int) floor($totalSpent),
            'active_orders' => $activeOrders,
            'rank' => $user->rank,
            'coupon_eligible' => $user->is_coupon_eligible,
            'coupon_code' => $user->unique_coupon_code,
        ];

        return view('user.dashboard', compact('recentOrders', 'stats'));
    }

    public function orders()
    {
        $user = Auth::user();
        
        // Mark all as viewed by user
        $user->orders()->where('is_viewed_by_user', false)->update(['is_viewed_by_user' => true]);

        $orders = $user->orders()
            ->with('items.product')
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('user.orders', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id != Auth::id()) {
            return redirect()->route('user.orders')->with('error', 'Unauthorized: This order does not belong to you.');
        }

        $order->load('items.product');

        return view('user.order-detail', compact('order'));
    }

    public function deleteOrder(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->user_id != Auth::id()) {
            return back()->with('error', 'Unauthorized: This order does not belong to you.');
        }

        // Only allow deletion of pending or cancelled orders
        if (!in_array(strtolower($order->status), ['pending', 'cancelled', 'canceled'])) {
            return back()->with('error', 'Only pending or cancelled orders can be deleted.');
        }

        // If pending, restore stock
        if ($order->status === 'pending') {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                    $item->product->decrement('sold_count', $item->quantity);
                    if ($item->product->stock_quantity > 0) {
                        $item->product->update(['in_stock' => true]);
                    }
                }
            }
        }

        $order->items()->delete();
        $order->delete();

        return redirect()->route('user.orders')->with('success', 'Order deleted successfully.');
    }

    public function wishlist()
    {
        $wishlistItems = Auth::user()
            ->wishlistItems()
            ->with('product')
            ->latest()
            ->paginate(12);

        return view('user.wishlist', compact('wishlistItems'));
    }

    public function profile()
    {
        return view('user.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }

            $user->password = Hash::make($validated['new_password']);
        }

        if ($request->hasFile('profile_image')) {
            $user->profile_image = Cloudinary::upload($request->file('profile_image')->getRealPath(), ['folder' => 'profile_images'])->getSecurePath();
        }

        if ($request->hasFile('cover_image')) {
            $user->cover_image = Cloudinary::upload($request->file('cover_image')->getRealPath(), ['folder' => 'cover_images'])->getSecurePath();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];
        $user->address = $validated['address'];
        $user->city = $validated['city'];
        $user->state = $validated['state'];
        $user->zip_code = $validated['zip_code'];
        $user->country = $validated['country'] ?? 'Pakistan';
        
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
