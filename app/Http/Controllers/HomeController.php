<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
 public function index()
{
    $categories = Category::with('products')->get();
    $recommended = Product::latest()->take(10)->get();
    $deals = Product::whereNotNull('old_price')->take(6)->get();

    return view('pages.home', compact(
        'categories',
        'recommended',
        'deals'
    ));
}

}
