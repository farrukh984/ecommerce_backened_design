@extends('layouts.app')

@section('content')

<div class="container product-detail-wrapper">

    <!-- ============ MOBILE HEADER (Mobile Only) ============ -->
    <div class="mobile-detail-header">
        <a href="{{ url()->previous() }}" class="m-header-btn">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div class="m-header-actions">
            <a href="{{ route('cart') }}" class="m-header-btn">
                <i class="fa-solid fa-cart-shopping"></i>
            </a>
            <a href="#" class="m-header-btn">
                <i class="fa-regular fa-user"></i>
            </a>
        </div>
    </div>

    <!-- ============ BREADCRUMB (Desktop Only) ============ -->
    <div class="breadcrumb-bar desktop-only">
        <a href="/home">Home</a>
        <i class="fa-solid fa-chevron-right"></i>
        @if($product->category && $product->category->parent && $product->category->parent->parent)
            <a href="#">{{ $product->category->parent->parent->name ?? 'Clothings' }}</a>
            <i class="fa-solid fa-chevron-right"></i>
        @endif
        @if($product->category && $product->category->parent)
            <a href="#">{{ $product->category->parent->name ?? 'Men\'s wear' }}</a>
            <i class="fa-solid fa-chevron-right"></i>
        @endif
        @if($product->category)
            <a href="#">{{ $product->category->name ?? 'Summer clothing' }}</a>
            <i class="fa-solid fa-chevron-right"></i>
        @endif
        <span>{{ Str::limit($product->name, 30) }}</span>
    </div>

    <!-- ============ MOBILE CONTENT ============ -->
    <div class="mobile-detail-container">
        
        <!-- Mobile Image Section -->
        <div class="mobile-image-wrapper">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            <div class="m-image-dots">
                <span class="dot active"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>
        </div>

        <!-- Mobile Product Info -->
        <div class="mobile-product-info">
            
            <!-- Rating Row -->
            <div class="m-rating-row">
                <span class="m-stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->rating ?? 4.5))
                            <i class="fa-solid fa-star"></i>
                        @elseif($i - 0.5 <= ($product->rating ?? 4.5))
                            <i class="fa-solid fa-star-half-stroke"></i>
                        @else
                            <i class="fa-regular fa-star"></i>
                        @endif
                    @endfor
                </span>
                <span class="m-reviews"><i class="fa-regular fa-comment-dots"></i> 32 reviews</span>
                <span class="m-sold"><i class="fa-solid fa-basket-shopping"></i> 154 sold</span>
            </div>

            <!-- Product Name -->
            <h1 class="m-product-name">{{ $product->name }}</h1>

            <!-- Price -->
            <div class="m-price-section">
                <span class="m-price-red">${{ number_format($product->price, 2) }}</span>
                <span class="m-price-range">(50-100 pcs)</span>
            </div>

            <!-- Send Inquiry Button -->
            <div class="m-button-row">
                <button class="btn-m-inquiry">Send inquiry</button>
                <button class="btn-m-heart">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div>

            <!-- Specifications -->
            <div class="m-specs-list">
                <div class="m-spec-item">
                    <span class="m-spec-label">Condition</span>
                    <span class="m-spec-value">Brand new</span>
                </div>
                <div class="m-spec-item">
                    <span class="m-spec-label">Material</span>
                    <span class="m-spec-value">Plastic</span>
                </div>
                <div class="m-spec-item">
                    <span class="m-spec-label">Category</span>
                    <span class="m-spec-value">Electronics, gadgets</span>
                </div>
                <div class="m-spec-item">
                    <span class="m-spec-label">Item num</span>
                    <span class="m-spec-value">23421</span>
                </div>
            </div>

            <!-- Description Preview -->
            <div class="m-description">
                <p>Info about edu item is an ideal companion for anyone engaged in learning. The drone provides precise and ...</p>
                <a href="#" class="m-read-more">Read more</a>
            </div>
        </div>

        <!-- Supplier Card -->
        <div class="m-supplier-card">
            <div class="m-supplier-header">
                <div class="m-supplier-avatar">R</div>
                <div class="m-supplier-info">
                    <span class="m-sup-label">Supplier</span>
                    <strong>Guanjio Trading LLC</strong>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="m-supplier-badges">
                <span><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='20' height='14'%3E%3Crect width='20' height='14' fill='%23000'/%3E%3Crect y='5' width='20' height='4' fill='%23D00'/%3E%3Crect y='9' width='20' height='5' fill='%23FFCE00'/%3E%3C/svg%3E" alt="Germany"> Germany</span>
                <span><i class="fa-solid fa-circle-check"></i> Verified</span>
                <span><i class="fa-solid fa-truck"></i> Shipping</span>
            </div>
        </div>

        <!-- Similar Products -->
        <div class="m-similar-section">
            <h3>Similar products</h3>
            <div class="m-similar-scroll">
                @foreach($similarProducts->take(6) as $sim)
                <a href="{{ route('products.show', $sim->id) }}" class="m-similar-card">
                    <img src="{{ asset('storage/' . $sim->image) }}" alt="{{ $sim->name }}">
                    <div class="m-similar-info">
                        <span class="m-sim-price">${{ number_format($sim->price, 2) }}</span>
                        <p>{{ Str::limit($sim->name, 40) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ============ DESKTOP LAYOUT ============ -->
    <div class="product-detail-grid desktop-only">

        <!-- LEFT COLUMN: IMAGE GALLERY -->
        <div class="detail-gallery">
            <div class="main-image-box">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" id="mainProductImg">
            </div>
            <div class="thumb-list">
                <div class="thumb-item active">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Thumbnail 1">
                </div>
                <div class="thumb-item">
                    <img src="https://via.placeholder.com/80x80/f5f5f5/666666?text=2" alt="Thumbnail 2">
                </div>
                <div class="thumb-item">
                    <img src="https://via.placeholder.com/80x80/f5f5f5/666666?text=3" alt="Thumbnail 3">
                </div>
                <div class="thumb-item">
                    <img src="https://via.placeholder.com/80x80/f5f5f5/666666?text=4" alt="Thumbnail 4">
                </div>
                <div class="thumb-item">
                    <img src="https://via.placeholder.com/80x80/f5f5f5/666666?text=5" alt="Thumbnail 5">
                </div>
                <div class="thumb-item">
                    <img src="https://via.placeholder.com/80x80/f5f5f5/666666?text=6" alt="Thumbnail 6">
                </div>
            </div>
        </div>

        <!-- CENTER COLUMN: PRODUCT INFO -->
        <div class="detail-main-info">
            
            <div class="stock-status">
                <i class="fa-solid fa-check"></i> In stock
            </div>

            <h1 class="detail-title">{{ $product->name }}</h1>

            <div class="detail-rating-row">
                <span class="stars-display">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($product->rating ?? 4.5))
                            <i class="fa-solid fa-star"></i>
                        @elseif($i - 0.5 <= ($product->rating ?? 4.5))
                            <i class="fa-solid fa-star-half-stroke"></i>
                        @else
                            <i class="fa-regular fa-star"></i>
                        @endif
                    @endfor
                </span>
                <span class="rating-num">{{ $product->rating ?? '4.5' }}</span>
                <span class="dot-sep">•</span>
                <span class="detail-reviews"><i class="fa-regular fa-comment-dots"></i> 32 reviews</span>
                <span class="dot-sep">•</span>
                <span class="detail-sold"><i class="fa-solid fa-basket-shopping"></i> 154 sold</span>
            </div>

            <div class="price-tier-block">
                <div class="price-col active">
                    <span class="p-price">$98.00</span>
                    <span class="p-qty">50-100 pcs</span>
                </div>
                <div class="price-col">
                    <span class="p-price">$90.00</span>
                    <span class="p-qty">100-700 pcs</span>
                </div>
                <div class="price-col">
                    <span class="p-price">$75.00</span>
                    <span class="p-qty">700+ pcs</span>
                </div>
            </div>

            <div class="clean-specs">
                <div class="spec-row">
                    <span class="s-label">Price:</span>
                    <span class="s-value">Negotiable</span>
                </div>
                <div class="spec-border"></div>
                <div class="spec-row">
                    <span class="s-label">Type:</span>
                    <span class="s-value">Classic shoes</span>
                </div>
                <div class="spec-row">
                    <span class="s-label">Material:</span>
                    <span class="s-value">Plastic material</span>
                </div>
                <div class="spec-row">
                    <span class="s-label">Design:</span>
                    <span class="s-value">Modern nice</span>
                </div>
                <div class="spec-border"></div>
                <div class="spec-row">
                    <span class="s-label">Customization:</span>
                    <span class="s-value">Customized logo and design custom packages</span>
                </div>
                <div class="spec-row">
                    <span class="s-label">Protection:</span>
                    <span class="s-value">Refund Policy</span>
                </div>
                <div class="spec-row">
                    <span class="s-label">Warranty:</span>
                    <span class="s-value">2 years full warranty</span>
                </div>
                <div class="spec-border"></div>
            </div>

        </div>

        <!-- RIGHT COLUMN: SUPPLIER INFO -->
        <div class="detail-right-sidebar">
            <div class="supplier-card-box">
                <div class="s-header">
                    <div class="s-avatar">R</div>
                    <div class="s-info">
                        <span class="s-label-text">Supplier</span>
                        <strong>Guardia Trading LLC</strong>
                        <span class="s-location">Germany, Berlin</span>
                    </div>
                </div>
                <div class="s-divider"></div>
                <div class="s-meta-row">
                    <i class="fa-solid fa-shield-halved"></i> <span>Verified Seller</span>
                </div>
                <div class="s-meta-row">
                    <i class="fa-solid fa-globe"></i> <span>Worldwide shipping</span>
                </div>
                <div class="s-actions">
                    <button class="btn-pry-blue">Send inquiry</button>
                    <a href="#" class="btn-sec-link">Seller's profile</a>
                </div>
            </div>

            <div class="save-later-box">
                <a href="#" class="btn-text-icon">
                    <i class="fa-regular fa-heart"></i> Save for later
                </a>
            </div>
        </div>

    </div>

    <!-- ============ TABS & DESCRIPTION SECTION (Desktop Only) ============ -->
    <div class="detail-tabs-layout desktop-only">
        
        <div class="detail-left-content">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="description">Description</button>
                <button class="tab-btn" data-tab="reviews">Reviews</button>
                <button class="tab-btn" data-tab="shipping">Shipping</button>
                <button class="tab-btn" data-tab="about">About seller</button>
            </div>

            <div class="tab-content active" id="description-tab">
                <div class="desc-text-block">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>
                    <p>Quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                </div>

                <table class="desc-specs-table">
                    <tr><td>Model</td><td>#8768857</td></tr>
                    <tr><td>Style</td><td>Classic style</td></tr>
                    <tr><td>Certificate</td><td>ISO-9845212</td></tr>
                    <tr><td>Size</td><td>34mm x 450mm x 19mm</td></tr>
                    <tr><td>Memory</td><td>36GB RAM</td></tr>
                </table>

                <ul class="check-list">
                    <li><i class="fa-solid fa-check"></i> Some great feature name here</li>
                    <li><i class="fa-solid fa-check"></i> Lorem ipsum dolor sit amet, consectetur</li>
                    <li><i class="fa-solid fa-check"></i> Duis aute irure dolor in reprehenderit</li>
                    <li><i class="fa-solid fa-check"></i> Some great feature name here</li>
                </ul>
            </div>
        </div>

        <div class="detail-right-content">
            <div class="you-box">
                <h3>You may like</h3>
                @foreach($youMayLike->take(5) as $yml)
                <a href="{{ route('products.show', $yml->id) }}" class="yml-item">
                    <img src="{{ asset('storage/' . $yml->image) }}" alt="{{ $yml->name }}">
                    <div class="yml-info">
                        <h5>{{ Str::limit($yml->name, 35) }}</h5>
                        <span class="yml-price">${{ number_format($yml->price, 2) }} - $99.50</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        
    </div>

    <!-- ============ RELATED PRODUCTS (Desktop Only) ============ -->
    <div class="related-products-box desktop-only">
        <h3>Related products</h3>
        <div class="related-grid">
            @foreach($similarProducts->take(6) as $rel)
            <a href="{{ route('products.show', $rel->id) }}" class="rel-item">
                <div class="rel-img">
                    <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}">
                </div>
                <div class="rel-info">
                    <h5>{{ Str::limit($rel->name, 30) }}</h5>
                    <span class="rel-price">${{ number_format($rel->price, 2) }}-$40.00</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- ============ DISCOUNT BANNER (Desktop Only) ============ -->
    <div class="detail-discount-banner desktop-only">
        <div class="discount-banner">
            <div class="discount-banner-text">
                <h3>Super discount on more than 100 USD</h3>
                <p>Have you ever finally just write dummy info</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn-shop-now">Shop now</a>
        </div>
    </div>

</div>

@endsection
