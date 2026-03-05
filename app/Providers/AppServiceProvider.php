<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\Product;
use App\Models\Message;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Share categories with caching (1 hour)
        try {
            if (!app()->runningInConsole()) {
                $categories = Cache::remember('site_categories', 3600, function () {
                    return Category::orderBy('name')->get();
                });
                View::share('categories', $categories);
                
                // Optimized View Composer for Admin layout
                View::composer('layouts.admin', function ($view) {
                    if (Auth::check() && Auth::user()->role === 'admin') {
                        $authId = Auth::id();

                        // Cache counts for 5 minutes instead of every page load
                        $stats = Cache::remember("admin_counts_{$authId}", 300, function () use ($authId) {
                            return [
                                'unreadMessages' => Message::where('is_read', false)
                                    ->where('user_id', '!=', $authId)
                                    ->whereHas('conversation', function ($q) use ($authId) {
                                        $q->where('sender_id', $authId)
                                          ->orWhere('receiver_id', $authId);
                                    })->count(),
                                'unviewedOrders' => Order::where('is_viewed', false)->count(),
                                'unviewedReviews' => ProductReview::where('is_viewed', false)->count(),
                                'lowStockCount'  => Product::where('stock_quantity', '<', 10)->count(),
                            ];
                        });

                        $view->with([
                            'unreadAdminCount' => $stats['unreadMessages'],
                            'unviewedOrdersCount' => $stats['unviewedOrders'],
                            'unviewedReviewsCount' => $stats['unviewedReviews'],
                            'lowStockCount' => $stats['lowStockCount'],
                        ]);
                    }
                });

                // User Layout Composer (for chat widget unread count)
                View::composer('layouts.app', function ($view) {
                    if (Auth::check()) {
                        $authId = Auth::id();
                        $unreadCount = Cache::remember("user_unread_messages_{$authId}", 60, function () use ($authId) {
                            return Message::where('is_read', false)
                                ->where('user_id', '!=', $authId)
                                ->whereHas('conversation', function ($q) use ($authId) {
                                    $q->where('sender_id', $authId)
                                      ->orWhere('receiver_id', $authId);
                                })->count();
                        });
                        $view->with('unreadUserCount', $unreadCount);
                    }
                });
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }
}
