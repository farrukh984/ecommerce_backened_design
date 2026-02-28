@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<!-- Include same sidebar layout but with enhanced styling -->
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<style>
    :root {
        --wishlist-primary: #6366f1;
        --wishlist-bg: #f8fafc;
        --wishlist-card: #ffffff;
        --wishlist-text: #1e293b;
        --wishlist-text-sub: #64748b;
        --wishlist-radius: 20px;
        --wishlist-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }

    .dashboard-container {
        padding: 40px;
        background: var(--wishlist-bg);
        min-height: 100vh;
        max-width: 100% !important; /* Allow full width */
    }

    .wishlist-hero {
        margin-bottom: 40px;
        background: linear-gradient(135deg, #4f46e5, #818cf8);
        padding: 50px 40px;
        border-radius: var(--wishlist-radius);
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .wishlist-hero h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 32px;
        font-weight: 800;
        margin: 0 0 5px;
    }

    .view-controls {
        display: flex;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 6px;
        border-radius: 14px;
        gap: 4px;
    }

    .view-btn {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        border: none;
        background: transparent;
        color: white;
        cursor: pointer;
        transition: 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .view-btn.active {
        background: white;
        color: var(--wishlist-primary);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Grid Layout */
    .wishlist-container-inner {
        width: 100%;
    }

    .wishlist-grid-modern {
        display: grid;
        grid-template-columns: 1fr; /* Default 1 col */
        gap: 24px;
        transition: all 0.4s ease;
    }

    /* Force 2 columns on small tablets/large phones */
    @media (min-width: 600px) {
        .wishlist-grid-modern:not(.list-view) {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Force 3 columns on standard desktops */
    @media (min-width: 1100px) {
        .wishlist-grid-modern:not(.list-view) {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* 4 columns on large screens */
    @media (min-width: 1440px) {
        .wishlist-grid-modern:not(.list-view) {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* List Layout State */
    .wishlist-grid-modern.list-view {
        grid-template-columns: 1fr !important;
    }

    .wishlist-card-premium {
        background: var(--wishlist-card);
        border-radius: var(--wishlist-radius);
        overflow: hidden;
        box-shadow: var(--wishlist-shadow);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(226, 232, 240, 0.8);
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
        /* Fix for blurriness */
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        transform: translateZ(0);
        -webkit-transform: translateZ(0);
    }

    /* List Specific Card Styling */
    .list-view .wishlist-card-premium {
        flex-direction: row;
        height: 180px;
        align-items: center;
    }

    .list-view .image-container {
        width: 180px;
        height: 180px;
        padding-top: 0;
        flex-shrink: 0;
    }

    .list-view .card-details {
        flex-direction: row;
        align-items: center;
        width: 100%;
        justify-content: space-between;
        padding: 0 40px;
    }

    .list-view .card-details h3 {
        margin-bottom: 0;
        max-width: 400px;
    }

    .list-view .price-tag {
        margin-bottom: 0;
        width: 150px;
        text-align: right;
    }

    .list-view .actions-row {
        margin-top: 0;
        width: 250px;
    }

    .wishlist-card-premium:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
        border-color: var(--wishlist-primary);
    }

    .image-container {
        position: relative;
        padding-top: 100%; 
        overflow: hidden;
        background: #f1f5f9;
    }

    .image-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .wishlist-card-premium:hover .image-container img {
        transform: scale(1.08);
    }

    .card-details {
        padding: 24px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .card-details h3 {
        font-family: 'Inter', sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: var(--wishlist-text);
        margin-bottom: 12px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 44px;
    }

    .price-tag {
        font-size: 20px;
        font-weight: 800;
        color: var(--wishlist-primary);
        margin-bottom: 20px;
    }

    .actions-row {
        margin-top: auto;
        display: flex;
        gap: 12px;
    }

    .btn-buy {
        flex: 1;
        background: linear-gradient(to right, var(--wishlist-primary), #4f46e5);
        color: white;
        text-decoration: none;
        padding: 12px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-align: center;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }

    .btn-buy:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
    }

    .btn-remove {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #ef4444;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
    }

    .btn-remove:hover {
        background: #fef2f2;
        border-color: #fecaca;
        color: #dc2626;
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        background: white;
        border-radius: var(--wishlist-radius);
        box-shadow: var(--wishlist-shadow);
    }

    .empty-state i {
        font-size: 64px;
        color: #e2e8f0;
        margin-bottom: 20px;
    }

    @media (max-width: 1024px) {
        .list-view .wishlist-card-premium { flex-direction: column; height: auto; }
        .list-view .image-container { width: 100%; padding-top: 100%; }
        .list-view .card-details { flex-direction: column; padding: 24px; align-items: flex-start; }
        .list-view .price-tag { text-align: left; width: auto; margin-bottom: 20px; }
        .list-view .actions-row { width: 100%; }
    }

    @media (max-width: 768px) {
        .dashboard-container { padding: 20px; }
    }
</style>

<div class="dashboard-container">
    <div style="display: flex; gap: 30px;">
        @include('user.partials.sidebar', ['active' => 'wishlist'])

        <main style="flex: 1; min-width: 0;">
            <div class="wishlist-hero">
                <div>
                    <h1>Dream Collections</h1>
                    <p>You have {{ $wishlistItems->total() }} curated items waiting.</p>
                </div>
                <div class="view-controls">
                    <button class="view-btn active" id="gridMode" title="Grid View">
                        <i class="fa-solid fa-table-cells-large"></i>
                    </button>
                    <button class="view-btn" id="listMode" title="List View">
                        <i class="fa-solid fa-list"></i>
                    </button>
                </div>
            </div>

            <div class="wishlist-container-inner">
                <div class="wishlist-grid-modern" id="wishlistGrid">
                    @forelse($wishlistItems as $item)
                        @if($item->product)
                            <article class="wishlist-card-premium" id="wishlist-item-{{ $item->product->id }}">
                                <div class="image-container">
                                    <a href="{{ route('products.show', $item->product->id) }}">
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                    </a>
                                </div>
                                <div class="card-details">
                                    <div>
                                        <span style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">
                                            {{ $item->product->category->name ?? 'Collection' }}
                                        </span>
                                        <h3>{{ $item->product->name }}</h3>
                                    </div>
                                    <div class="price-tag">${{ number_format($item->product->price, 2) }}</div>
                                    
                                    <div class="actions-row">
                                        <a href="{{ route('products.show', $item->product->id) }}" class="btn-buy">
                                            <i class="fa-solid fa-cart-shopping"></i> Purchase
                                        </a>
                                        <button type="button" class="btn-remove wishlist-toggle-btn" 
                                                data-id="{{ $item->product->id }}" 
                                                data-name="{{ $item->product->name }}">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @endif
                    @empty
                        <div class="empty-state">
                            <i class="fa-solid fa-heart-crack"></i>
                            <h2>Your wishlist is empty</h2>
                            <p>Discover something special and add it to your wishlist!</p>
                            <a href="{{ route('products.index') }}" style="display: inline-block; margin-top: 20px; padding: 14px 30px; background: var(--wishlist-primary); color: white; text-decoration: none; border-radius: 12px; font-weight: 700;">Explore Products</a>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($wishlistItems->hasPages())
                <div class="pagination-wrap" style="margin-top: 40px; display: flex; justify-content: center;">
                    {{ $wishlistItems->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('wishlistGrid');
    const gridBtn = document.getElementById('gridMode');
    const listBtn = document.getElementById('listMode');

    // View Toggles
    gridBtn.addEventListener('click', () => {
        grid.classList.remove('list-view');
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        gsap.from(".wishlist-card-premium", { opacity: 0, scale: 0.9, duration: 0.4, stagger: 0.05 });
    });

    listBtn.addEventListener('click', () => {
        grid.classList.add('list-view');
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
        gsap.from(".wishlist-card-premium", { opacity: 0, x: -20, duration: 0.4, stagger: 0.05 });
    });

    // GSAP Entrance
    gsap.from(".wishlist-hero", { duration: 1, y: 20, opacity: 0, ease: "power3.out" });
    gsap.from(".wishlist-card-premium", {
        duration: 0.7,
        y: 40,
        opacity: 0,
        stagger: 0.08,
        ease: "power2.out",
        delay: 0.2,
        clearProps: "all" // Important to remove blur/transform after animation
    });

    // Remove logic
    document.querySelectorAll('.wishlist-toggle-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const url = "{{ route('wishlist.toggle', ':id') }}".replace(':id', id);
            const card = document.getElementById(`wishlist-item-${id}`);

            Swal.fire({
                title: 'Drop from Wishlist?',
                text: `Remove "${name}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6366f1',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    gsap.to(card, {
                        scale: 0.9,
                        opacity: 0,
                        duration: 0.3,
                        onComplete: () => {
                            fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            }).then(() => window.location.reload());
                        }
                    });
                }
            });
        });
    });
});
</script>
@endsection
