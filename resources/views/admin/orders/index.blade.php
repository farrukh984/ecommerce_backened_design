@extends('layouts.admin')

@section('title', 'Orders Management')
@section('header_title', 'Orders Management')

@section('admin_content')

<!-- Order Stats - Animated with GSAP -->
<div class="stats-grid" id="statsGrid">
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

<!-- Orders Section -->
<div class="premium-card" id="ordersCard">
    <div class="action-header">
        <div class="header-title">
            <h2>Live Order Stream</h2>
            <p>Manage real-time customer activity and logistics</p>
        </div>
        <div class="header-actions">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="modern-search-form">
                <div class="search-input-wrapper">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" name="search" placeholder="Search ID, Name..." value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('admin.orders.index') }}" class="clear-search"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </div>
                <select name="status" class="modern-select" onchange="this.form.submit()">
                    <option value="all">All Channels</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn-primary-sm ripple">
                    <i class="fa-solid fa-filter"></i> Apply
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-premium success">
            <div class="alert-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div class="alert-message">{{ session('success') }}</div>
        </div>
    @endif

    <div class="table-responsive">
        <table class="premium-table">
            <thead>
                <tr>
                    <th><i class="fa-solid fa-hashtag"></i> Order ID</th>
                    <th><i class="fa-solid fa-user"></i> Customer Details</th>
                    <th><i class="fa-solid fa-box"></i> Items</th>
                    <th><i class="fa-solid fa-dollar-sign"></i> Total Revenue</th>
                    <th><i class="fa-solid fa-sliders"></i> Progression</th>
                    <th><i class="fa-solid fa-calendar"></i> Placed At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $statusClass = 'status-' . $order->status;
                    @endphp
                    <tr class="order-row">
                        <td><span class="order-id-badge">#{{ $order->id }}</span></td>
                        <td>
                            <div class="customer-info">
                                @if($order->user && $order->user->profile_image)
                                    <img src="{{ asset('storage/' . $order->user->profile_image) }}" class="customer-avatar">
                                @else
                                    <div class="customer-avatar-fallback">
                                        {{ strtoupper(substr($order->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="customer-details">
                                    <span class="customer-name">{{ $order->name }}</span>
                                    <span class="customer-email">{{ $order->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="items-count-badge">{{ $order->items_count }} Items</span>
                        </td>
                        <td>
                            <span class="price-premium">${{ number_format($order->total_amount, 2) }}</span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}" class="status-form">
                                @csrf
                                @method('PATCH')
                                <div class="custom-select-wrapper {{ $statusClass }}">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $order->status === 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                            </form>
                        </td>
                        <td>
                            <div class="date-display">
                                <span class="date-main">{{ $order->created_at->format('M d, Y') }}</span>
                                <span class="date-sub">{{ $order->created_at->format('h:i A') }}</span>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-icon-link ripple" title="View Order">
                                <i class="fa-solid fa-arrow-right-long"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state-card">
                                <div class="empty-icon"><i class="fa-solid fa-ghost"></i></div>
                                <h3>No matching orders</h3>
                                <p>We couldn't find any orders matching your current filters.</p>
                                <a href="{{ route('admin.orders.index') }}" class="btn-primary-sm">Clear Filters</a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="pagination-footer">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif
</div>

@endsection

@section('styles')
<style>
    /* Modern Search Form */
    .modern-search-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        padding: 0 16px;
        transition: all 0.3s;
        width: 260px;
    }

    .search-input-wrapper:focus-within {
        border-color: var(--admin-primary);
        background: white;
        box-shadow: 0 0 0 4px var(--admin-primary-glow);
    }

    .search-input-wrapper i {
        color: var(--admin-text-sub);
        font-size: 14px;
    }

    .search-input-wrapper input {
        border: none;
        background: transparent;
        padding: 12px 10px;
        font-size: 13px;
        font-weight: 600;
        outline: none;
        width: 100%;
        color: var(--admin-text);
    }

    .clear-search {
        background: #e2e8f0;
        color: var(--admin-text-sub);
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .clear-search:hover { background: #cbd5e1; color: var(--admin-text); }

    .modern-select {
        background: #f8fafc;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        color: var(--admin-text);
        outline: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .modern-select:focus { border-color: var(--admin-primary); box-shadow: 0 0 0 4px var(--admin-primary-glow); }

    .btn-primary-sm {
        background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        border: none;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 10px var(--admin-primary-glow);
    }

    /* Table Enhancements */
    .premium-table th {
        font-size: 11px;
        letter-spacing: 0.8px;
        padding: 22px 24px;
    }

    .premium-table th i { margin-right: 6px; opacity: 0.6; }

    .order-row {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .order-row:hover { background: #f8fafc; }

    .order-id-badge {
        background: #f1f5f9;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        color: var(--admin-primary);
        font-size: 13px;
    }

    .customer-info { display: flex; align-items: center; gap: 14px; }

    .customer-avatar { width: 42px; height: 42px; border-radius: 14px; object-fit: cover; border: 2px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

    .customer-avatar-fallback {
        width: 42px; height: 42px; border-radius: 14px; background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: var(--admin-primary); display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px; border: 2px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .customer-details { display: flex; flex-direction: column; gap: 2px; }

    .customer-name { font-weight: 700; color: var(--admin-text); font-size: 14px; }

    .customer-email { color: var(--admin-text-sub); font-size: 12px; }

    .items-count-badge {
        background: #eff6ff;
        color: #3b82f6;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #dbeafe;
    }

    .price-premium { font-weight: 800; font-size: 15px; font-family: 'Outfit', sans-serif; color: var(--admin-text); }

    /* Custom Status Selector UI */
    .custom-select-wrapper {
        position: relative;
        display: inline-flex;
        align-items: center;
        width: 130px;
    }

    .custom-select-wrapper select {
        width: 100%;
        padding: 8px 14px;
        padding-right: 30px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: 1px solid transparent;
        appearance: none;
        cursor: pointer;
        outline: none;
        transition: all 0.2s;
    }

    .custom-select-wrapper i {
        position: absolute;
        right: 12px;
        font-size: 10px;
        pointer-events: none;
        opacity: 0.6;
    }

    .status-pending select { background: #fff7ed; color: #c2410c; border-color: #ffedd5; }
    .status-approved select { background: #ecfdf5; color: #047857; border-color: #d1fae5; }
    .status-processing select { background: #f5f3ff; color: #6d28d9; border-color: #ede9fe; }
    .status-shipped select { background: #eff6ff; color: #1d4ed8; border-color: #dbeafe; }
    .status-delivered select { background: #f0fdf4; color: #15803d; border-color: #dcfce7; }
    .status-cancelled select { background: #fef2f2; color: #b91c1c; border-color: #fee2e2; }

    .date-display { display: flex; flex-direction: column; }
    .date-main { font-weight: 600; font-size: 13px; color: var(--admin-text); }
    .date-sub { font-size: 11px; color: var(--admin-text-sub); }

    .btn-icon-link {
        width: 36px; height: 36px; border-radius: 12px; background: #f8fafc; border: 1px solid var(--admin-border);
        color: var(--admin-text-sub); display: flex; align-items: center; justify-content: center;
        transition: all 0.3s; text-decoration: none;
    }

    .btn-icon-link:hover { background: var(--admin-primary); border-color: var(--admin-primary); color: white; transform: rotate(-5deg); }

    .pagination-footer { padding: 20px 32px; border-top: 1px solid var(--admin-border); }

    .empty-state-card { padding: 60px 40px; text-align: center; }
    .empty-icon { font-size: 50px; color: #e2e8f0; margin-bottom: 20px; }
    .empty-state-card h3 { font-family: 'Outfit', sans-serif; font-weight: 700; margin-bottom: 8px; }
    .empty-state-card p { color: var(--admin-text-sub); margin-bottom: 24px; }

    .alert-premium { display: flex; align-items: center; gap: 15px; padding: 16px 24px; border-radius: 16px; margin: 0 24px 24px 24px; }
    .alert-premium.success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .alert-premium.success .alert-icon { font-size: 20px; }

    /* Responsive Logic */
    @media (max-width: 1200px) {
        .header-actions { width: 100%; margin-top: 20px; }
        .modern-search-form { flex-wrap: wrap; }
        .search-input-wrapper { width: 100%; }
        .modern-select { flex: 1; }
        .btn-primary-sm { flex: 0.5; justify-content: center; }
    }

    @media (max-width: 991px) {
        .action-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initial clean state
        gsap.set('.stat-card, .order-row, #ordersCard', { opacity: 0, y: 20 });
        
        // Timeline for coordinated animation
        const tl = gsap.timeline({ defaults: { ease: "power3.out", duration: 0.8 }});
        
        tl.to('#statsGrid .stat-card', {
            opacity: 1,
            y: 0,
            stagger: 0.1
        })
        .to('#ordersCard', {
            opacity: 1,
            y: 0
        }, "-=0.4")
        .to('.order-row', {
            opacity: 1,
            y: 0,
            stagger: 0.05,
            clearProps: "all"
        }, "-=0.3");

        // Hover effects for scale
        const rows = document.querySelectorAll('.order-row');
        rows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                gsap.to(row, { backgroundColor: "#f8fafc", duration: 0.2 });
            });
            row.addEventListener('mouseleave', () => {
                gsap.to(row, { backgroundColor: "transparent", duration: 0.2 });
            });
        });
    });
</script>
@endsection
