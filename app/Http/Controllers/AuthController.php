<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Login
    public function login()
    {
        return view('auth.login');
    }

    // Login Store
    public function loginStore(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'You are not registered. Please register first.']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security practice
            $this->syncGuestWishlist($request);

            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['password' => 'Incorrect password']);
    }

    // Show Register
    public function register()
    {
        return view('auth.register');
    }

    // Register Store
    public function registerStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = 'user';

        User::create($data);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    private function syncGuestWishlist(Request $request): void
    {
        $wishlist = $request->session()->get('wishlist', []);
        if (empty($wishlist)) {
            return;
        }

        foreach ($wishlist as $productId) {
            WishlistItem::firstOrCreate([
                'user_id' => auth()->id(),
                'product_id' => $productId,
            ]);
        }

        $request->session()->forget('wishlist');
    }
}
