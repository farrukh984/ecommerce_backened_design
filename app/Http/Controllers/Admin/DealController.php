<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Product;
use Illuminate\Http\Request;

class DealController extends Controller
{
    public function index()
    {
        $deals = Deal::with('product')->latest()->paginate(10);
        return view('admin.deals.index', compact('deals'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.deals.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'discount_percent' => 'required|integer|min:1|max:99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Deal::create($validated);

        return redirect()->route('admin.deals.index')->with('success', 'Deal created successfully!');
    }

    public function edit(Deal $deal)
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.deals.edit', compact('deal', 'products'));
    }

    public function update(Request $request, Deal $deal)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'discount_percent' => 'required|integer|min:1|max:99',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
            'product_id' => 'nullable|exists:products,id',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $deal->update($validated);

        return redirect()->route('admin.deals.index')->with('success', 'Deal updated successfully!');
    }

    public function destroy(Deal $deal)
    {
        $deal->delete();
        return redirect()->route('admin.deals.index')->with('success', 'Deal deleted successfully!');
    }
}
