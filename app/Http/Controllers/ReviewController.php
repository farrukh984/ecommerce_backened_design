<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductReview;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\Admin\NewReviewAlert;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // چیک کریں کہ کیا یوزر نے یہ پروڈکٹ خریدا ہے
        $order = Order::where('user_id', $user->id)
            ->whereHas('items', function($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->where('status', 'delivered') // صرف ڈیلیورڈ آرڈرز پر ریویو دے سکتے ہیں
            ->latest()
            ->first();

        if (!$order) {
            return back()->withErrors(['review' => 'You can only review products that have been delivered to you.']);
        }

        // چیک کریں کہ کہیں پہلے ریویو تو نہیں دے دیا
        $existingReview = ProductReview::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existingReview) {
            return back()->withErrors(['review' => 'You have already reviewed this product.']);
        }

        $review = ProductReview::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Notify Admin of New Review
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            try {
                $review->load('product', 'user');
                Mail::to($admin->email)->send(new NewReviewAlert($review));
            } catch (\Exception $e) { \Log::error("Admin Review Mail Error: " . $e->getMessage()); }
        }

        return back()->with('success', 'Thank you for your review!');
    }
}
