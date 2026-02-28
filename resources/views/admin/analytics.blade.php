@extends('layouts.admin')

@section('title', 'Advanced Analytics')
@section('header_title', 'Insights & Intelligence')

@section('admin_content')

<!-- Analytics Header Stats -->
<div class="stats-grid">
    <div class="stat-card glass-stat">
        <div class="stat-icon bg-indigo">
            <i class="fa-solid fa-users-viewfinder"></i>
        </div>
        <div class="stat-content">
            <h3>New Registrations</h3>
            <p>{{ number_format($userRegistrations[11]['count']) }}</p>
            <span class="stat-detail">This Month</span>
        </div>
    </div>
    <div class="stat-card glass-stat">
        <div class="stat-icon bg-cyan">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        <div class="stat-content">
            <h3>Total Orders</h3>
            <p>{{ number_format(\App\Models\Order::count()) }}</p>
            <span class="stat-detail">All Time</span>
        </div>
    </div>
    <div class="stat-card glass-stat">
        <div class="stat-icon bg-rose">
            <i class="fa-solid fa-sack-dollar"></i>
        </div>
        <div class="stat-content">
            <h3>Avg. Ticket</h3>
            @php
                $avgOrder = \App\Models\Order::avg('total_amount') ?: 0;
            @endphp
            <p>${{ number_format($avgOrder, 2) }}</p>
            <span class="stat-detail">Per Transaction</span>
        </div>
    </div>
    <div class="stat-card glass-stat">
        <div class="stat-icon bg-emerald">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div class="stat-content">
            <h3>Conversion Rate</h3>
            <p>3.2%</p>
            <span class="stat-detail">Average</span>
        </div>
    </div>
</div>

<!-- Main Charts Section -->
<div class="analytics-main-grid">
    <!-- Left: User & Order Trends -->
    <div class="analytics-left">
        <!-- User Growth Chart -->
        <div class="premium-card chart-card-heavy">
            <div class="action-header-premium">
                <div class="header-info">
                    <h2>User Acquisition Strategy</h2>
                    <p>New member registrations trend over the last 12 months</p>
                </div>
                <div class="header-badge">Growth Focus</div>
            </div>
            <div class="chart-box">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Sales & Orders Daily -->
        <div class="premium-card chart-card-heavy">
            <div class="action-header-premium">
                <div class="header-info">
                    <h2>Order Velocity</h2>
                    <p>Daily order volume for the past 30 days</p>
                </div>
            </div>
            <div class="chart-box">
                <canvas id="dailyOrdersChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Right: Distribution & Rankings -->
    <div class="analytics-right">
        <!-- Category Distribution (Doughnut) -->
        <div class="premium-card chart-card-heavy">
            <div class="action-header-premium">
                <div class="header-info">
                    <h2>Inventory Mix</h2>
                    <p>Product distribution across categories</p>
                </div>
            </div>
            <div class="chart-box doughnut-center">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <!-- Top Products List -->
        <div class="premium-card">
            <div class="action-header-premium">
                <div class="header-info">
                    <h2>Elite Performers</h2>
                    <p>Highest performing products by order count</p>
                </div>
            </div>
            <div class="top-performers-list">
                @foreach($topProducts as $product)
                <div class="performer-item">
                    <div class="performer-rank">#{{ $loop->iteration }}</div>
                    <div class="performer-info">
                        <div class="performer-name">{{ $product->product_name }}</div>
                        <div class="performer-meta">{{ $product->total_orders }} Orders • {{ $product->total_quantity }} Sold</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(255, 255, 255, 0.5);
    }

    .analytics-main-grid {
        display: grid;
        grid-template-columns: 2fr 1.2fr;
        gap: 30px;
        margin-top: 30px;
    }

    .analytics-left, .analytics-right {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    /* Glassmorphism Stats */
    .glass-stat {
        background: var(--glass-bg) !important;
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border) !important;
        position: relative;
        overflow: hidden;
    }

    .glass-stat::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, transparent 70%);
        opacity: 0;
        transition: 0.5s;
    }

    .glass-stat:hover::before {
        opacity: 1;
        transform: translate(20%, 20%);
    }

    .stat-detail {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        color: #94a3b8;
        letter-spacing: 0.5px;
    }

    /* Premium Chart Cards */
    .chart-card-heavy {
        padding: 0 !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.05) !important;
    }

    .action-header-premium {
        padding: 25px 30px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(to right, #ffffff, #fafafa);
    }

    .header-info h2 { font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 4px; }
    .header-info p { font-size: 13px; color: #64748b; }

    .header-badge {
        background: #eff6ff;
        color: #3b82f6;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .chart-box {
        padding: 30px;
        height: 380px;
        position: relative;
    }

    .doughnut-center {
        height: 420px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Top Performers List */
    .top-performers-list {
        display: flex;
        flex-direction: column;
    }

    .performer-item {
        padding: 20px 30px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 20px;
        transition: 0.3s;
    }

    .performer-item:last-child { border-bottom: none; }
    .performer-item:hover { background: #f8fafc; transform: translateX(5px); }

    .performer-rank {
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        color: #475569;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
    }

    .performer-name { font-weight: 700; color: #1e293b; font-size: 14px; margin-bottom: 2px; }
    .performer-meta { font-size: 12px; color: #64748b; font-weight: 500; }

    /* Stat Icon Colors */
    .bg-indigo { background: #e0e7ff; color: #4338ca; }
    .bg-cyan { background: #cffafe; color: #0891b2; }
    .bg-rose { background: #ffe4e6; color: #e11d48; }
    .bg-emerald { background: #d1fae5; color: #059669; }

    @media (max-width: 1200px) {
        .analytics-main-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // GSAP Entrance
    gsap.from(".stat-card", {
        duration: 0.8,
        y: 40,
        opacity: 0,
        stagger: 0.1,
        ease: "power3.out",
        clearProps: "all"
    });

    gsap.from(".premium-card", {
        duration: 1,
        y: 60,
        opacity: 0,
        stagger: 0.15,
        ease: "expo.out",
        delay: 0.2,
        clearProps: "all"
    });

    // 1. User Growth Chart (Area)
    const userGrCtx = document.getElementById('userGrowthChart').getContext('2d');
    const uLabel = @json(collect($userRegistrations)->pluck('label'));
    const uData = @json(collect($userRegistrations)->pluck('count'));

    const uGrad = userGrCtx.createLinearGradient(0, 0, 0, 400);
    uGrad.addColorStop(0, 'rgba(67, 56, 202, 0.3)');
    uGrad.addColorStop(1, 'rgba(67, 56, 202, 0)');

    new Chart(userGrCtx, {
        type: 'line',
        data: {
            labels: uLabel,
            datasets: [{
                label: 'New Users',
                data: uData,
                borderColor: '#4338ca',
                borderWidth: 4,
                tension: 0.5,
                fill: true,
                backgroundColor: uGrad,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBorderWidth: 3,
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#4338ca'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            animations: {
                y: { duration: 2000, easing: 'easeOutQuart', from: 0 }
            },
            scales: {
                y: { grid: { borderDash: [5, 5], color: '#f1f5f9' }, ticks: { font: { weight: 'bold' }, color: '#94a3b8' } },
                x: { grid: { display: false }, ticks: { font: { weight: 'bold' }, color: '#94a3b8' } }
            }
        }
    });

    // 2. Daily Orders Chart (Bar)
    const orderCtx = document.getElementById('dailyOrdersChart').getContext('2d');
    const oLabel = @json(collect($dailyOrders)->pluck('label'));
    const oData = @json(collect($dailyOrders)->pluck('count'));

    new Chart(orderCtx, {
        type: 'bar',
        data: {
            labels: oLabel,
            datasets: [{
                label: 'Orders',
                data: oData,
                backgroundColor: '#0ea5e9',
                borderRadius: 8,
                barThickness: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            animations: {
                duration: 2000,
                easing: 'easeOutElastic'
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                x: { grid: { display: false } }
            }
        }
    });

    // 3. Category Distribution (Doughnut)
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    const cLabel = @json(collect($categoryData)->pluck('name'));
    const cData = @json(collect($categoryData)->pluck('count'));

    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: cLabel,
            datasets: [{
                data: cData,
                backgroundColor: ['#4f46e5', '#0ea5e9', '#ec4899', '#f59e0b', '#10b981', '#6366f1'],
                borderWidth: 0,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 25, font: { weight: 'bold', family: 'Outfit' } } }
            },
            animations: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
});
</script>
@endsection
