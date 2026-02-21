<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetOtpMail;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = rand(100000, 999999);
        
        // Store OTP in cache for 10 minutes
        Cache::put('password_reset_otp_' . $user->email, $otp, now()->addMinutes(10));
        
        // Store email in session to know which email we are verifying
        session(['reset_email' => $user->email]);

        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($user, $otp));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Unable to send OTP. Please try again later.']);
        }

        return redirect()->route('password.otp')->with('success', 'OTP sent to your email!');
    }

    public function showOtpForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('password.request');
        }

        $cachedOtp = Cache::get('password_reset_otp_' . $email);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP code.']);
        }

        // OTP is valid. Now generate a standard Laravel password reset token
        $user = User::where('email', $email)->first();
        $token = Password::createToken($user);

        // Clear the OTP from cache
        Cache::forget('password_reset_otp_' . $email);

        // Redirect to the reset password form with the token and email
        return redirect()->route('password.reset', [
            'token' => $token,
            'email' => $email
        ]);
    }
}
