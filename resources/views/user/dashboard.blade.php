@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'overview'])

    <main class="dashboard-main">
        <div class="welcome-banner">
            <div class="banner-content">
                <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
                <p>Your latest account activity is shown below.</p>
            </div>
            <i class="fa-solid fa-tags banner-decoration"></i>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['total_orders'] }}</h3>
                    <p>Total Orders</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon pink">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['wishlist_items'] }}</h3>
                    <p>Wishlist Items</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fa-solid fa-gem"></i>
                </div>
                <div class="stat-info">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <h3>{{ $stats['loyalty_points'] }}</h3>
                        <span style="font-size: 11px; background: #f3e8ff; color: #6b21a8; padding: 2px 8px; border-radius: 20px; font-weight: 700;">{{ $stats['rank'] }}</span>
                    </div>
                    <p>Loyalty Points</p>
                </div>
            </div>

            @if($stats['coupon_eligible'])
            <div class="stat-card" style="background: linear-gradient(135deg, #0d6efd, #4895ef); color: white;">
                <div class="stat-icon" style="background: rgba(255,255,255,0.2); color: white;">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div class="stat-info">
                    <h3 style="color: white; font-size: 14px;">{{ $stats['coupon_code'] }}</h3>
                    <p style="color: rgba(255,255,255,0.8);">Your Unique Coupon Active!</p>
                </div>
            </div>
            @endif

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ $stats['active_orders'] }}</h3>
                    <p>Active Orders</p>
                </div>
            </div>
        </div>

        <div class="recent-activity">
            <div class="section-header">
                <h2>Recent Order History</h2>
                <a href="{{ route('user.orders') }}" class="action-btn">View All</a>
            </div>

            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            @php
                                $statusClass = match(strtolower($order->status)) {
                                    'delivered', 'completed' => 'completed',
                                    'canceled', 'cancelled', 'failed' => 'canceled',
                                    'approved' => 'approved',
                                    'processing' => 'processing',
                                    'shipped' => 'shipped',
                                    default => 'pending',
                                };
                            @endphp
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center;">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
