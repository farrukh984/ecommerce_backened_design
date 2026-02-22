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
            'gallery.*' => 'nullable|image|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'rating' => 'nullable|numeric|min:0|max:5',
            'condition_id' => 'nullable|exists:conditions,id',
            'is_negotiable' => 'nullable|boolean',
            'in_stock' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'type' => 'nullable|string',
            'material' => 'nullable|string',
            'design_style' => 'nullable|string',
            'customization' => 'nullable|string',
            'protection' => 'nullable|string',
            'warranty' => 'nullable|string',
            'model_number' => 'nullable|string',
            'item_number' => 'nullable|string',
            'size' => 'nullable|string',
            'memory' => 'nullable|string',
            'certificate' => 'nullable|string',
            'style' => 'nullable|string',
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

        // Extract features
        $features = $data['features'] ?? [];
        unset($data['features']);

        // handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);
        
        // Attach features
        $product->features()->attach($features);

        // Handle Gallery
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $path = $img->store('products/gallery', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $conditions = Condition::orderBy('name')->get();
        $features = Feature::orderBy('name')->get();
        $suppliers = \App\Models\Supplier::orderBy('name')->get();
        return view('admin.products.edit', compact('product','categories','brands','conditions','features', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'old_price' => 'nullable|numeric',
            'image' => 'nullable|image|max:2048',
            'gallery.*' => 'nullable|image|max:2048',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'rating' => 'nullable|numeric|min:0|max:5',
            'condition_id' => 'nullable|exists:conditions,id',
            'is_negotiable' => 'nullable|boolean',
            'in_stock' => 'nullable|boolean',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'type' => 'nullable|string',
            'material' => 'nullable|string',
            'design_style' => 'nullable|string',
            'customization' => 'nullable|string',
            'protection' => 'nullable|string',
            'warranty' => 'nullable|string',
            'model_number' => 'nullable|string',
            'item_number' => 'nullable|string',
            'size' => 'nullable|string',
            'memory' => 'nullable|string',
            'certificate' => 'nullable|string',
            'style' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*' => 'exists:features,id',
        ]);

        $data['brand'] = null;
        if (!empty($data['brand_id'])) {
            $brand = Brand::find($data['brand_id']);
            if ($brand) $data['brand'] = $brand->name;
        }
        unset($data['brand_id']);

        $features = $data['features'] ?? [];
        unset($data['features']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        $product->features()->sync($features);

        // Handle Gallery (append)
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                $path = $img->store('products/gallery', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index');
    }
}
