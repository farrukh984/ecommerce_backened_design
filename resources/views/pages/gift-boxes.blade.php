@extends('layouts.app')

@section('content')
<style>
    .gift-hero {
        background: linear-gradient(135deg, #7c3aed, #a855f7, #ec4899);
        padding: 60px 0 40px;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .gift-hero::before {
        content: '🎁';
        position: absolute;
        font-size: 200px;
        opacity: 0.08;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .gift-hero h1 {
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
        position: relative;
        z-index: 1;
    }
    .gift-hero p {
        font-size: 16px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    .gift-hero .hero-icon {
        font-size: 50px;
        margin-bottom: 16px;
        display: block;
        position: relative;
        z-index: 1;
    }
    .gift-categories {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }
    .gift-categories h2 {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 24px;
    }
    .gift-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 24px;
    }
    .gift-card {
        background: #fff;
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .gift-card:hover {
        box-shadow: 0 12px 36px rgba(0,0,0,0.08);
        transform: translateY(-4px);
    }
    .gift-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    .gift-card .card-body {
        padding: 16px;
    }
    .gift-card .card-body h3 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .gift-card .card-body .price {
        font-weight: 800;
        font-size: 18px;
        color: #7c3aed;
    }
    .gift-card .card-body p {
        font-size: 13px;
        color: #64748b;
        margin-top: 6px;
    }
    .gift-banner {
        max-width: 1200px;
        margin: 0 auto 40px;
        padding: 0 20px;
    }
    .gift-banner-inner {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 20px;
        padding: 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 30px;
        flex-wrap: wrap;
    }
    .gift-banner-inner h3 {
        font-size: 24px;
        font-weight: 800;
        color: #92400e;
    }
    .gift-banner-inner p {
        font-size: 14px;
        color: #78350f;
        margin-top: 6px;
    }
    .gift-banner-inner a {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: white;
        padding: 12px 28px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s;
    }
    .gift-banner-inner a:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
    }
</style>

<div class="gift-hero">
    <span class="hero-icon">🎁</span>
    <h1>Gift Boxes</h1>
    <p>Find the perfect gift for every occasion</p>
</div>

<div class="gift-banner">
    <div class="gift-banner-inner">
        <div>
            <h3>🎉 Special Gift Collections</h3>
            <p>Explore our curated gift boxes for birthdays, anniversaries, and celebrations!</p>
        </div>
        <a href="{{ route('products.index') }}"><i class="fa-solid fa-arrow-right"></i> Shop All</a>
    </div>
</div>

<div class="gift-categories">
    <h2>Trending Gift Ideas</h2>
    <div class="gift-grid">
        @forelse($products as $product)
        <a href="{{ route('products.show', $product->id) }}" class="gift-card">
            <img src="{{ display_image($product->image) }}" alt="{{ $product->name }}">
            <div class="card-body">
                <h3>{{ $product->name }}</h3>
                <span class="price">${{ number_format($product->price, 2) }}</span>
                <p>Perfect for gifting 🎁</p>
            </div>
        </a>
        @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: #94a3b8;">
            <i class="fa-solid fa-gift" style="font-size: 60px; opacity: 0.3; display: block; margin-bottom: 12px;"></i>
            <h3 style="color: #475569;">No Gift Items Available</h3>
            <p>Check back soon!</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
