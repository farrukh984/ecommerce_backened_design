<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\NewUserAlert;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['google' => 'Google login failed. Please try again.']);
        }

        if (! $googleUser || ! $googleUser->getEmail()) {
            return redirect()->route('login')->withErrors(['google' => 'Unable to retrieve Google account information.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        // Admin ko Google se login krne ki ijazat nahi
        if ($user && $user->role === 'admin') {
            return redirect()->route('login')->withErrors([
                'google' => 'Admin account cannot use Google login. Please use your email and password.'
            ]);
        }

        if (! $user) {
            $user = User::create([
                'name'          => $googleUser->getName() ?? $googleUser->getNickname() ?? $googleUser->getEmail(),
                'email'         => $googleUser->getEmail(),
                'password'      => bcrypt(Str::random(16)),
                'role'          => 'user',
                'profile_image' => $googleUser->getAvatar(),
            ]);

            // Notify Admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                try {
                    Mail::to($admin->email)->send(new NewUserAlert($user));
                } catch (\Exception $e) { \Log::error("Admin Google Reg Mail Error: " . $e->getMessage()); }
            }
        }

        Auth::login($user, true);

        try {
            request()->session()->regenerate();
        } catch (\Throwable $e) {
            // ignore if session not available
        }

        return redirect()->route('user.dashboard');
    }
}
