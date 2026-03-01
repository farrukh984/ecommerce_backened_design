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

<!-- Analytics Chart -->
<div class="stats-grid" style="grid-template-columns: 1fr; margin-bottom: 24px;">
    <div class="premium-card chart-card">
        <div class="action-header" style="background: linear-gradient(to right, #ffffff, #f8fafc);">
            <div class="header-title">
                <h2 style="color: #1e293b; font-weight: 800;">Revenue Intelligence</h2>
                <p>Real-time analytics & sales performance tracking</p>
            </div>
            <div class="chart-stats-premium" style="display: flex; gap: 24px;">
                <div class="stat-minimal">
                    <span class="label">Total Revenue</span>
                    <span class="value">${{ number_format(collect($monthlySales)->sum('total'), 2) }}</span>
                </div>
                <div class="stat-minimal">
                    <span class="label">Avg. Monthly</span>
                    <span class="value">${{ number_format(collect($monthlySales)->avg('total'), 2) }}</span>
                </div>
            </div>
        </div>
        <div class="chart-wrapper-inner" style="height: 400px; padding: 10px 25px 25px 25px; position: relative;">
            <div class="chart-overlay-info">
                @php
                    $lastMonth = end($monthlySales);
                    $prevMonth = prev($monthlySales) ?: $lastMonth;
                    $growth = $prevMonth['total'] > 0 ? (($lastMonth['total'] - $prevMonth['total']) / $prevMonth['total']) * 100 : 0;
                @endphp
                <div class="growth-badge {{ $growth >= 0 ? 'up' : 'down' }}">
                    <i class="fa-solid fa-arrow-{{ $growth >= 0 ? 'up' : 'down' }}"></i> {{ abs(round($growth, 1)) }}%
                </div>
            </div>
            <canvas id="salesChart"></canvas>
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
                                <img src="{{ display_image($product->image) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
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
                                <img src="{{ display_image($product->image) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
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
                                        <img src="{{ display_image($order->user->profile_image) }}" style="width: 32px; height: 32px; border-radius: 10px; object-fit: cover;">
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

        <!-- Recent Conversations -->
        <div class="premium-card">
            <div class="action-header">
                <div class="header-title">
                    <h2>Recent Chats</h2>
                    <p>Latest customer inquiries</p>
                </div>
                <div class="unread-count">{{ $stats['unread_messages'] }} New</div>
            </div>
            <div class="dashboard-chat-list">
                @php
                    $recentConversations = \App\Models\Conversation::where('sender_id', auth()->id())
                        ->orWhere('receiver_id', auth()->id())
                        ->with(['sender', 'receiver', 'messages'])
                        ->latest('last_message_at')
                        ->take(3)
                        ->get();
                @endphp

                @forelse($recentConversations as $conv)
                    @php
                        $otherUser = $conv->sender_id === auth()->id() ? $conv->receiver : $conv->sender;
                        $lastMsg = $conv->messages->sortByDesc('created_at')->first();
                    @endphp
                    <div class="dash-chat-item" onclick="openQuickChat('{{ $conv->id }}', '{{ $otherUser->name }}', '{{ $otherUser->profile_image ? display_image($otherUser->profile_image) : '' }}', '{{ strtoupper(substr($otherUser->name, 0, 1)) }}')">
                        <div class="dash-chat-avatar">
                            @if($otherUser->profile_image)
                                <img src="{{ display_image($otherUser->profile_image) }}">
                            @else
                                <div class="avatar-placeholder">{{ strtoupper(substr($otherUser->name, 0, 1)) }}</div>
                            @endif
                            <span class="status-dot {{ $otherUser->isOnline() ? 'online' : '' }}"></span>
                        </div>
                        <div class="dash-chat-info">
                            <div class="chat-name-time">
                                <span class="chat-name">{{ $otherUser->name }}</span>
                                <span class="chat-time">{{ $lastMsg ? $lastMsg->created_at->diffForHumans(null, true, true) : '' }}</span>
                            </div>
                            <div class="chat-preview">{{ $lastMsg ? Str::limit($lastMsg->message, 35) : 'No messages' }}</div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 20px; text-align: center; color: #94a3b8;">No recent chats</div>
                @endforelse
            </div>
            <div style="padding: 20px; border-top: 1px solid #f1f5f9;">
                <a href="{{ route('admin.messages.index') }}" class="btn-outline" style="width: 100%; justify-content: center;">View All Messages</a>
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

    /* Chart & Chat Styles */
    .chart-card {
        padding: 0 !important;
        overflow: hidden;
        border: 1px solid var(--admin-border);
        background: #fff;
    }

    .chart-wrapper-inner {
        position: relative;
    }

    .chart-overlay-info {
        position: absolute;
        top: 20px;
        right: 30px;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .growth-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .growth-badge.up {
        background: #dcfce7;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .growth-badge.down {
        background: #fee2e2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .stat-minimal {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .stat-minimal .label {
        font-size: 10px;
        text-transform: uppercase;
        font-weight: 800;
        color: #94a3b8;
        letter-spacing: 1px;
    }

    .stat-minimal .value {
        font-size: 18px;
        font-weight: 900;
        color: #1e293b;
        font-family: 'Outfit', sans-serif;
    }
    
    .dot { width: 10px; height: 10px; border-radius: 50%; }
    .bg-blue { background: #2563eb; box-shadow: 0 0 10px rgba(37, 99, 235, 0.4); }

    .unread-count {
        background: #ef4444;
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .dashboard-chat-list {
        display: flex;
        flex-direction: column;
    }

    .dash-chat-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px 20px;
        border-bottom: 1px solid #f8fafc;
        transition: 0.3s;
        cursor: pointer;
    }

    .dash-chat-item:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .dash-chat-avatar {
        position: relative;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        overflow: hidden;
    }

    .dash-chat-avatar img { width: 100%; height: 100%; object-fit: cover; }
    
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
    }

    .status-dot {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 10px;
        height: 10px;
        background: #94a3b8;
        border: 2px solid white;
        border-radius: 50%;
    }

    .status-dot.online { background: #10b981; box-shadow: 0 0 8px rgba(16, 185, 129, 0.5); }

    .dash-chat-info { flex: 1; min-width: 0; }
    
    .chat-name-time {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2px;
    }

    .chat-name { font-weight: 700; font-size: 14px; color: var(--admin-text-main); }
    .chat-time { font-size: 11px; color: var(--admin-text-sub); font-weight: 600; }
    .chat-preview { font-size: 12px; color: var(--admin-text-sub); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

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
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Entrance Animations (Run first for better UX and fail-safe)
        const statCards = document.querySelectorAll(".stat-card");
        const premiumCards = document.querySelectorAll(".premium-card");

        if (statCards.length > 0) {
            gsap.from(statCards, {
                duration: 0.8,
                y: 20,
                opacity: 0,
                stagger: 0.05,
                ease: "power2.out",
                clearProps: "all"
            });
        }

        if (premiumCards.length > 0) {
            gsap.from(premiumCards, {
                duration: 0.8,
                y: 30,
                opacity: 0,
                stagger: 0.1,
                ease: "power3.out",
                delay: 0.1, // Reduced delay
                clearProps: "all"
            });
        }

        // 2. Sales Chart Initialization
        try {
            const chartCanvas = document.getElementById('salesChart');
            if (chartCanvas) {
                const ctx = chartCanvas.getContext('2d');
                const months = @json(collect($monthlySales)->pluck('month'));
                const totals = @json(collect($monthlySales)->pluck('total'));

                // Premium Gradient
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
                gradient.addColorStop(0.5, 'rgba(79, 70, 229, 0.1)');
                gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: months,
                        datasets: [{
                            label: 'Revenue',
                            data: totals,
                            borderColor: '#4f46e5',
                            borderWidth: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4f46e5',
                            pointBorderWidth: 3,
                            pointRadius: 0, // Hidden by default
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: '#4f46e5',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 4,
                            tension: 0.45,
                            fill: true,
                            backgroundColor: gradient,
                            borderCapStyle: 'round',
                            borderJoinStyle: 'round'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                enabled: true,
                                backgroundColor: '#1e293b',
                                titleFont: { family: 'Outfit', size: 14, weight: '800' },
                                bodyFont: { family: 'Inter', size: 13, weight: '600' },
                                padding: 15,
                                cornerRadius: 15,
                                displayColors: false,
                                callbacks: {
                                    label: (context) => `Earnings: $${context.parsed.y.toLocaleString()}`
                                }
                            }
                        },
                        animations: {
                            y: {
                                easing: 'easeInOutElastic',
                                duration: 2000,
                                from: (ctx) => {
                                    if (ctx.type === 'data') {
                                        if (ctx.mode === 'default' && !ctx.dropped) {
                                            ctx.dropped = true;
                                            return 0;
                                        }
                                    }
                                }
                            },
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(226, 232, 240, 0.5)',
                                    drawBorder: false,
                                    borderDash: [5, 5]
                                },
                                ticks: {
                                    callback: (value) => '$' + value.toLocaleString(),
                                    font: { family: 'Outfit', size: 11, weight: '700' },
                                    color: '#94a3b8',
                                    padding: 10
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { 
                                    font: { family: 'Outfit', size: 12, weight: '700' }, 
                                    color: '#94a3b8',
                                    padding: 10
                                }
                            }
                        }
                    }
                });
            }
        } catch (e) {
            console.error("Chart initialization failed:", e);
        }
    });
</script>
@endsection
