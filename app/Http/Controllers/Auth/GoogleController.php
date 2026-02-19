<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        if (! $googleUser || ! $googleUser->getEmail()) {
            return redirect()->route('login')->withErrors(['google' => 'Unable to retrieve Google account information.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? $googleUser->getEmail(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'role' => 'user',
                'profile_image' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);

        // regenerate session to prevent fixation and ensure auth persists
        try {
            request()->session()->regenerate();
        } catch (\Throwable $e) {
            // ignore if session not available
        }

        // Redirect based on role (match behavior of normal login)
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
}
