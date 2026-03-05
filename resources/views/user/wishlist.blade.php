@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<!-- Include same sidebar layout but with enhanced styling -->
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/user-wishlist.css') }}">

<div class="dashboard-container wishlist-layout">
    <div class="wishlist-flex">
        @include('user.partials.sidebar', ['active' => 'wishlist'])

        <main class="wishlist-main">
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
                                        <img src="{{ display_image($item->product->image) }}" alt="{{ $item->product->name }}">
                                    </a>
                                </div>
                                <div class="card-details">
                                    <div>
                                        <span class="category-label">
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
                        <div class="wishlist-empty-state">
                            <i class="fa-solid fa-heart-crack"></i>
                            <h2>Your wishlist is empty</h2>
                            <p>Discover something special and add it to your wishlist!</p>
                            <a href="{{ route('products.index') }}" class="btn-explore">Explore Products</a>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($wishlistItems->hasPages())
                <div class="pagination-wrap">
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
