<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'details' => 'nullable|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'unit' => 'required|string|in:Pcs,Kg,Sets',
        ]);

        // Get user email if authenticated, otherwise use a default
        $userEmail = auth()->check() ? auth()->user()->email : 'guest@example.com';
        $userName = auth()->check() ? auth()->user()->name : 'Guest';

        // Log the inquiry (you can also save to database if needed)
        Log::info('Inquiry submitted', [
            'user' => $userEmail,
            'item' => $validated['item'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
        ]);

        // Here you can:
        // 1. Save to database
        // 2. Send email notification
        // 3. Send to admin panel

        return redirect()->back()->with('success', 'Your inquiry has been sent successfully! We will contact you soon.');
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        // Log the subscription (you can also save to database if needed)
        Log::info('Newsletter subscription', [
            'email' => $validated['email'],
        ]);

        // Here you can:
        // 1. Save to database
        // 2. Add to mailing list service
        // 3. Send confirmation email

        return redirect()->back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
