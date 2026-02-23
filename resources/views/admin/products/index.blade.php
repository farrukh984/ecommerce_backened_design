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

    @if(session('success'))
        <div style="padding: 12px 24px;">
            <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 500;">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        </div>
    @endif

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
                <tr id="product-row-{{ $product->id }}">
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
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="position: relative;">
                                <input type="number" 
                                    id="stock-input-{{ $product->id }}" 
                                    value="{{ $product->stock_quantity }}" 
                                    min="0" 
                                    style="width: 70px; padding: 6px 8px; border: 1px solid {{ $product->stock_quantity < 10 ? ($product->stock_quantity == 0 ? '#fecaca' : '#fde68a') : '#e2e8f0' }}; border-radius: 8px; font-weight: 700; font-size: 13px; text-align: center; outline: none; transition: all 0.2s; background: {{ $product->stock_quantity == 0 ? '#fef2f2' : ($product->stock_quantity < 10 ? '#fffbeb' : '#fff') }}; color: {{ $product->stock_quantity < 10 ? '#dc2626' : '#1c1c1c' }};"
                                    onfocus="this.style.borderColor='var(--admin-primary)'; this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.1)';"
                                    onblur="this.style.boxShadow='none';"
                                    onchange="updateStock({{ $product->id }})">
                            </div>
                            <button onclick="updateStock({{ $product->id }})" style="padding: 6px 8px; border: none; background: var(--admin-primary); color: white; border-radius: 6px; font-size: 11px; cursor: pointer; opacity: 0.8; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.8'">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        </div>
                        <div id="stock-label-{{ $product->id }}" style="margin-top: 4px;">
                            @if($product->stock_quantity > 0 && $product->stock_quantity < 10)
                                <span style="font-size: 10px; color: #d97706; font-weight: 600;"><i class="fa-solid fa-triangle-exclamation"></i> Low Stock</span>
                            @elseif($product->stock_quantity == 0)
                                <span style="font-size: 10px; color: #dc2626; font-weight: 700;"><i class="fa-solid fa-circle-xmark"></i> Out of Stock</span>
                            @else
                                <span style="font-size: 10px; color: #16a34a; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> In Stock</span>
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
                        <label class="toggle-switch" style="position: relative; display: inline-block; width: 48px; height: 26px;">
                            <input type="checkbox" 
                                {{ $product->is_active ? 'checked' : '' }} 
                                onchange="toggleActive({{ $product->id }})"
                                style="opacity: 0; width: 0; height: 0;">
                            <span class="toggle-slider" style="position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: {{ $product->is_active ? '#22c55e' : '#cbd5e1' }}; transition: .3s; border-radius: 26px;" id="toggle-slider-{{ $product->id }}">
                                <span style="position: absolute; content: ''; height: 20px; width: 20px; left: {{ $product->is_active ? '24px' : '3px' }}; bottom: 3px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 1px 3px rgba(0,0,0,0.2);" id="toggle-knob-{{ $product->id }}"></span>
                            </span>
                        </label>
                        <div style="font-size: 11px; margin-top: 4px;" id="visibility-label-{{ $product->id }}">
                            @if($product->is_active)
                                <span style="color: #16a34a; font-weight: 600;"><i class="fa-solid fa-eye"></i> Visible</span>
                            @else
                                <span style="color: #64748b; font-weight: 600;"><i class="fa-solid fa-eye-slash"></i> Hidden</span>
                            @endif
                        </div>
                    </td>
                    <td style="text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 8px;">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-outline" style="padding: 6px 10px; color: var(--admin-primary);">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
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

@section('scripts')
<script>
    function toggleActive(productId) {
        fetch(`/admin/products/${productId}/toggle-active`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const slider = document.getElementById(`toggle-slider-${productId}`);
                const knob = document.getElementById(`toggle-knob-${productId}`);
                const label = document.getElementById(`visibility-label-${productId}`);
                
                if (data.is_active) {
                    slider.style.backgroundColor = '#22c55e';
                    knob.style.left = '24px';
                    label.innerHTML = '<span style="color: #16a34a; font-weight: 600;"><i class="fa-solid fa-eye"></i> Visible</span>';
                } else {
                    slider.style.backgroundColor = '#cbd5e1';
                    knob.style.left = '3px';
                    label.innerHTML = '<span style="color: #64748b; font-weight: 600;"><i class="fa-solid fa-eye-slash"></i> Hidden</span>';
                }
                
                showToast(data.message, 'success');
            }
        });
    }

    function updateStock(productId) {
        const input = document.getElementById(`stock-input-${productId}`);
        const qty = parseInt(input.value);
        
        if (isNaN(qty) || qty < 0) {
            showToast('Please enter a valid stock quantity', 'error');
            return;
        }

        fetch(`/admin/products/${productId}/update-stock`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ stock_quantity: qty })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const label = document.getElementById(`stock-label-${productId}`);
                
                // Update input styling
                if (qty === 0) {
                    input.style.borderColor = '#fecaca';
                    input.style.background = '#fef2f2';
                    input.style.color = '#dc2626';
                    label.innerHTML = '<span style="font-size: 10px; color: #dc2626; font-weight: 700;"><i class="fa-solid fa-circle-xmark"></i> Out of Stock</span>';
                } else if (qty < 10) {
                    input.style.borderColor = '#fde68a';
                    input.style.background = '#fffbeb';
                    input.style.color = '#dc2626';
                    label.innerHTML = '<span style="font-size: 10px; color: #d97706; font-weight: 600;"><i class="fa-solid fa-triangle-exclamation"></i> Low Stock</span>';
                } else {
                    input.style.borderColor = '#e2e8f0';
                    input.style.background = '#fff';
                    input.style.color = '#1c1c1c';
                    label.innerHTML = '<span style="font-size: 10px; color: #16a34a; font-weight: 600;"><i class="fa-solid fa-check-circle"></i> In Stock</span>';
                }
                
                showToast(data.message, 'success');
            }
        });
    }

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.style.cssText = `position: fixed; bottom: 24px; right: 24px; padding: 14px 24px; border-radius: 12px; color: white; font-weight: 600; font-size: 14px; z-index: 9999; animation: slideInRight 0.3s ease; box-shadow: 0 8px 24px rgba(0,0,0,0.15);`;
        toast.style.background = type === 'success' ? 'linear-gradient(135deg, #059669, #10b981)' : 'linear-gradient(135deg, #dc2626, #ef4444)';
        toast.innerHTML = `<i class="fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}" style="margin-right: 8px;"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>

<style>
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
</style>
@endsection
