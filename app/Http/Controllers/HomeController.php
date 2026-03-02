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
                'deals' => Product::where('is_active', true)->whereNotNull('old_price')->take(6)->get(),
                'activeDeal' => Deal::activeDeal()
            ];
        });

        return view('pages.home', [
            'categories' => $homeData['categories'],
            'recommended' => $homeData['recommended'],
            'deals' => $homeData['deals'],
            'activeDeal' => $homeData['activeDeal']
        ]);
    }

}
