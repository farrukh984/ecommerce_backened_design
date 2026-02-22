@extends('layouts.admin')

@section('title', 'Products Management')
@section('header_title', 'Manage Products')

@section('admin_content')

<div class="premium-card">
    <div class="action-header">
        <div class="header-title">
            <h2>Product List</h2>
            <p>Showing all products available in your storage</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.products.create') }}" class="btn-primary">
                <i class="fa-solid fa-plus"></i> Add Product
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Product Details</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Visibility</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>#{{ $product->id }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img src="{{ asset('storage/' . $product->image) }}" style="width: 40px; height: 40px; border-radius: 6px; object-fit: cover; border: 1px solid #eee;">
                            <div>
                                <div style="font-weight: 700; color: #1c1c1c;">{{ $product->name }}</div>
                                <div style="font-size: 11px; color: #8b96a5;">{{ $product->brand }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="btn-outline" style="font-size: 11px; padding: 2px 8px;">
                            {{ $product->category->name ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 700;">${{ number_format($product->price, 2) }}</div>
                        @if($product->old_price)
                        <div style="font-size: 11px; color: #eb001b; text-decoration: line-through;">${{ number_format($product->old_price, 2) }}</div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 700; color: {{ $product->stock_quantity < 10 ? '#eb001b' : '#1c1c1c' }};">
                            {{ $product->stock_quantity }} pcs
                            @if($product->stock_quantity > 0 && $product->stock_quantity < 10)
                                <div style="font-size: 10px; color: #ff9017; font-weight: 600;"><i class="fa-solid fa-triangle-exclamation"></i> Low Stock</div>
                            @elseif($product->stock_quantity == 0)
                                <div style="font-size: 10px; color: #eb001b; font-weight: 600;"><i class="fa-solid fa-circle-xmark"></i> Out of Stock</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($product->stock_quantity > 0)
                            <span class="status-label status-active" style="background: #e5f8ed; color: #00b517;">Live</span>
                        @else
                            <span class="status-label" style="background: #fee2e2; color: #eb001b; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;">Sold Out</span>
                        @endif
                    </td>
                    <td>
                        @if($product->is_active)
                            <span style="color: #0d6efd; font-weight: 600;"><i class="fa-solid fa-eye"></i> Visible</span>
                        @else
                            <span style="color: #64748b; font-weight: 600;"><i class="fa-solid fa-eye-slash"></i> Hidden</span>
                        @endif
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-outline" style="padding: 6px 10px; color: var(--admin-primary);">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Silni ha?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-outline" style="padding: 6px 10px; color: #eb001b; cursor: pointer;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
