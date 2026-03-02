<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('name')->get();
        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:suppliers,name',
            'location' => 'nullable|string',
            'country_flag' => 'nullable|string',
            'is_verified' => 'boolean',
            'has_worldwide_shipping' => 'boolean',
        ]);

        // Default booleans if not provided in request (due to checkboxes)
        $data['is_verified'] = $request->has('is_verified');
        $data['has_worldwide_shipping'] = $request->has('has_worldwide_shipping');

        Supplier::create($data);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:suppliers,name,' . $supplier->id,
            'location' => 'nullable|string',
            'country_flag' => 'nullable|string',
            'is_verified' => 'boolean',
            'has_worldwide_shipping' => 'boolean',
        ]);

        // Handle booleans
        $data['is_verified'] = $request->has('is_verified');
        $data['has_worldwide_shipping'] = $request->has('has_worldwide_shipping');

        $supplier->update($data);
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
