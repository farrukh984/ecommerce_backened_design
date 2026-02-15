<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Feature;


class ProductController extends Controller
{

        public function index(Request $request)
        {
            $query = Product::query()->with('category');

            // Search by q (name or category)
            if ($q = $request->query('q')) {
                $query->where(function($qbuilder) use ($q) {
                    $qbuilder->where('name', 'like', "%{$q}%")
                             ->orWhereHas('category', function($c) use ($q) {
                                 $c->where('name', 'like', "%{$q}%");
                             });
                });
            }

            // Filter by category id
            if ($request->category) {
                $query->where('category_id', $request->category);
            }

            // Brand Filter (checkbox list)
            if ($request->brands) {
                $query->whereIn('brand', (array) $request->brands);
            }

            // Rating Filter (checkbox list)
            if ($request->ratings) {
                $rating_values = (array) $request->ratings;
                $query->where(function($q) use ($rating_values) {
                    foreach($rating_values as $rating) {
                        $q->orWhere('rating', '=', $rating);
                    }
                });
            }

            // Condition Filter (checkbox list) - using condition_id
            if ($request->conditions) {
                $query->whereIn('condition_id', (array) $request->conditions);
            }

            // Features Filter (checkbox list) - feature IDs via pivot
            if ($request->features) {
                $feature_list = (array) $request->features;
                foreach($feature_list as $featureId) {
                    $query->whereHas('features', function($q) use ($featureId) {
                        $q->where('features.id', $featureId);
                    });
                }
            }

            // Price Filter
            if ($request->min_price && $request->max_price) {
                $query->whereBetween('price', [$request->min_price, $request->max_price]);
            }

            $products = $query->paginate(9)->withQueryString();

            // Dynamic filter data
            $brands = Product::whereNotNull('brand')->distinct()->pluck('brand')->sort();
            $conditions = Condition::orderBy('name')->get();
            $ratings = [5, 4, 3, 2, 1];

            // Get all features from features table
            $features = Feature::orderBy('name')->get();

            // Get price range
            $minPrice = Product::whereNotNull('price')->min('price') ?? 0;
            $maxPrice = Product::whereNotNull('price')->max('price') ?? 1000;

            return view('products.index', compact('products', 'brands', 'conditions', 'ratings', 'features', 'minPrice', 'maxPrice'));
        }

        public function show($id)
        {
            $product = Product::with('category')->findOrFail($id);

            // Similar products from same category
            $similarProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->take(6)
                ->get();

            if ($similarProducts->count() < 6) {
                $more = Product::where('id', '!=', $product->id)
                    ->whereNotIn('id', $similarProducts->pluck('id'))
                    ->inRandomOrder()
                    ->take(6 - $similarProducts->count())
                    ->get();
                $similarProducts = $similarProducts->merge($more);
            }

            // You may also like (random products)
            $youMayLike = Product::where('id', '!=', $product->id)
                ->inRandomOrder()
                ->limit(6)
                ->get();

            return view('products.show', compact('product', 'similarProducts', 'youMayLike'));
        }


}
