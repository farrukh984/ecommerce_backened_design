<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminOtpMail;

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
        \Log::info('Login attempt for: ' . ($request->email ?? 'no email') . '. User found: ' . ($user ? 'YES (Role: ' . $user->role . ')' : 'NO'));
        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'You are not registered. Please register first.']);
        }

        // Verify password manually so we can handle admin OTP flow before logging in
        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password']);
        }

        // If admin, send OTP email and require OTP verification
        if ($user->role === 'admin') {
            \Log::info('Admin login detected for: ' . $user->email);
            $otp = rand(100000, 999999);
            Cache::put('admin_otp_' . $user->id, $otp, now()->addMinutes(10));
            // store pending admin id in session
            $request->session()->put('pending_admin_id', $user->id);

            // send OTP email
            try {
                \Log::info('Attempting to send OTP email to ' . $user->email . ' with OTP: ' . $otp);
                Mail::to($user->email)->send(new AdminOtpMail($user, $otp));
                \Log::info('OTP email sent successfully to ' . $user->email);
            } catch (\Throwable $e) {
                \Log::error('Admin OTP Email Error: ' . $e->getMessage());
                return back()->withErrors(['email' => 'Unable to send OTP email. Please check logs.']);
            }

            return redirect()->route('admin.otp')->with('success', 'OTP sent to admin email.');
        }

        // Regular user login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Security practice
            $this->syncGuestWishlist($request);

            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['password' => 'Incorrect password']);
    }

    // Show OTP input page for admin
    public function showAdminOtp(Request $request)
    {
        if (! $request->session()->has('pending_admin_id')) {
            return redirect()->route('login');
        }

        return view('auth.admin_otp');
    }

    // Verify OTP for admin and log them in
    public function verifyAdminOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $pendingId = $request->session()->get('pending_admin_id');
        if (! $pendingId) {
            return redirect()->route('login')->withErrors(['otp' => 'No pending admin login. Please login again.']);
        }

        $cached = Cache::get('admin_otp_' . $pendingId);
        if (! $cached || $cached != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }

        // OTP matched — clear cache and session, log the admin in
        Cache::forget('admin_otp_' . $pendingId);
        $request->session()->forget('pending_admin_id');

        $user = User::find($pendingId);
        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found']);
        }

        Auth::login($user, true);
        try { request()->session()->regenerate(); } catch (\Throwable $e) {}

        return redirect()->route('admin.dashboard');
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
