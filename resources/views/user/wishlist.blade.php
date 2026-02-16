@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'wishlist'])

    <main class="dashboard-main">
        <div class="recent-activity">
            <div class="section-header">
                <h2>My Wishlist</h2>
                <span class="action-btn">{{ $wishlistItems->total() }} products</span>
            </div>

            <div class="wishlist-grid">
                @forelse($wishlistItems as $item)
                    @if($item->product)
                        <article class="wishlist-card">
                            <a href="{{ route('products.show', $item->product->id) }}" class="wishlist-image-wrap">
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                            </a>
                            <div class="wishlist-content">
                                <h3>{{ \Illuminate\Support\Str::limit($item->product->name, 55) }}</h3>
                                <p>${{ number_format($item->product->price, 2) }}</p>
                                <div class="wishlist-actions">
                                    <a href="{{ route('products.show', $item->product->id) }}" class="action-btn">View</a>
                                    <button type="button" class="action-btn wishlist-toggle" data-id="{{ $item->product->id }}">Remove</button>
                                </div>
                            </div>
                        </article>
                    @endif
                @empty
                    <p>No wishlist products found.</p>
                @endforelse
            </div>

            <div style="margin-top: 20px;">
                {{ $wishlistItems->links() }}
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.wishlist-toggle').forEach(function(button) {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const url = "{{ route('wishlist.toggle', ':id') }}".replace(':id', id);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(function() {
            window.location.reload();
        });
    });
});
</script>
@endsection
