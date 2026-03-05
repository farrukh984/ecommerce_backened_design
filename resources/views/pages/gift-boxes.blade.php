@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/gift-boxes.css') }}">
@endsection

@section('content')

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
        <div class="gift-empty-state">
            <i class="fa-solid fa-gift"></i>
            <h3>No Gift Items Available</h3>
            <p>Check back soon!</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
