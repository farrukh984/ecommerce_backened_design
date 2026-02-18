@extends('layouts.admin')

@section('title', 'Order #' . $order->id)
@section('header_title', 'Order Details')

@section('admin_content')

<div style="margin-bottom: 24px;">
    <a href="{{ route('admin.orders.index') }}" style="color: var(--admin-primary); text-decoration: none; font-size: 14px; font-weight: 500;">
        <i class="fa-solid fa-arrow-left"></i> Back to Orders
    </a>
</div>

@if(session('success'))
    <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; margin-bottom: 24px;">
        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 1fr 360px; gap: 24px;">
    <!-- Order Items -->
    <div class="premium-card">
        <div class="action-header">
            <div class="header-title">
                <h2>Order #{{ $order->id }}</h2>
                <p>Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
            @php
                $statusColors = [
                    'pending' => ['bg' => '#fff8eb', 'color' => '#a16207'],
                    'approved' => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
                    'processing' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                    'shipped' => ['bg' => '#e7f0ff', 'color' => '#0d6efd'],
                    'delivered' => ['bg' => '#dcfce7', 'color' => '#166534'],
                    'cancelled' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                ];
                $sc = $statusColors[$order->status] ?? $statusColors['pending'];
            @endphp
            <span style="padding: 6px 16px; border-radius: 20px; background: {{ $sc['bg'] }}; color: {{ $sc['color'] }}; font-weight: 700; font-size: 13px;">
                {{ ucfirst($order->status) }}
            </span>
        </div>

        <div class="table-responsive">
            <table class="premium-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid var(--admin-border);">
                                    @else
                                        <div style="width: 48px; height: 48px; border-radius: 8px; background: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa-solid fa-image" style="color: #cbd5e1;"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <strong>{{ $item->product->name ?? 'Deleted Product' }}</strong>
                                        @if($item->attributes)
                                            <div style="font-size: 12px; color: var(--admin-text-sub); margin-top: 2px;">
                                                @foreach(array_filter($item->attributes) as $key => $val)
                                                    {{ ucfirst($key) }}: {{ $val }}{{ !$loop->last ? ' · ' : '' }}
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><strong>${{ number_format($item->price * $item->quantity, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div style="padding: 24px; border-top: 1px solid var(--admin-border);">
            <div style="max-width: 300px; margin-left: auto;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--admin-text-sub);">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--admin-text-sub);">
                    <span>Tax</span>
                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: var(--admin-text-sub);">
                    <span>Shipping</span>
                    <span>${{ number_format($order->shipping_amount, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; color: #15803d;">
                    <span>Discount</span>
                    <span>-${{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <hr style="border: none; border-top: 1px solid var(--admin-border); margin: 12px 0;">
                <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 800; font-family: 'Outfit', sans-serif;">
                    <span>Total</span>
                    <span style="color: var(--admin-primary);">${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        <!-- Update Status -->
        <div class="premium-card">
            <div style="padding: 24px;">
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; margin-bottom: 16px;">Update Status</h3>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-control" style="margin-bottom: 12px;">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                        <option value="approved" {{ $order->status === 'approved' ? 'selected' : '' }}>✅ Approved</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>📦 Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                    </select>
                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fa-solid fa-check"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="premium-card">
            <div style="padding: 24px;">
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; margin-bottom: 16px;">Customer Info</h3>
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                    <div style="width: 44px; height: 44px; border-radius: 12px; background: var(--admin-primary-light); color: var(--admin-primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                        {{ strtoupper(substr($order->name, 0, 1)) }}
                    </div>
                    <div>
                        <strong style="display: block;">{{ $order->name }}</strong>
                        <span style="font-size: 13px; color: var(--admin-text-sub);">{{ $order->email }}</span>
                    </div>
                </div>
                <div style="font-size: 14px; color: #444; line-height: 1.8;">
                    <div><i class="fa-solid fa-phone" style="width: 18px; color: var(--admin-text-sub);"></i> {{ $order->phone }}</div>
                    <div><i class="fa-solid fa-map-marker-alt" style="width: 18px; color: var(--admin-text-sub);"></i> {{ $order->address }}</div>
                    <div><i class="fa-solid fa-city" style="width: 18px; color: var(--admin-text-sub);"></i> {{ $order->city }}, {{ $order->state ?? '' }} {{ $order->zip_code }}</div>
                    <div><i class="fa-solid fa-globe" style="width: 18px; color: var(--admin-text-sub);"></i> {{ $order->country }}</div>
                </div>
            </div>
        </div>

        <!-- Order Notes -->
        @if($order->notes)
        <div class="premium-card">
            <div style="padding: 24px;">
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; margin-bottom: 12px;">Order Notes</h3>
                <p style="font-size: 14px; color: #444; line-height: 1.6; background: #f8fafc; padding: 12px; border-radius: 8px;">{{ $order->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('styles')
<style>
    @media (max-width: 991px) {
        div[style*="grid-template-columns: 1fr 360px"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection
