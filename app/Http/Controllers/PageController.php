<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Deal;

class PageController extends Controller
{
    public function hotOffers()
    {
        $activeDeal = Deal::activeDeal();

        $products = Product::where('is_active', true)
            ->whereNotNull('old_price')
            ->latest()
            ->paginate(16);

        return view('pages.hot-offers', compact('products', 'activeDeal'));
    }

    public function giftBoxes()
    {
        $products = Product::where('is_active', true)
            ->latest()
            ->take(12)
            ->get();

        return view('pages.gift-boxes', compact('products'));
    }

    public function help()
    {
        return view('pages.help');
    }
}
