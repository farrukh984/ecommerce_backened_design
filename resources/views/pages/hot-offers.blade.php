@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/hot_offers.css') }}">

<div class="page-hero">
    <span class="hero-icon">🔥</span>
    <h1>Hot Offers</h1>
    <p>Grab these exclusive deals before they expire!</p>
</div>

@if($activeDeal)
<div class="deals-countdown-bar">
    <div class="countdown-wrap">
        <span class="deal-title-text"><i class="fa-solid fa-clock"></i> {{ $activeDeal->title }} — Ends in:</span>
        <div class="count-box">
            <span class="count-num" id="ho-days">00</span>
            <span class="count-label">Days</span>
        </div>
        <div class="count-box">
            <span class="count-num" id="ho-hours">00</span>
            <span class="count-label">Hours</span>
        </div>
        <div class="count-box">
            <span class="count-num" id="ho-minutes">00</span>
            <span class="count-label">Min</span>
        </div>
        <div class="count-box">
            <span class="count-num" id="ho-seconds">00</span>
            <span class="count-label">Sec</span>
        </div>
    </div>
</div>
@endif

<div class="hot-offers-grid">
    @forelse($products as $product)
    <a href="{{ route('products.show', $product->id) }}" style="text-decoration: none; color: inherit;">
        <div class="hot-offer-card">
            @if($product->discount > 0)
                <div class="discount-tag">-{{ $product->discount }}%</div>
            @endif
            <img src="{{ display_image($product->image) }}" alt="{{ $product->name }}">
            <div class="card-body">
                <h3>{{ $product->name }}</h3>
                <div class="price-row">
                    <span class="price-new">${{ number_format($product->price, 2) }}</span>
                    @if($product->old_price)
                        <span class="price-old">${{ number_format($product->old_price, 2) }}</span>
                    @endif
                </div>
            </div>
        </div>
    </a>
    @empty
    <div class="empty-state">
        <i class="fa-solid fa-fire"></i>
        <h3>No Hot Offers Right Now</h3>
        <p>Check back soon for amazing deals!</p>
        <a href="{{ route('products.index') }}" class="btn-browse">
            Browse All Products
        </a>
    </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
@if($activeDeal)
(function() {
    const endDate = new Date("{{ $activeDeal->end_date->toIso8601String() }}");

    function update() {
        const now = new Date();
        const diff = endDate - now;
        if (diff <= 0) {
            document.getElementById('ho-days').textContent = '00';
            document.getElementById('ho-hours').textContent = '00';
            document.getElementById('ho-minutes').textContent = '00';
            document.getElementById('ho-seconds').textContent = '00';
            return;
        }
        document.getElementById('ho-days').textContent = String(Math.floor(diff / 86400000)).padStart(2, '0');
        document.getElementById('ho-hours').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
        document.getElementById('ho-minutes').textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
        document.getElementById('ho-seconds').textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
    }
    update();
    setInterval(update, 1000);
})();
@endif
</script>
@endsection
