@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'orders'])

    <main class="dashboard-main">
        <div class="recent-activity">
            @if(session('success'))
                <div class="profile-alert success" style="margin-bottom: 18px;">{{ session('success') }}</div>
            @endif

            <div class="section-header">
                <h2>All Orders</h2>
                <span class="action-btn">{{ $orders->total() }} total</span>
            </div>

            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
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
                                <td><span class="status-badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span></td>
                                <td>{{ $order->items_count }}</td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 20px;">
                {{ $orders->links() }}
            </div>
        </div>
    </main>
</div>
@endsection
