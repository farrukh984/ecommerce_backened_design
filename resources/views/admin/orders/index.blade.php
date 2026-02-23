@extends('layouts.admin')

@section('title', 'Orders Management')
@section('header_title', 'Orders Management')

@section('admin_content')

<!-- Order Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <h3>Total Orders</h3>
            <p>{{ $stats['total'] }}</p>
        </div>
        <div class="stat-icon bg-blue">
            <i class="fa-solid fa-bag-shopping"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h3>Pending</h3>
            <p>{{ $stats['pending'] }}</p>
        </div>
        <div class="stat-icon bg-orange">
            <i class="fa-solid fa-clock"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h3>Processing</h3>
            <p>{{ $stats['processing'] }}</p>
        </div>
        <div class="stat-icon bg-purple">
            <i class="fa-solid fa-gears"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h3>Delivered</h3>
            <p>{{ $stats['delivered'] }}</p>
        </div>
        <div class="stat-icon bg-green">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="premium-card">
    <div class="action-header">
        <div class="header-title">
            <h2>All Orders</h2>
            <p>Manage and track all customer orders</p>
        </div>
        <div style="display: flex; gap: 10px; align-items: center;">
            <form method="GET" action="{{ route('admin.orders.index') }}" style="display: flex; gap: 10px; align-items: center;">
                <input type="text" name="search" class="form-control" placeholder="Search by ID or name..."
                       value="{{ request('search') }}" style="max-width: 200px; padding: 8px 14px; font-size: 13px;">
                <select name="status" class="form-control" onchange="this.form.submit()" style="max-width: 160px; padding: 8px 14px; font-size: 13px;">
                    <option value="all">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn-primary" style="padding: 8px 16px;">
                    <i class="fa-solid fa-search"></i>
                </button>
            </form>
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
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Email</th>
                    <th>Items</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
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
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($order->user && $order->user->profile_image)
                                    <img src="{{ asset('storage/' . $order->user->profile_image) }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;">
                                @else
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, var(--admin-primary-light, #eff6ff), #dbeafe); color: var(--admin-primary, #2563eb); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; border: 2px solid #e2e8f0;">
                                        {{ strtoupper(substr($order->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <span style="font-weight: 600;">{{ $order->name }}</span>
                                    @if($order->user)
                                        <div style="font-size: 10px; color: #94a3b8;">ID: {{ $order->user->id }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="color: var(--admin-text-sub);">{{ $order->email }}</td>
                        <td>{{ $order->items_count }}</td>
                        <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                        <td>
                            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    style="padding: 6px 12px; border: 1px solid {{ $sc['color'] }}30; background: {{ $sc['bg'] }}; color: {{ $sc['color'] }}; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; outline: none; appearance: none; -webkit-appearance: none; -moz-appearance: none; text-align: center; min-width: 110px;">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="approved" {{ $order->status === 'approved' ? 'selected' : '' }}>✅ Approved</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>📦 Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td style="color: var(--admin-text-sub);">{{ $order->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline" style="padding: 6px 12px; font-size: 12px; text-decoration: none;">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--admin-text-sub);">
                            <i class="fa-solid fa-bag-shopping" style="font-size: 40px; margin-bottom: 12px; display: block; opacity: 0.3;"></i>
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div style="padding: 16px 24px; border-top: 1px solid var(--admin-border);">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection
