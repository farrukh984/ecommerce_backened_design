@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<style>
    .order-detail-container {
        max-width: 100%;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 24px;
        transition: color 0.2s;
    }
    .back-link:hover { color: #2563eb; }
    .order-detail-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
    }
    .detail-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .detail-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .detail-card-header h3 {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .detail-card-body {
        padding: 24px;
    }
    .detail-item-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid #f8fafc;
    }
    .detail-item-row:last-child {
        border-bottom: none;
    }
    .detail-item-img {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    .detail-item-placeholder {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
        font-size: 24px;
        flex-shrink: 0;
    }
    .detail-item-info {
        flex: 1;
        min-width: 0;
    }
    .detail-item-name {
        font-weight: 700;
        color: #0f172a;
        font-size: 15px;
        margin-bottom: 4px;
    }
    .detail-item-attrs {
        font-size: 12px;
        color: #94a3b8;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .detail-item-attr-tag {
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 11px;
    }
    .detail-item-price-block {
        text-align: right;
        flex-shrink: 0;
    }
    .detail-item-price {
        font-weight: 800;
        color: #0f172a;
        font-size: 16px;
    }
    .detail-item-unit {
        font-size: 12px;
        color: #94a3b8;
    }
    .order-summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 14px;
        color: #64748b;
    }
    .order-summary-total {
        display: flex;
        justify-content: space-between;
        padding: 14px 0 0;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        border-top: 2px solid #f1f5f9;
        margin-top: 8px;
    }
    .order-summary-total span:last-child {
        color: #3b82f6;
    }
    .info-list {
        font-size: 14px;
        color: #475569;
        line-height: 2;
    }
    .info-list i {
        width: 20px;
        color: #94a3b8;
        margin-right: 8px;
    }
    .status-timeline {
        display: flex;
        flex-direction: column;
        gap: 0;
        padding: 0;
    }
    .timeline-step {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        position: relative;
        padding-bottom: 24px;
    }
    .timeline-step:last-child {
        padding-bottom: 0;
    }
    .timeline-dot {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        z-index: 2;
    }
    .timeline-dot.active {
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
        box-shadow: 0 2px 8px rgba(59,130,246,0.3);
    }
    .timeline-dot.completed {
        background: #16a34a;
        color: white;
    }
    .timeline-dot.inactive {
        background: #f1f5f9;
        color: #cbd5e1;
    }
    .timeline-line {
        position: absolute;
        left: 15px;
        top: 32px;
        bottom: 0;
        width: 2px;
        z-index: 1;
    }
    .timeline-step:last-child .timeline-line {
        display: none;
    }
    .timeline-content h4 {
        margin: 0;
        font-size: 14px;
        color: #1e293b;
    }
    .timeline-content p {
        margin: 2px 0 0;
        font-size: 12px;
        color: #94a3b8;
    }
    @media (max-width: 991px) {
        .order-detail-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'orders'])

    <main class="dashboard-main">
        <div class="order-detail-container">
            <a href="{{ route('user.orders') }}" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>

            <div class="order-detail-grid">
                <!-- Left: Order Items -->
                <div>
                    <div class="detail-card" style="margin-bottom: 24px;">
                        <div class="detail-card-header">
                            <h3><i class="fa-solid fa-bag-shopping" style="color: #3b82f6; margin-right: 8px;"></i>Order #{{ $order->id }}</h3>
                            @php
                                $statusClass = match(strtolower($order->status)) {
                                    'delivered', 'completed' => 'delivered',
                                    'canceled', 'cancelled', 'failed' => 'cancelled',
                                    'approved' => 'approved',
                                    'processing' => 'processing',
                                    'shipped' => 'shipped',
                                    default => 'pending',
                                };
                                $statusColors = [
                                    'pending' => ['bg' => '#fff8eb', 'color' => '#a16207'],
                                    'approved' => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
                                    'processing' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                                    'shipped' => ['bg' => '#e7f0ff', 'color' => '#0d6efd'],
                                    'delivered' => ['bg' => '#dcfce7', 'color' => '#166534'],
                                    'cancelled' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                                ];
                                $sc = $statusColors[$statusClass] ?? $statusColors['pending'];
                            @endphp
                            <span style="padding: 6px 16px; border-radius: 20px; background: {{ $sc['bg'] }}; color: {{ $sc['color'] }}; font-weight: 700; font-size: 13px;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="detail-card-body" style="padding: 16px 24px;">
                            @foreach($order->items as $item)
                                <div class="detail-item-row">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ display_image($item->product->image) }}" class="detail-item-img" alt="{{ $item->product->name ?? 'Product' }}">
                                    @else
                                        <div class="detail-item-placeholder">
                                            <i class="fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                    <div class="detail-item-info">
                                        <div class="detail-item-name">{{ $item->product->name ?? 'Deleted Product' }}</div>
                                        @if($item->attributes && count(array_filter($item->attributes)) > 0)
                                            <div class="detail-item-attrs">
                                                @foreach(array_filter($item->attributes) as $key => $val)
                                                    <span class="detail-item-attr-tag">{{ ucfirst($key) }}: {{ $val }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <div class="detail-item-unit" style="margin-top: 6px;">
                                            ${{ number_format($item->price, 2) }} × {{ $item->quantity }}
                                        </div>
                                    </div>
                                    <div class="detail-item-price-block">
                                        <div class="detail-item-price">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div style="padding: 0 24px 24px;">
                            <div style="background: #f8fafc; border-radius: 12px; padding: 20px;">
                                <div class="order-summary-row">
                                    <span>Subtotal</span>
                                    <span>${{ number_format($order->total_amount - $order->tax_amount - $order->shipping_amount + $order->discount_amount, 2) }}</span>
                                </div>
                                <div class="order-summary-row">
                                    <span>Tax</span>
                                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                                <div class="order-summary-row">
                                    <span>Shipping</span>
                                    <span>{{ $order->shipping_amount > 0 ? '$'.number_format($order->shipping_amount, 2) : 'Free' }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                <div class="order-summary-row" style="color: #16a34a;">
                                    <span>Discount</span>
                                    <span>-${{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                                @endif
                                <div class="order-summary-total">
                                    <span>Total</span>
                                    <span>${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Order Info Sidebar -->
                <div style="display: flex; flex-direction: column; gap: 24px;">
                    <!-- Status Timeline -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h3 style="font-size: 15px;"><i class="fa-solid fa-route" style="color: #3b82f6; margin-right: 8px;"></i>Order Status</h3>
                        </div>
                        <div class="detail-card-body">
                            @php
                                $statuses = ['pending', 'approved', 'processing', 'shipped', 'delivered'];
                                $currentIndex = array_search($order->status, $statuses);
                                if ($currentIndex === false) $currentIndex = -1;
                                $isCancelled = $order->status === 'cancelled';
                            @endphp
                            <div class="status-timeline">
                                @foreach($statuses as $i => $st)
                                    <div class="timeline-step">
                                        @if($isCancelled)
                                            <div class="timeline-dot inactive"><i class="fa-solid fa-circle"></i></div>
                                            <div class="timeline-line" style="background: #e2e8f0;"></div>
                                        @elseif($i < $currentIndex)
                                            <div class="timeline-dot completed"><i class="fa-solid fa-check"></i></div>
                                            <div class="timeline-line" style="background: #16a34a;"></div>
                                        @elseif($i === $currentIndex)
                                            <div class="timeline-dot active"><i class="fa-solid fa-circle"></i></div>
                                            <div class="timeline-line" style="background: #e2e8f0;"></div>
                                        @else
                                            <div class="timeline-dot inactive"><i class="fa-solid fa-circle"></i></div>
                                            <div class="timeline-line" style="background: #e2e8f0;"></div>
                                        @endif
                                        <div class="timeline-content">
                                            <h4 style="{{ $i <= $currentIndex && !$isCancelled ? 'color: #0f172a;' : 'color: #cbd5e1;' }}">{{ ucfirst($st) }}</h4>
                                            @if($i === $currentIndex && !$isCancelled)
                                                <p style="color: #3b82f6; font-weight: 600;">Current Status</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @if($isCancelled)
                                    <div class="timeline-step">
                                        <div class="timeline-dot" style="background: #dc2626; color: white;"><i class="fa-solid fa-times"></i></div>
                                        <div class="timeline-content">
                                            <h4 style="color: #dc2626;">Cancelled</h4>
                                            <p style="color: #dc2626; font-weight: 600;">Order was cancelled</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h3 style="font-size: 15px;"><i class="fa-solid fa-truck" style="color: #3b82f6; margin-right: 8px;"></i>Shipping Address</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="info-list">
                                <div><i class="fa-solid fa-user"></i>{{ $order->name }}</div>
                                <div><i class="fa-solid fa-envelope"></i>{{ $order->email }}</div>
                                <div><i class="fa-solid fa-phone"></i>{{ $order->phone }}</div>
                                <div><i class="fa-solid fa-map-marker-alt"></i>{{ $order->address }}</div>
                                <div><i class="fa-solid fa-city"></i>{{ $order->city }}, {{ $order->state ?? '' }} {{ $order->zip_code }}</div>
                                <div><i class="fa-solid fa-globe"></i>{{ $order->country }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Info -->
                    <div class="detail-card">
                        <div class="detail-card-header">
                            <h3 style="font-size: 15px;"><i class="fa-solid fa-info-circle" style="color: #3b82f6; margin-right: 8px;"></i>Order Info</h3>
                        </div>
                        <div class="detail-card-body">
                            <div class="info-list">
                                <div><i class="fa-solid fa-calendar"></i>Placed: {{ $order->created_at->format('M d, Y h:i A') }}</div>
                                <div><i class="fa-solid fa-clock"></i>Updated: {{ $order->updated_at->diffForHumans() }}</div>
                                @if($order->notes)
                                    <div style="margin-top: 12px; padding: 12px; background: #f8fafc; border-radius: 8px; font-size: 13px; color: #64748b;">
                                        <strong>Notes:</strong> {{ $order->notes }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delete Order -->
                    @if(in_array($order->status, ['pending', 'cancelled']))
                    <form method="POST" action="{{ route('user.orders.delete', $order->id) }}" onsubmit="return confirm('Are you sure you want to delete this order permanently?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 100%; padding: 14px; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                            <i class="fa-solid fa-trash"></i> Delete This Order
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </main>
</div>
@endsection
