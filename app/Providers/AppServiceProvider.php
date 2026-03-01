<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Support\Facades\URL;

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

        // Share categories to all views for dynamic nav/search selects
        try {
            View::share('categories', Category::orderBy('name')->get());
            
            // Share unread message count for admin
            View::composer('layouts.admin', function ($view) {
                if (auth()->check()) {
                    $unreadCount = \App\Models\Message::where('is_read', false)
                        ->whereHas('conversation', function ($q) {
                            $q->where('receiver_id', auth()->id());
                        })->count();
                    $view->with('unreadAdminCount', $unreadCount);
                }
            });
        } catch (\Throwable $e) {
            // ignore when running migrations or before DB exists
        }
    }
}
