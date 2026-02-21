<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $recentOrders = $user->orders()
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
        $orders = Auth::user()
            ->orders()
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('user.orders', compact('orders'));
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
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('cover_images', 'public');
            $user->cover_image = $path;
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
