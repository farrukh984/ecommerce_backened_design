<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Admin\NewUserAlert;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->scopes(['email', 'public_profile'])
            ->with(['auth_type' => 'rerequest'])
            ->stateless()
            ->redirect();
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
        } catch (\Exception $e) {
            \Log::error('Facebook login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['facebook' => 'Facebook login failed. Please try again.']);
        }

        if (!$facebookUser) {
            return redirect()->route('login')->withErrors(['facebook' => 'Unable to retrieve Facebook account information.']);
        }

        // Email mil jaye to use karo, warna facebook ID se banao
        $email = $facebookUser->getEmail();
        if (!$email) {
            $email = 'fb_' . $facebookUser->getId() . '@facebook-user.com';
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name'          => $facebookUser->getName() ?? $facebookUser->getNickname() ?? $facebookUser->getEmail(),
                'email'         => $facebookUser->getEmail(),
                'password'      => bcrypt(Str::random(16)),
                'role'          => 'user',
                'profile_image' => $facebookUser->getAvatar(),
            ]);

            // Notify Admin
            $admin = User::where('role', 'admin')->first();
            if ($admin) {
                try {
                    Mail::to($admin->email)->send(new NewUserAlert($user));
                } catch (\Exception $e) { \Log::error("Admin Facebook Reg Mail Error: " . $e->getMessage()); }
            }
        }

        Auth::login($user, true);

        try {
            request()->session()->regenerate();
        } catch (\Throwable $e) {
            // ignore if session not available
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }
}
