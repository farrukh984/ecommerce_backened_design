<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

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
        // Share categories to all views for dynamic nav/search selects
        try {
            View::share('categories', Category::orderBy('name')->get());
        } catch (\Throwable $e) {
            // ignore when running migrations or before DB exists
        }
    }
}
