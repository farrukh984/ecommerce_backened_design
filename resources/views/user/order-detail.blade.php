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
        color: var(--primary, #3b82f6);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 24px;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--primary-hover, #2563eb); }
    .order-detail-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
    }
    .detail-card {
        background: var(--bg-card, #fff);
        border-radius: 20px;
        border: 1px solid var(--border, #f1f5f9);
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    [data-theme="dark"] .detail-card {
        box-shadow: 0 4px 25px rgba(0,0,0,0.2);
    }
    .detail-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid var(--border, #f1f5f9);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }
    .detail-card-header h3 {
        font-size: 18px;
        font-weight: 800;
        color: var(--text-primary, #0f172a);
        margin: 0;
    }
    .detail-card-body {
        padding: 24px;
    }
    .detail-item-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px;
        margin: 0 -8px;
        border-radius: 12px;
        transition: all 0.3s ease;
        border-bottom: 1px solid var(--border-light, #f8fafc);
    }
    .detail-item-row:hover {
        background: var(--bg-hover, #f8fafc);
        transform: translateX(5px);
    }
    .detail-item-row:last-child {
        border-bottom: none;
    }
    .detail-item-img {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        object-fit: cover;
        border: 1px solid var(--border, #f1f5f9);
        flex-shrink: 0;
    }
    .detail-item-placeholder {
        width: 72px;
        height: 72px;
        border-radius: 12px;
        background: var(--bg-card-alt, #f1f5f9);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-muted, #cbd5e1);
        font-size: 24px;
        flex-shrink: 0;
    }
    .detail-item-info {
        flex: 1;
        min-width: 0;
    }
    .detail-item-name {
        font-weight: 700;
        color: var(--text-primary, #0f172a);
        font-size: 15px;
        margin-bottom: 4px;
    }
    .detail-item-attrs {
        font-size: 12px;
        color: var(--text-muted, #94a3b8);
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    .detail-item-attr-tag {
        background: var(--bg-card-alt, #f1f5f9);
        color: var(--text-secondary, #475569);
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
        color: var(--text-primary, #0f172a);
        font-size: 16px;
    }
    .detail-item-unit {
        font-size: 12px;
        color: var(--text-muted, #94a3b8);
    }
    .order-summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 14px;
        color: var(--text-secondary, #64748b);
    }
    .order-summary-total {
        display: flex;
        justify-content: space-between;
        padding: 18px 0 0;
        font-size: 22px;
        font-weight: 800;
        color: var(--text-primary, #0f172a);
        border-top: 2px dashed var(--border, #f1f5f9);
        margin-top: 12px;
    }
    .order-summary-total span:last-child {
        color: var(--primary, #3b82f6);
        text-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
    }
    .info-list {
        font-size: 14px;
        color: var(--text-secondary, #475569);
        line-height: 2;
    }
    .info-list i {
        width: 20px;
        color: var(--text-muted, #94a3b8);
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
        gap: 18px;
        position: relative;
        padding-bottom: 30px;
    }
    .timeline-step:last-child { padding-bottom: 0; }
    
    .timeline-dot {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        z-index: 2;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .timeline-dot i { transition: transform 0.3s; }
    
    .timeline-dot.completed {
        background: #16a34a;
        color: white;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    }
    .timeline-dot.active {
        background: linear-gradient(135deg, #3b82f6, #0ea5e9);
        color: white;
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
        transform: scale(1.1);
    }
    .timeline-dot.active i { animation: pulseIcon 2s infinite; }
    .timeline-dot.inactive {
        background: var(--bg-card-alt, #f1f5f9);
        color: var(--text-muted, #94a3b8);
        border: 1.5px solid var(--border, #e2e8f0);
    }
    
    @keyframes pulseIcon {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(0.85); opacity: 0.8; }
    }

    .timeline-line {
        position: absolute;
        left: 18px;
        top: 38px;
        bottom: -4px;
        width: 2px;
        background: var(--border, #e2e8f0);
        z-index: 1;
        transition: background 0.3s;
    }
    .timeline-line.active {
        background: #16a34a;
    }
    .timeline-step:last-child .timeline-line { display: none; }
    
    .timeline-content { padding-top: 4px; }
    .timeline-content h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: -0.3px;
    }
    .timeline-content p {
        margin: 4px 0 0;
        font-size: 12px;
    }

    /* Status Badge Glow Handlers */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    .status-badge::after {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shine 3s infinite;
    }
    @keyframes shine {
        0% { left: -100%; }
        20% { left: 100%; }
        100% { left: 100%; }
    }

    .status-dot-blink {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        animation: blink 1.5s infinite;
    }
    @keyframes blink {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(0.8); }
    }
    @media (max-width: 991px) {
        .order-detail-grid {
            grid-template-columns: 1fr !important;
        }
    }
    /* Status Specific Colors */
    .status-badge.pending { --badge-bg: #fff8eb; --badge-text: #a16207; }
    .status-badge.approved { --badge-bg: #e0f2fe; --badge-text: #0369a1; }
    .status-badge.processing { --badge-bg: #f3e8ff; --badge-text: #7e22ce; }
    .status-badge.shipped { --badge-bg: #e0f2fe; --badge-text: #0d6efd; }
    .status-badge.delivered { --badge-bg: #dcfce7; --badge-text: #166534; }
    .status-badge.cancelled { --badge-bg: #fee2e2; --badge-text: #991b1b; }

    [data-theme="dark"] .status-badge.pending { --badge-bg: rgba(245, 158, 11, 0.15); --badge-text: #fbbf24; }
    [data-theme="dark"] .status-badge.approved { --badge-bg: rgba(14, 165, 233, 0.15); --badge-text: #38bdf8; }
    [data-theme="dark"] .status-badge.processing { --badge-bg: rgba(168, 85, 247, 0.15); --badge-text: #c084fc; }
    [data-theme="dark"] .status-badge.shipped { --badge-bg: rgba(59, 130, 246, 0.15); --badge-text: #60a5fa; }
    [data-theme="dark"] .status-badge.delivered { --badge-bg: rgba(16, 185, 129, 0.15); --badge-text: #34d399; }
    [data-theme="dark"] .status-badge.cancelled { --badge-bg: rgba(239, 68, 68, 0.15); --badge-text: #f87171; }
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
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                <span class="status-dot-blink"></span>
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
                            <div style="background: var(--bg-card-alt, #f8fafc); border-radius: 12px; padding: 20px;">
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
                                $statuses = [
                                    ['key' => 'pending', 'icon' => 'fa-clock'],
                                    ['key' => 'approved', 'icon' => 'fa-check-to-slot'],
                                    ['key' => 'processing', 'icon' => 'fa-gears'],
                                    ['key' => 'shipped', 'icon' => 'fa-truck-fast'],
                                    ['key' => 'delivered', 'icon' => 'fa-house-circle-check']
                                ];
                                $statusKeys = array_column($statuses, 'key');
                                $currentIndex = array_search($order->status, $statusKeys);
                                if ($currentIndex === false) $currentIndex = -1;
                                $isCancelled = in_array($order->status, ['cancelled', 'canceled']);
                            @endphp
                            <div class="status-timeline">
                                @foreach($statuses as $i => $st)
                                    <div class="timeline-step">
                                        @if($isCancelled)
                                            <div class="timeline-dot inactive"><i class="fa-solid {{ $st['icon'] }}"></i></div>
                                            <div class="timeline-line"></div>
                                        @elseif($i < $currentIndex)
                                            <div class="timeline-dot completed"><i class="fa-solid fa-check"></i></div>
                                            <div class="timeline-line active"></div>
                                        @elseif($i === $currentIndex)
                                            <div class="timeline-dot active"><i class="fa-solid {{ $st['icon'] }}"></i></div>
                                            <div class="timeline-line"></div>
                                        @else
                                            <div class="timeline-dot inactive"><i class="fa-solid {{ $st['icon'] }}"></i></div>
                                            <div class="timeline-line"></div>
                                        @endif
                                        <div class="timeline-content">
                                            <h4 style="{{ $i <= $currentIndex && !$isCancelled ? 'color: var(--text-primary, #0f172a);' : 'color: var(--text-muted, #94a3b8);' }}">
                                                {{ ucfirst($st['key']) }}
                                            </h4>
                                            @if($i === $currentIndex && !$isCancelled)
                                                <p style="color: var(--primary, #3b82f6); font-weight: 600;">Current Phase</p>
                                            @elseif($i < $currentIndex)
                                                <p style="color: #16a34a; opacity: 0.8;">Completed</p>
                                            @else
                                                <p style="color: var(--text-muted, #94a3b8); opacity: 0.6;">Upcoming</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                @if($isCancelled)
                                    <div class="timeline-step">
                                        <div class="timeline-dot" style="background: #dc2626; color: white; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);"><i class="fa-solid fa-xmark"></i></div>
                                        <div class="timeline-content">
                                            <h4 style="color: #dc2626;">Cancelled</h4>
                                            <p style="color: #ef4444; font-weight: 600;">Order Termination</p>
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
                                    <div style="margin-top: 12px; padding: 12px; background: var(--bg-card-alt, #f8fafc); border-radius: 8px; font-size: 13px; color: var(--text-secondary, #64748b);">
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
                        <button type="submit" style="width: 100%; padding: 14px; background: var(--bg-card-alt, #fef2f2); color: var(--danger, #dc2626); border: 1px solid var(--border, #fecaca); border-radius: 12px; font-size: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='var(--bg-hover)'" onmouseout="this.style.background='var(--bg-card-alt)'">
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
