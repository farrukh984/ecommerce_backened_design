<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Feature;
use App\Models\WishlistItem;


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

            // Rating Filter (Minimum Rating)
            if ($request->ratings) {
                $rating_values = (array) $request->ratings;
                $min_rating = min($rating_values);
                $query->where('rating', '>=', $min_rating);
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

            $categories = Category::orderBy('name')->get();
            $currentCategory = $request->category ? Category::with('parent.parent')->find($request->category) : null;

            return view('products.index', compact('products', 'categories', 'brands', 'conditions', 'ratings', 'features', 'minPrice', 'maxPrice', 'currentCategory'));
        }

        public function show($id)
        {
            $product = Product::with(['category', 'supplier', 'priceTiers', 'features', 'condition'])->findOrFail($id);

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

            // You may also like: Show Liked products (Wishlist)
            $wishlistIds = auth()->check()
                ? auth()->user()->wishlistItems()->pluck('product_id')->all()
                : session()->get('wishlist', []);
            $youMayLike = Product::whereIn('id', $wishlistIds)
                ->where('id', '!=', $product->id)
                ->take(6)
                ->get();

            // If wishlist is empty or small, fill with random products
            if($youMayLike->count() < 6) {
                $countNeeded = 6 - $youMayLike->count();
                $randomExtra = Product::whereNotIn('id', array_merge($wishlistIds, [$product->id]))
                    ->inRandomOrder()
                    ->take($countNeeded)
                    ->get();
                $youMayLike = $youMayLike->merge($randomExtra);
            }

            return view('products.show', compact('product', 'similarProducts', 'youMayLike'));
        }

    public function toggleWishlist(Request $request, $id)
    {
            if (auth()->check()) {
                $wishlistItem = WishlistItem::where('user_id', auth()->id())
                    ->where('product_id', $id)
                    ->first();

                if ($wishlistItem) {
                    $wishlistItem->delete();
                    $status = 'removed';
                } else {
                    WishlistItem::create([
                        'user_id' => auth()->id(),
                        'product_id' => $id,
                    ]);
                    $status = 'added';
                }

                $count = WishlistItem::where('user_id', auth()->id())->count();
            } else {
                $wishlist = session()->get('wishlist', []);
                if (isset($wishlist[$id])) {
                    unset($wishlist[$id]);
                    $status = 'removed';
                } else {
                    $wishlist[$id] = $id;
                    $status = 'added';
                }
                session()->put('wishlist', $wishlist);
                $count = count($wishlist);
            }

            if ($request->ajax()) {
                return response()->json(['status' => $status, 'count' => $count]);
            }
            return redirect()->back();
        }


}
