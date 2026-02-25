<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index()
    {
        // Mark all as viewed
        ProductReview::where('is_viewed', false)->update(['is_viewed' => true]);

        $reviews = ProductReview::with('product', 'user')->latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(ProductReview $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Review approved successfully!');
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted successfully!');
    }
}
