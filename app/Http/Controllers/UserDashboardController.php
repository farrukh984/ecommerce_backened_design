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
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }

            $user->password = Hash::make($validated['new_password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
