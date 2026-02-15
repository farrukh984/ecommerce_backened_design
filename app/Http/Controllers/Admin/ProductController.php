<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Condition;
use App\Models\Feature;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at','desc')->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $conditions = Condition::orderBy('name')->get();
        $features = Feature::orderBy('name')->get();
        return view('admin.products.create', compact('categories','brands','conditions','features'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'old_price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'rating' => 'nullable|integer|min:0|max:5',
            'condition_id' => 'nullable|exists:conditions,id',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        // Resolve brand_id to brand name
        $data['brand'] = null;
        if (!empty($data['brand_id'])) {
            $brand = Brand::find($data['brand_id']);
            if ($brand) $data['brand'] = $brand->name;
        }
        unset($data['brand_id']);

        // Extract features for later attachment
        $features = $data['features'] ?? [];
        unset($data['features']);

        // handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product = Product::create($data);
        
        // Attach features
        if (!empty($features)) {
            $product->features()->attach($features);
        }

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $conditions = Condition::orderBy('name')->get();
        $features = Feature::orderBy('name')->get();
        return view('admin.products.edit', compact('product','categories','brands','conditions','features'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'old_price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'rating' => 'nullable|integer|min:0|max:5',
            'condition_id' => 'nullable|exists:conditions,id',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        // Resolve brand_id to brand name
        $data['brand'] = null;
        if (!empty($data['brand_id'])) {
            $brand = Brand::find($data['brand_id']);
            if ($brand) $data['brand'] = $brand->name;
        }
        unset($data['brand_id']);

        // Extract features for later attachment
        $features = $data['features'] ?? [];
        unset($data['features']);

        // handle image upload (replace)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        $product->update($data);
        
        // Sync features
        $product->features()->sync($features);

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index');
    }
}
