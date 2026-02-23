@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'overview'])

    <main class="dashboard-main">
        <!-- Dashboard Cover -->
        @if(auth()->user()->cover_image)
        <div class="dashboard-cover">
            <img src="{{ asset('storage/' . auth()->user()->cover_image) }}" alt="Cover">
        </div>
        @endif

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! 👋</h1>
                <p>Monitor your orders, managed your wishlist and account settings here.</p>
            </div>
            <div class="welcome-badge">
                <div style="font-size: 12px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; margin-bottom: 4px;">Account Status</div>
                <div style="font-size: 20px; font-weight: 800; color: var(--primary);">{{ $stats['rank'] }} Member</div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e0f2fe; color: #0ea5e9;">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_orders'] }}</h3>
                    <p>Total Orders</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['wishlist_items'] }}</h3>
                    <p>Wishlist</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['loyalty_points']) }}</h3>
                    <p>Points</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #f59e0b;">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['active_orders'] }}</h3>
                    <p>Active Orders</p>
                </div>
            </div>
        </div>

        <!-- Coupon Banner -->
        @if($stats['coupon_eligible'] && $stats['coupon_code'])
        <div class="coupon-banner">
            <div>
                <div style="font-size: 14px; color: #94a3b8; margin-bottom: 8px; font-weight: 600;">ACTIVE REWARD AVAILABLE</div>
                <h2 style="font-size: 24px; font-weight: 800; margin: 0;">10% Discount Coupon!</h2>
                <p style="margin: 8px 0 0; opacity: 0.8;">Use this code on your next checkout for instant savings.</p>
            </div>
            <div class="coupon-code-box">
                <span style="color: #fbbf24; font-weight: 900; font-size: 22px; letter-spacing: 4px;">{{ $stats['coupon_code'] }}</span>
            </div>
        </div>
        @endif

        <!-- Recent Activity -->
        <div class="recent-activity">
            <div class="section-header">
                <h2>Recent Orders</h2>
                <a href="{{ route('user.orders') }}" style="color: var(--primary); font-weight: 700; text-decoration: none; font-size: 14px;">View Full History →</a>
            </div>
            
            @forelse($recentOrders as $order)
                @php
                    $statusStyles = [
                        'pending'    => ['bg' => '#fef3c7', 'color' => '#92400e'],
                        'approved'   => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
                        'processing' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                        'shipped'    => ['bg' => '#dbeafe', 'color' => '#1e40af'],
                        'delivered'  => ['bg' => '#dcfce7', 'color' => '#166534'],
                        'cancelled'  => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                    ];
                    $st = $statusStyles[$order->status] ?? $statusStyles['pending'];
                @endphp
                <div class="order-item-card" onclick="window.location='{{ route('user.orders.show', $order->id) }}'">
                    <div class="order-image-stack">
                        @foreach($order->items->take(3) as $idx => $item)
                            @if($item->product && $item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="order-img" style="{{ $idx > 0 ? 'margin-left: -15px;' : '' }} z-index: {{ 10 - $idx }};">
                            @endif
                        @endforeach
                    </div>
                    
                    <div style="flex: 1;">
                        <h4 style="margin: 0 0 4px; font-size: 15px; font-weight: 700;">Order ID: #{{ $order->id }}</h4>
                        <p style="margin: 0; color: var(--text-muted); font-size: 12px; font-weight: 600;">
                            {{ $order->created_at->format('M d, Y') }} • {{ $order->items_count ?? $order->items->count() }} items
                        </p>
                    </div>
                    
                    <div style="text-align: right;">
                        <div style="font-weight: 800; font-size: 18px; color: var(--text-main); margin-bottom: 6px;">${{ number_format($order->total_amount, 2) }}</div>
                        <span class="status-check" style="background: {{ $st['bg'] }}; color: {{ $st['color'] }};">
                            {{ $order->status }}
                        </span>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 60px 0; color: var(--text-muted);">
                    <i class="fa-solid fa-bag-shopping" style="font-size: 48px; opacity: 0.2; margin-bottom: 16px;"></i>
                    <p style="font-weight: 600;">No orders found in your history.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection
