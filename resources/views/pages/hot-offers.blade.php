@extends('layouts.app')

@section('content')
<style>
    .page-hero {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7, #127FFF);
        padding: 60px 0 40px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .page-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
    .page-hero h1 {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }
    .page-hero p {
        font-size: 16px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    .page-hero .hero-icon {
        font-size: 50px;
        margin-bottom: 16px;
        display: block;
        position: relative;
        z-index: 1;
    }
    .deals-countdown-bar {
        background: #1e293b;
        color: white;
        padding: 16px 0;
        text-align: center;
    }
    .deals-countdown-bar .countdown-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .deals-countdown-bar .deal-title-text {
        font-weight: 700;
        font-size: 16px;
    }
    .deals-countdown-bar .count-box {
        background: rgba(255,255,255,0.15);
        padding: 8px 14px;
        border-radius: 10px;
        text-align: center;
        min-width: 55px;
    }
    .deals-countdown-bar .count-num {
        font-weight: 800;
        font-size: 20px;
        display: block;
    }
    .deals-countdown-bar .count-label {
        font-size: 10px;
        text-transform: uppercase;
        opacity: 0.7;
    }
    .hot-offers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 24px;
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    .hot-offer-card {
        background: #fff;
        border: 1px solid #e3e8ee;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        position: relative;
    }
    .hot-offer-card:hover {
        box-shadow: 0 12px 36px rgba(13,110,253,0.12);
        transform: translateY(-4px);
    }
    .hot-offer-card .discount-tag {
        position: absolute;
        top: 12px;
        left: 12px;
        background: linear-gradient(135deg, #dc2626, #f87171);
        color: white;
        padding: 4px 12px;
        border-radius: 8px;
        font-weight: 800;
        font-size: 13px;
        z-index: 2;
    }
    .hot-offer-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .hot-offer-card .card-body {
        padding: 16px;
    }
    .hot-offer-card .card-body h3 {
        font-size: 15px;
        font-weight: 700;
        color: #1c1c1c;
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .hot-offer-card .price-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .hot-offer-card .price-new {
        font-weight: 800;
        font-size: 18px;
        color: #1c1c1c;
    }
    .hot-offer-card .price-old {
        text-decoration: line-through;
        color: #8b96a5;
        font-size: 14px;
    }
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        color: #8b96a5;
    }
    .empty-state i {
        font-size: 64px;
        opacity: 0.3;
        display: block;
        margin-bottom: 16px;
    }
    .empty-state h3 {
        color: #505050;
        margin-bottom: 8px;
    }
</style>

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
            <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
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
    <div class="empty-state" style="grid-column: 1 / -1;">
        <i class="fa-solid fa-fire"></i>
        <h3>No Hot Offers Right Now</h3>
        <p>Check back soon for amazing deals!</p>
        <a href="{{ route('products.index') }}" style="display: inline-block; margin-top: 16px; padding: 10px 24px; background: linear-gradient(135deg, #0d6efd, #0b5ed7); color: white; border-radius: 10px; text-decoration: none; font-weight: 600;">
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
