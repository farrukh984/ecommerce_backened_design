<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Deal;

class HomeController extends Controller
{
    public function index()
    {
        $homeData = \Illuminate\Support\Facades\Cache::remember('home_page_data', 1800, function() {
            return [
                'categories' => Category::with(['products' => function($q) {
                    $q->where('is_active', true);
                }])->get(),
                'recommended' => Product::where('is_active', true)->latest()->take(10)->get(),
            ];
        });

        // Always check for all active deals fresh (outside cache) 
        // to ensure the list is accurate and only shows products selected by admin
        $activeDeals = Deal::where('is_active', true)
            ->where('end_date', '>', now())
            ->with('product')
            ->orderBy('end_date', 'asc')
            ->take(6)
            ->get();

        // The first one is used for the main countdown timer
        $activeDeal = $activeDeals->first();

        return view('pages.home', [
            'categories' => $homeData['categories'],
            'recommended' => $homeData['recommended'],
            'deals' => $activeDeals, // Pass the collection of active deals
            'activeDeal' => $activeDeal
        ]);
    }

}
