@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<style>
    .order-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 20px;
    }
    .order-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        border-color: #e2e8f0;
    }
    .order-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px;
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 12px;
    }
    .order-id-badge {
        font-weight: 800;
        font-size: 16px;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .order-id-badge .hash {
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 13px;
    }
    .order-meta {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .order-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748b;
    }
    .order-meta-item i {
        color: #94a3b8;
    }
    .order-items-preview {
        padding: 16px 24px;
    }
    .order-item-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 12px 0;
        border-bottom: 1px solid #f8fafc;
    }
    .order-item-row:last-child {
        border-bottom: none;
    }
    .order-item-img {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #f1f5f9;
        flex-shrink: 0;
    }
    .order-item-placeholder {
        width: 56px;
        height: 56px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cbd5e1;
        flex-shrink: 0;
    }
    .order-item-info {
        flex: 1;
        min-width: 0;
    }
    .order-item-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .order-item-attrs {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 2px;
    }
    .order-item-price {
        font-weight: 700;
        color: #0f172a;
        font-size: 14px;
        text-align: right;
        white-space: nowrap;
    }
    .order-item-qty {
        font-size: 12px;
        color: #64748b;
    }
    .order-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        border-top: 1px solid #f1f5f9;
        background: #fcfcfc;
        flex-wrap: wrap;
        gap: 12px;
    }
    .order-total {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }
    .order-total span {
        color: #3b82f6;
    }
    .order-actions-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .btn-detail {
        padding: 8px 18px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border-radius: 10px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-detail:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }
    .btn-delete-order {
        padding: 8px 14px;
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-delete-order:hover {
        background: #fee2e2;
        border-color: #dc2626;
    }
    .status-pill {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-pending { background: #fff8eb; color: #a16207; }
    .status-approved { background: #e0f2fe; color: #0369a1; }
    .status-processing { background: #f3e8ff; color: #7e22ce; }
    .status-shipped { background: #e7f0ff; color: #0d6efd; }
    .status-delivered { background: #dcfce7; color: #166534; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }
    .empty-orders {
        text-align: center;
        padding: 60px 24px;
        color: #94a3b8;
    }
    .empty-orders i {
        font-size: 60px;
        margin-bottom: 16px;
        display: block;
        opacity: 0.3;
    }
    .empty-orders h3 {
        color: #475569;
        margin-bottom: 8px;
    }
</style>

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
                            <div style="text-align: center; padding: 8px 0; font-size: 12px; color: #94a3b8;">
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

<style>
    @keyframes slideDown {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

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
