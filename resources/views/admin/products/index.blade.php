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
                    <th>Status</th>
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
                        <span class="status-label status-active">Live</span>
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
