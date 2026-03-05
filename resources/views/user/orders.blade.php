@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-orders.css') }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'orders'])

    <main class="dashboard-main">
        <div class="recent-activity">
            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        timer: 3000,
                        showConfirmButton: false
                    });
                </script>
            @endif
            @if(session('error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "{{ session('error') }}",
                    });
                </script>
            @endif

            <div class="section-header">
                <h2>My Orders</h2>
                <span class="action-btn">{{ $orders->total() }} total</span>
            </div>

            @forelse($orders as $order)
                @php
                    $statusClass = match(strtolower($order->status)) {
                        'delivered', 'completed' => 'delivered',
                        'canceled', 'cancelled', 'failed' => 'cancelled',
                        'approved' => 'approved',
                        'processing' => 'processing',
                        'shipped' => 'shipped',
                        default => 'pending',
                    };
                    $statusIcons = [
                        'pending' => '⏳', 'approved' => '✅', 'processing' => '⚙️',
                        'shipped' => '🚚', 'delivered' => '📦', 'cancelled' => '❌',
                    ];
                @endphp
                <div class="order-card">
                    <div class="order-card-header">
                        <div class="order-id-badge">
                            <span class="hash">#{{ $order->id }}</span>
                            <span class="status-pill status-{{ $statusClass }}">
                                {{ $statusIcons[$statusClass] ?? '' }} {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="order-meta">
                            <div class="order-meta-item">
                                <i class="fa-solid fa-calendar"></i>
                                {{ $order->created_at->format('M d, Y') }}
                            </div>
                            <div class="order-meta-item">
                                <i class="fa-solid fa-box"></i>
                                {{ $order->items_count }} {{ $order->items_count == 1 ? 'item' : 'items' }}
                            </div>
                        </div>
                    </div>

                    <div class="order-items-preview">
                        @foreach($order->items->take(3) as $item)
                            <div class="order-item-row">
                                @if($item->product && $item->product->image)
                                    <img src="{{ display_image($item->product->image) }}" class="order-item-img" alt="{{ $item->product->name ?? 'Product' }}">
                                @else
                                    <div class="order-item-placeholder">
                                        <i class="fa-solid fa-image"></i>
                                    </div>
                                @endif
                                <div class="order-item-info">
                                    <div class="order-item-name">{{ $item->product->name ?? 'Deleted Product' }}</div>
                                    @if($item->attributes && count(array_filter($item->attributes)) > 0)
                                        <div class="order-item-attrs">
                                            @foreach(array_filter($item->attributes) as $key => $val)
                                                {{ ucfirst($key) }}: {{ $val }}{{ !$loop->last ? ' · ' : '' }}
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="order-item-qty">Qty: {{ $item->quantity }}</div>
                                </div>
                                <div class="order-item-price">
                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                        @if($order->items->count() > 3)
                            <div class="more-items-note">
                                +{{ $order->items->count() - 3 }} more items
                            </div>
                        @endif
                    </div>

                    <div class="order-card-footer">
                        <div class="order-total">
                            Total: <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        <div class="order-actions-group">
                            <a href="{{ route('user.orders.show', $order->id) }}" class="btn-detail">
                                <i class="fa-solid fa-eye"></i> View Details
                            </a>
                            @if(in_array($order->status, ['pending', 'cancelled']))
                                <form id="delete-form-{{ $order->id }}" method="POST" action="{{ route('user.orders.delete', $order->id) }}" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <button type="button" class="btn-delete-order" onclick="confirmDelete({{ $order->id }})">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-orders">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <h3>No Orders Yet</h3>
                    <p>Your order history will appear here once you place your first order.</p>
                    <a href="{{ route('products.index') }}" style="display: inline-block; margin-top: 16px; padding: 10px 24px; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border-radius: 10px; text-decoration: none; font-weight: 600;">
                        <i class="fa-solid fa-shopping-cart"></i> Start Shopping
                    </a>
                </div>
            @endforelse

            @if($orders->hasPages())
            <div style="margin-top: 20px;">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </main>
</div>



<script>
function confirmDelete(orderId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + orderId).submit();
        }
    })
}
</script>
@endsection
