@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard Overview')

@section('admin_content')

<!-- Main Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon bg-green">
            <i class="fa-solid fa-chart-line"></i>
        </div>
        <div class="stat-content">
            <h3>Total Sales</h3>
            <p>${{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-blue">
            <i class="fa-solid fa-bag-shopping"></i>
        </div>
        <div class="stat-content">
            <h3>Total Orders</h3>
            <p>{{ number_format($stats['total_orders']) }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-purple">
            <i class="fa-solid fa-box"></i>
        </div>
        <div class="stat-content">
            <h3>Total Products</h3>
            <p>{{ number_format($stats['total_products']) }}</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-orange">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stat-content">
            <h3>Active Users</h3>
            <p>{{ number_format($stats['total_users']) }}</p>
        </div>
    </div>
</div>

<div class="dashboard-main-grid">
    <!-- Left Column: Recent Orders & Stock Alerts -->
    <div class="dashboard-left">
        
        <!-- Stock Alerts if any -->
        @if($outOfStockProducts->count() > 0 || $lowStockProducts->count() > 0)
        <div class="premium-card" style="border-left: 5px solid #ef4444;">
            <div class="action-header alert-header">
                <div class="header-title">
                    <h2 style="color: #ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Critical Inventory</h2>
                    <p>Action required for these items</p>
                </div>
            </div>
            <div style="padding: 24px;">
                @if($outOfStockProducts->count() > 0)
                <div style="margin-bottom: 24px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Out of Stock</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                        @foreach($outOfStockProducts->take(6) as $product)
                        <div style="background: #f8fafc; border: 1px solid #fee2e2; padding: 12px; border-radius: 14px; display: flex; align-items: center; gap: 12px;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                            @endif
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 700; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</div>
                                <div style="color: #dc2626; font-size: 11px; font-weight: 800;">REPLENISH NOW</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($lowStockProducts->count() > 0)
                <div>
                    <h4 style="font-size: 13px; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px;">Low Stock Warning</h4>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 12px;">
                        @foreach($lowStockProducts->take(6) as $product)
                        <div style="background: #f8fafc; border: 1px solid #fef3c7; padding: 12px; border-radius: 14px; display: flex; align-items: center; gap: 12px;">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                            @endif
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 700; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</div>
                                <div style="color: #d97706; font-size: 11px; font-weight: 800;">{{ $product->stock_quantity }} items left</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Recent Orders Table -->
        <div class="premium-card">
            <div class="action-header">
                <div class="header-title">
                    <h2>Recent Transactions</h2>
                    <p>Overview of the latest 10 customer orders</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn-outline">View All Transactions</a>
            </div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                        @php
                            $st = match($order->status) {
                                'delivered' => ['bg' => '#dcfce7', 'color' => '#166534'],
                                'pending' => ['bg' => '#fef3c7', 'color' => '#92400e'],
                                'processing' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                                'shipped' => ['bg' => '#dbeafe', 'color' => '#1e40af'],
                                'cancelled' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                                default => ['bg' => '#f1f5f9', 'color' => '#475569'],
                            };
                        @endphp
                        <tr>
                            <td style="font-weight: 800; color: var(--admin-primary);">#{{ $order->id }}</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    @if($order->user && $order->user->profile_image)
                                        <img src="{{ asset('storage/' . $order->user->profile_image) }}" style="width: 32px; height: 32px; border-radius: 10px; object-fit: cover;">
                                    @else
                                        <div style="width: 32px; height: 32px; border-radius: 10px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; color: #64748b;">
                                            {{ strtoupper(substr($order->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span style="font-weight: 600;">{{ $order->name }}</span>
                                </div>
                            </td>
                            <td style="font-weight: 800;">${{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span style="background: {{ $st['bg'] }}; color: {{ $st['color'] }}; padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td style="color: var(--admin-text-sub); font-size: 12px; font-weight: 500;">{{ $order->created_at->format('M d, H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" style="text-align: center; padding: 40px; color: var(--admin-text-sub);">No orders recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Quick Stats & Actions -->
    <div class="dashboard-right">
        
        <!-- Quick Actions -->
        <div class="premium-card">
            <div class="action-header">
                <div class="header-title">
                    <h2>Quick Actions</h2>
                    <p>Common management tasks</p>
                </div>
            </div>
            <div style="padding: 32px; display: flex; flex-direction: column; gap: 12px;">
                <a href="{{ route('admin.products.create') }}" class="btn-primary" style="justify-content: center; width: 100%;">
                    <i class="fa-solid fa-plus-circle"></i> Add New Product
                </a>
                <a href="{{ route('admin.categories.create') }}" class="btn-outline" style="text-align: center; width: 100%;">
                    <i class="fa-solid fa-folder-plus"></i> Create Category
                </a>
                <a href="{{ route('admin.messages.index') }}" class="btn-outline" style="text-align: center; width: 100%;">
                    <i class="fa-solid fa-envelope"></i> Check Messages
                    @if($stats['unread_messages'] > 0)
                    <span style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 6px; font-size: 10px; margin-left: 8px;">{{ $stats['unread_messages'] }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Inventory Status -->
        <div class="premium-card">
            <div class="action-header">
                <div class="header-title">
                    <h2>Inventory Summary</h2>
                    <p>Status of your catalog visibility</p>
                </div>
            </div>
            <div style="padding: 32px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div style="text-align: center; flex: 1;">
                        <div style="font-size: 32px; font-weight: 900; color: #10b981;">{{ $stats['active_products'] }}</div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--admin-text-sub); text-transform: uppercase;">Visible</div>
                    </div>
                    <div style="width: 1px; background: var(--admin-border);"></div>
                    <div style="text-align: center; flex: 1;">
                        <div style="font-size: 32px; font-weight: 900; color: #ef4444;">{{ $stats['inactive_products'] }}</div>
                        <div style="font-size: 12px; font-weight: 700; color: var(--admin-text-sub); text-transform: uppercase;">Hidden</div>
                    </div>
                </div>
                <div style="width: 100%; height: 8px; background: #f1f5f9; border-radius: 4px; overflow: hidden; display: flex; margin-bottom: 24px;">
                    @php
                        $total = $stats['active_products'] + $stats['inactive_products'];
                        $activePerc = $total > 0 ? ($stats['active_products'] / $total) * 100 : 0;
                    @endphp
                    <div style="width: {{ $activePerc }}%; height: 100%; background: #10b981;"></div>
                    <div style="flex: 1; height: 100%; background: #ef4444;"></div>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn-outline" style="width: 100%; text-align: center; display: block;">Manage Catalog</a>
            </div>
        </div>

        <!-- Support Overview -->
        <div class="premium-card">
            <div class="action-header">
                <div class="header-title">
                    <h2>Messages & Support</h2>
                    <p>Unread inquiries breakdown</p>
                </div>
            </div>
            <div style="padding: 32px; text-align: center;">
                <div style="width: 80px; height: 80px; background: #e0f2fe; color: #0ea5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 16px;">
                    <i class="fa-solid fa-comment-dots"></i>
                </div>
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ $stats['unread_messages'] }}</h3>
                <p style="color: var(--admin-text-sub); font-size: 14px; margin-bottom: 24px;">Unread messages awaiting response</p>
                <a href="{{ route('admin.messages.index') }}" class="btn-primary" style="width: 100%; justify-content: center;">Open Inbox</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .dashboard-main-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 32px;
    }
    
    .dashboard-left, .dashboard-right {
        display: flex;
        flex-direction: column;
        gap: 24px;
        min-width: 0;
    }

    /* Premium Horizontal Scroll for content boxes */
    .table-responsive, .premium-card {
        overflow-x: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--admin-border) transparent;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive::-webkit-scrollbar,
    .premium-card::-webkit-scrollbar {
        height: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb,
    .premium-card::-webkit-scrollbar-thumb {
        background: var(--admin-border);
        border-radius: 10px;
    }

    .stat-card {
        flex-direction: row-reverse; /* Icon on the left-ish for better mobile flow */
        justify-content: flex-end;
        gap: 20px;
    }

    @media (max-width: 1100px) {
        .dashboard-main-grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .dashboard-main-grid {
            gap: 20px;
            overflow: visible;
        }
        .header-title h2 {
            font-size: 16px !important;
        }
        .stat-card {
            padding: 20px;
        }
        .stat-content p {
            font-size: 22px;
        }
        .action-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 12px;
            padding: 20px !important;
        }
    }
</style>
@endsection
