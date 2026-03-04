@extends('layouts.app')

@section('body_class', 'product-detail-page')

@section('content')

@php
    $wishlistIds = auth()->check()
        ? auth()->user()->wishlistItems()->pluck('product_id')->all()
        : session()->get('wishlist', []);
    $inWishlist = in_array($product->id, $wishlistIds);
@endphp

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
            <div class="m-image-slider" id="mobileImageSlider">
                <div class="m-slide">
                    <img src="{{ display_image($product->image) }}" alt="{{ $product->name }}">
                </div>
                @foreach($product->images as $gImg)
                <div class="m-slide">
                    <img src="{{ display_image($gImg->image) }}" alt="Gallery Image">
                </div>
                @endforeach
            </div>
            <div class="m-image-dots">
                <span class="dot active" data-index="0"></span>
                @php $dotIdx = 1; @endphp
                @foreach($product->images as $gImg)
                <span class="dot" data-index="{{ $dotIdx++ }}"></span>
                @endforeach
            </div>
        </div>

        <!-- Mobile Product Info -->
        <div class="mobile-product-info">
            
            <!-- Rating Row -->
            <div class="m-rating-row">
                <span class="m-stars">
                    @php $rating = $product->rating ?? 0; @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($rating))
                            <i class="fa-solid fa-star"></i>
                        @elseif($i == ceil($rating) && ($rating - floor($rating)) >= 0.3)
                            <i class="fa-solid fa-star-half-stroke"></i>
                        @else
                            <i class="fa-regular fa-star"></i>
                        @endif
                    @endfor
                </span>
                <span class="m-reviews"><i class="fa-regular fa-comment-dots"></i> {{ $product->approvedReviews->count() }} reviews</span>
                <span class="m-sold"><i class="fa-solid fa-basket-shopping"></i> {{ $product->sold_count ?? 154 }} sold</span>
            </div>

            <!-- Product Name -->
            <h1 class="m-product-name">{{ $product->name }}</h1>

            <!-- Price -->
            <div class="m-price-section">
                @php $firstTier = $product->priceTiers->first(); @endphp
                <span class="m-price-red">${{ number_format($firstTier->price ?? $product->price, 2) }}</span>
                <span class="m-price-range">({{ ($firstTier->min_qty ?? 50) . '-' . ($firstTier->max_qty ?? '100') }} pcs)</span>
            </div>

            <!-- Add to Cart (Inquiry) Section -->
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <div class="m-button-row">
                    <div class="qty-control">
                        <button type="button" onclick="changeQty(-1, 'm-qty')">-</button>
                        <input type="number" name="quantity" id="m-qty" value="1" min="1">
                        <button type="button" onclick="changeQty(1, 'm-qty')">+</button>
                    </div>
                    @if($product->stock_quantity > 0)
                        <button type="submit" class="btn-m-inquiry">Send inquiry</button>
                    @else
                        <button type="button" class="btn-m-inquiry sold-out" disabled>Sold Out</button>
                    @endif
                </div>
            </form>

            <div class="m-button-row">
                <button class="btn-m-heart wishlist-toggle" data-id="{{ $product->id }}" style="{{ $inWishlist ? 'color: #fa3434; border-color: #fa3434;' : '' }}">
                    <i class="{{ $inWishlist ? 'fa-solid' : 'fa-regular' }} fa-heart"></i> 
                    <span class="wish-text">{{ $inWishlist ? 'Added to wishlist' : 'Add to wishlist' }}</span>
                </button>
            </div>

            <!-- Specifications -->
            <div class="m-specs-list">
                <div class="m-spec-item">
                    <span class="m-spec-label">Condition</span>
                    <span class="m-spec-value">{{ $product->condition->name ?? 'Brand new' }}</span>
                </div>
                <div class="m-spec-item">
                    <span class="m-spec-label">Material</span>
                    <span class="m-spec-value">{{ $product->material ?? 'Plastic material' }}</span>
                </div>
                <div class="m-spec-item">
                    <span class="m-spec-label">Category</span>
                    <span class="m-spec-value">{{ $product->category->name ?? 'Electronics, gadgets' }}</span>
                </div>
                <div class="m-spec-item">
                    <span class="m-spec-label">Item num</span>
                    <span class="m-spec-value">{{ $product->item_number ?? '23421' }}</span>
                </div>
            </div>

            <!-- Description Preview -->
            <div class="m-description">
                <div class="description-expand-box" id="mobileDescBox">
                    <p>{{ $product->description }}</p>
                </div>
                <a href="javascript:void(0)" class="m-read-more-btn" id="mobileDescBtn" onclick="toggleReadMore('mobileDescBox', this)">Read more</a>
            </div>

            <!-- Shipping & Delivery (Mobile) -->
            <div class="m-shipping-info">
                <h3>Shipping & Delivery</h3>
                <div class="m-shipping-content">
                    <i class="fa-solid fa-truck-fast blue"></i>
                    <div class="m-shipping-text">
                        Fast international shipping from <strong>{{ $product->supplier->location ?? 'Global Warehouse' }}</strong>. 
                        Estimated delivery: 7-15 business days.
                    </div>
                </div>
                <div class="m-shipping-note">
                    <i class="fa-solid fa-shield-check green"></i> All shipments are fully insured and trackable.
                </div>
            </div>
        </div>

        <!-- Supplier Card -->
        <div class="m-supplier-card">
            <div class="m-supplier-header">
                <div class="m-supplier-avatar">{{ substr($product->supplier->name ?? 'R', 0, 1) }}</div>
                <div class="m-supplier-info">
                    <span class="m-sup-label">Supplier</span>
                    <strong>{{ $product->supplier->name ?? 'Guanjio Trading LLC' }}</strong>
                </div>
                <i class="fa-solid fa-chevron-right"></i>
            </div>
            <div class="m-supplier-badges">
                <span>{{ $product->supplier->location ?? 'Germany' }}</span>
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
                    <img src="{{ display_image($sim->image) }}" alt="{{ $sim->name }}">
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
                <img src="{{ display_image($product->image) }}" alt="{{ $product->name }}" id="mainProductImg">
            </div>
            <div class="thumb-list">
                <div class="thumb-item active" onclick="changeMainImg('{{ display_image($product->image) }}', this)">
                    <img src="{{ display_image($product->image) }}" alt="Main Image">
                </div>
                @foreach($product->images as $gImg)
                <div class="thumb-item" onclick="changeMainImg('{{ display_image($gImg->image) }}', this)">
                    <img src="{{ display_image($gImg->image) }}" alt="Gallery Image">
                </div>
                @endforeach
                {{-- Fill up to 6 with placeholders if needed for design consistency --}}
                @for($i = $product->images->count() + 1; $i < 6; $i++)
                <div class="thumb-item">
                    <img src="https://via.placeholder.com/80x80/f5f5f5/666666?text=+" alt="Placeholder">
                </div>
                @endfor
            </div>
        </div>

        <!-- CENTER COLUMN: PRODUCT INFO -->
        <div class="detail-main-info">
            
            <div class="stock-status {{ $product->stock_quantity > 0 ? '' : 'text-danger' }}">
                <i class="fa-solid {{ $product->stock_quantity > 0 ? 'fa-check' : 'fa-xmark' }}"></i> 
                {{ $product->stock_quantity > 0 ? 'In stock (' . $product->stock_quantity . ' pcs)' : 'Sold Out' }}
            </div>

            <h1 class="detail-title">{{ $product->name }}</h1>

            <div class="detail-rating-row">
                <span class="stars-display">
                    @php $rating = $product->rating ?? 0; @endphp
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= floor($rating))
                            <i class="fa-solid fa-star"></i>
                        @elseif($i == ceil($rating) && ($rating - floor($rating)) >= 0.3)
                            <i class="fa-solid fa-star-half-stroke"></i>
                        @else
                            <i class="fa-regular fa-star"></i>
                        @endif
                    @endfor
                </span>
                <span class="rating-num">{{ number_format($rating, 1) }}</span>
                <span class="dot-sep">•</span>
                <span class="detail-reviews"><i class="fa-regular fa-comment-dots"></i> {{ $product->approvedReviews->count() }} reviews</span>
                <span class="dot-sep">•</span>
                <span class="detail-sold"><i class="fa-solid fa-basket-shopping"></i> {{ $product->sold_count ?? 154 }} sold</span>
            </div>

            <div class="price-tier-block">
                @php $tiers = $product->priceTiers->take(3); @endphp
                @if($tiers->count() > 0)
                    @foreach($tiers as $tier)
                    <div class="price-col {{ $loop->first ? 'active' : '' }}">
                        <span class="p-price">${{ number_format($tier->price, 2) }}</span>
                        <span class="p-qty">{{ $tier->min_qty }}{{ $tier->max_qty ? '-'.$tier->max_qty : '+' }} pcs</span>
                    </div>
                    @endforeach
                    @for($i = $tiers->count(); $i < 3; $i++)
                    <div class="price-col">
                        <span class="p-price">${{ number_format($product->price * (1 - 0.1 * ($i+1)), 2) }}</span>
                        <span class="p-qty">{{ (100 * ($i+1)) }}+ pcs</span>
                    </div>
                    @endfor
                @else
                    <div class="price-col active">
                        <span class="p-price">${{ number_format($product->price, 2) }}</span>
                        <span class="p-qty">50-100 pcs</span>
                    </div>
                    <div class="price-col">
                        <span class="p-price">${{ number_format($product->price * 0.9, 2) }}</span>
                        <span class="p-qty">100-700 pcs</span>
                    </div>
                    <div class="price-col">
                        <span class="p-price">${{ number_format($product->price * 0.8, 2) }}</span>
                        <span class="p-qty">700+ pcs</span>
                    </div>
                @endif
            </div>

            <div class="clean-specs">
                <div class="spec-row">
                    <span class="s-label">Price:</span>
                    <span class="s-value">{{ $product->is_negotiable ? 'Negotiable' : 'Fixed' }}</span>
                </div>
                <div class="spec-border"></div>
                <div class="spec-row">
                    <span class="s-label">Type:</span>
                    <span class="s-value">{{ $product->type ?? 'Classic style' }}</span>
                </div>
                <div class="spec-row">
                    <span class="s-label">Material:</span>
                    <span class="s-value">{{ $product->material ?? 'Plastic material' }}</span>
                </div>
                <div class="spec-row">
                    <span class="s-label">Design:</span>
                    <span class="s-value">{{ $product->design_style ?? 'Modern nice' }}</span>
                </div>
                <div class="spec-border"></div>
                <div class="spec-row">
                    <span class="s-label">Customization:</span>
                    <span class="s-value">{{ $product->customization ?? 'Customized logo and design custom packages' }}</span>
                </div>
                <div class="spec-row">
                    <span class="s-label">Protection:</span>
                    <span class="s-value">{{ $product->protection ?? 'Refund Policy' }}</span>
                </div>
                <div class="spec-row">
                    <span class="s-label">Warranty:</span>
                    <span class="s-value">{{ $product->warranty ?? '2 years full warranty' }}</span>
                </div>
                <div class="spec-border"></div>
            </div>

        </div>

        <!-- RIGHT COLUMN: SUPPLIER INFO -->
        <div class="detail-right-sidebar">
            <div class="supplier-card-box">
                <div class="s-header">
                    <div class="s-avatar">{{ substr($product->supplier->name ?? 'R', 0, 1) }}</div>
                    <div class="s-info">
                        <span class="s-label-text">Supplier</span>
                        <strong>{{ $product->supplier->name ?? 'Guardia Trading LLC' }}</strong>
                        <span class="s-location">{{ $product->supplier->location ?? 'Germany, Berlin' }}</span>
                    </div>
                </div>
                <div class="s-divider"></div>
                <div class="s-meta-row">
                    <i class="fa-solid fa-shield-halved"></i> <span>{{ $product->supplier && $product->supplier->is_verified ? 'Verified Seller' : 'Standard Seller' }}</span>
                </div>
                <div class="s-meta-row">
                    <i class="fa-solid fa-globe"></i> <span>{{ $product->supplier && $product->supplier->has_worldwide_shipping ? 'Worldwide shipping' : 'Local shipping' }}</span>
                </div>
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <div class="desktop-qty-section">
                        <label>Quantity:</label>
                        <div class="qty-control">
                            <button type="button" onclick="changeQty(-1, 'd-qty')">-</button>
                            <input type="number" name="quantity" id="d-qty" value="1" min="1">
                            <button type="button" onclick="changeQty(1, 'd-qty')">+</button>
                        </div>
                    </div>
                    <div class="s-actions">
                        @if($product->stock_quantity > 0)
                            <button type="submit" class="btn-pry-blue">Send inquiry</button>
                        @else
                            <button type="button" class="btn-pry-blue sold-out" disabled>Sold Out</button>
                        @endif
                        <a href="#" class="btn-sec-link">Seller's profile</a>
                    </div>
                </form>
            </div>

            <div class="save-later-box">
                <a href="javascript:void(0)" class="btn-text-icon wishlist-toggle {{ $inWishlist ? 'text-danger' : '' }}" data-id="{{ $product->id }}">
                    <i class="{{ $inWishlist ? 'fa-solid' : 'fa-regular' }} fa-heart"></i> 
                    <span class="wish-text">{{ $inWishlist ? 'Saved in wishlist' : 'Save for later' }}</span>
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
                <div class="description-expand-box" id="desktopDescBox">
                    <p>{{ $product->description }}</p>
                </div>
                <a href="javascript:void(0)" class="m-read-more-btn" id="desktopDescBtn" style="display: none; margin-top: 10px;" onclick="toggleReadMore('desktopDescBox', this)">Read more</a>
                
                <table class="desc-specs-table" style="margin-top: 20px;">
                    <tr><td>Model</td><td>{{ $product->model_number ?? '#'.str_pad($product->id, 7, '0', STR_PAD_LEFT) }}</td></tr>
                    <tr><td>Style</td><td>{{ $product->style ?? 'Classic style' }}</td></tr>
                    <tr><td>Certificate</td><td>{{ $product->certificate ?? 'ISO-9845212' }}</td></tr>
                    <tr><td>Size</td><td>{{ $product->size ?? 'Standard' }}</td></tr>
                    <tr><td>Memory</td><td>{{ $product->memory ?? 'N/A' }}</td></tr>
                </table>

                <ul class="check-list">
                    @forelse($product->features as $feat)
                        <li><i class="fa-solid fa-check"></i> {{ $feat->name }}</li>
                    @empty
                        <li><i class="fa-solid fa-check"></i> Quality assured product</li>
                        <li><i class="fa-solid fa-check"></i> {{ $product->material ?? 'Premium material' }}</li>
                    @endforelse
                </ul>
            </div>

            <div class="tab-content" id="reviews-tab">
                <div class="reviews-header">
                    <h4>Customer Feedback</h4>
                    <div class="rating-summary">
                        <span class="avg-rating">{{ number_format($product->approvedReviews->avg('rating') ?: $product->rating, 1) }}</span>
                        <div class="stars-display">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= round($product->approvedReviews->avg('rating') ?: $product->rating) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <span class="review-count">{{ $product->approvedReviews->count() }} reviews</span>
                    </div>
                </div>

                @auth
                    @php
                        $hasBought = \App\Models\Order::where('user_id', auth()->id())
                            ->whereHas('items', function($q) use ($product) {
                                $q->where('product_id', $product->id);
                            })->where('status', 'delivered')->exists();
                        
                        $alreadyReviewed = $product->reviews->where('user_id', auth()->id())->first();
                    @endphp

                    @if($hasBought && !$alreadyReviewed)
                        <div class="add-review-card">
                            <h5>Write a Review</h5>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="rating-input">
                                    <label>Your Rating:</label>
                                    <div class="star-rating-input">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fa-regular fa-star star-btn" data-value="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="review_rating_val" value="5" required>
                                </div>
                                <div class="form-group">
                                    <textarea name="comment" rows="3" class="review-form-control" placeholder="Tell others about your experience..." required></textarea>
                                </div>
                                <button type="submit" class="btn-pry-blue">Submit Review</button>
                            </form>
                        </div>
                    @elseif($alreadyReviewed && !$alreadyReviewed->is_approved)
                        <div class="alert alert-info">
                            <i class="fa-solid fa-clock"></i>
                            <span>Your review is pending admin approval. It will be visible soon!</span>
                        </div>
                    @elseif(!$hasBought)
                         <div class="alert alert-warning">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>Only verified buyers can leave a review.</span>
                         </div>
                    @endif
                @endauth

                <div class="reviews-list">
                    @forelse($product->approvedReviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="review-user-box">
                                    <div class="review-user-avatar">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div class="review-user-info">
                                        <div class="review-name-row">
                                            <strong>{{ $review->user->name }}</strong>
                                            <span class="buyer-badge">Verified Buyer</span>
                                        </div>
                                        <div class="stars-display mini">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="review-text">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div class="no-reviews">
                            <i class="fa-regular fa-comments"></i>
                            <p>No reviews for this product yet. Be the first to share your thoughts!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="tab-content" id="shipping-tab">
                <div class="shipping-info-wrapper">
                    <h4>Logistics & Delivery</h4>
                    <div class="shipping-grid">
                        <div class="shipping-card">
                            <div class="shipping-card-icon blue">
                                <i class="fa-solid fa-plane-up"></i>
                            </div>
                            <h5>Fast International Shipping</h5>
                            <p>Shipped from {{ $product->supplier->location ?? 'Global Hub' }}. Track your order every step of the way with our real-time portal.</p>
                        </div>
                        <div class="shipping-card">
                            <div class="shipping-card-icon green">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <h5>Premium Packaging</h5>
                            <p>Every {{ $product->name }} is packed with industry-standard export materials to ensure Zero-Damage arrival.</p>
                        </div>
                    </div>
                    
                    <div class="premium-delivery-timeline">
                        <div class="timeline-header">
                            <i class="fa-solid fa-clock-rotate-left blue-color"></i>
                            <h5>Estimated Timeline</h5>
                        </div>
                        <div class="timeline-body">
                            <div class="timeline-line"></div>
                            <div class="timeline-point">
                                <div class="timeline-dot active"></div>
                                <span class="point-label">Order Confirmed</span>
                                <span class="point-time">Instant</span>
                            </div>
                            <div class="timeline-point">
                                <div class="timeline-dot"></div>
                                <span class="point-label">Quality Check</span>
                                <span class="point-time">1-2 Days</span>
                            </div>
                            <div class="timeline-point">
                                <div class="timeline-dot"></div>
                                <span class="point-label">Arrives to You</span>
                                <span class="point-time">7-15 Days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="about-tab" style="display: none;">
                <div class="seller-profile-wrapper">
                    <div class="seller-header-card">
                        <div class="seller-big-avatar">
                             {{ substr($product->supplier->name ?? 'S', 0, 1) }}
                        </div>
                        <div class="seller-header-info">
                            <div class="seller-title-row">
                                <h3>{{ $product->supplier->name ?? 'Global Brand' }}</h3>
                                @if($product->supplier && $product->supplier->is_verified)
                                    <div class="verified-badge" title="Verified Supplier">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                @endif
                            </div>
                            <p class="seller-location"><i class="fa-solid fa-location-dot"></i> {{ $product->supplier->location ?? 'Headquarters' }}</p>
                            <div class="seller-badges">
                                <div class="seller-badge-item">
                                    <i class="fa-solid fa-award"></i> Top Rated Seller
                                </div>
                                <div class="seller-badge-item">
                                     <i class="fa-solid fa-globe"></i> Official Supplier
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="seller-info-grid">
                        <div class="seller-story">
                            <h5>Seller Story</h5>
                            <p>Our partner, <strong>{{ $product->supplier->name ?? 'this supplier' }}</strong>, has been a leading force in the electronics and consumer goods market for over a decade. Committed to excellence and quality assurance, they have served over thousands of satisfied customers globally.</p>
                        </div>
                        <div class="seller-commitment-card">
                             <i class="fa-solid fa-quote-right quote"></i>
                             <h5 class="commitment-label">Quality Commitment</h5>
                             <p class="commitment-text">"We ensure every single product meets international standards before shipping to our valued customers."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-right-content">
            <div class="you-box">
                <h3>Recommended for you</h3>
                @foreach($youMayLike->take(5) as $yml)
                <a href="{{ route('products.show', $yml->id) }}" class="yml-item">
                    <img src="{{ display_image($yml->image) }}" alt="{{ $yml->name }}">
                    <div class="yml-info">
                        <h5>{{ Str::limit($yml->name, 35) }}</h5>
                        <span class="yml-price">${{ number_format($yml->price, 2) }} - ${{ number_format($yml->price * 1.2, 2) }}</span>
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
                    <img src="{{ display_image($rel->image) }}" alt="{{ $rel->name }}">
                </div>
                <div class="rel-info">
                    <h5>{{ Str::limit($rel->name, 30) }}</h5>
                    <span class="rel-price">${{ number_format($rel->price, 2) }} - ${{ number_format($rel->price * 1.5, 2) }}</span>
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

@section('scripts')
<script>
    function changeMainImg(src, el) {
        document.getElementById('mainProductImg').src = src;
        
        // Update active thumbnail
        document.querySelectorAll('.thumb-item').forEach(item => {
            item.classList.remove('active');
        });
        el.classList.add('active');
    }

    // Tab Switching Logic
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.getAttribute('data-tab');
            
            // Update active button
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Show target content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });
            
            const targetContent = document.getElementById(tabName + '-tab');
            if (targetContent) {
                targetContent.style.display = 'block';
                setTimeout(() => targetContent.classList.add('active'), 10);
                
                // GSAP Animations when switching tabs
                if (tabName === 'reviews') {
                    gsap.from("#reviews-tab .review-item", {
                        y: 30,
                        opacity: 0,
                        stagger: 0.1,
                        duration: 0.6,
                        ease: "power2.out"
                    });
                }
                
                if (tabName === 'shipping') {
                    gsap.from(".shipping-info-wrapper", {
                        y: 20,
                        opacity: 0,
                        duration: 0.8,
                        ease: "power3.out"
                    });
                    gsap.from(".shipping-card", {
                        scale: 0.95,
                        opacity: 0,
                        stagger: 0.2,
                        delay: 0.2,
                        duration: 0.6,
                        ease: "back.out(1.7)"
                    });
                }
                
                if (tabName === 'about') {
                     gsap.set(".seller-profile-wrapper", { opacity: 1 });
                     gsap.from(".seller-big-avatar", {
                        rotate: -45,
                        scale: 0,
                        duration: 0.8,
                        ease: "back.out(2)"
                     });
                     gsap.from(".seller-profile-wrapper h3, .seller-profile-wrapper p", {
                        x: 20,
                        opacity: 0,
                        stagger: 0.1,
                        delay: 0.3,
                        duration: 0.5
                     });
                }
                
                // Trigger overflow check if switching back to description
                if (tabName === 'description') {
                    setTimeout(checkOverflow, 50);
                }
            }
        });
    });

    // Star Rating Logic
    document.querySelectorAll('.star-btn').forEach(star => {
        star.addEventListener('mouseover', function() {
            const val = this.getAttribute('data-value');
            highlightStars(val);
        });
        
        star.addEventListener('mouseleave', function() {
            const currentVal = document.getElementById('review_rating_val').value;
            highlightStars(currentVal);
        });
        
        star.addEventListener('click', function() {
            const val = this.getAttribute('data-value');
            document.getElementById('review_rating_val').value = val;
            highlightStars(val);
        });
    });

    function highlightStars(val) {
        document.querySelectorAll('.star-btn').forEach(btn => {
            const btnVal = btn.getAttribute('data-value');
            if (btnVal <= val) {
                btn.classList.replace('fa-regular', 'fa-solid');
                btn.style.color = '#ff9017';
            } else {
                btn.classList.replace('fa-solid', 'fa-regular');
                btn.style.color = '#dee2e7';
            }
        });
    }

    // Mobile Slider dot tracking
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('mobileImageSlider');
        const dots = document.querySelectorAll('.m-image-dots .dot');

        if (slider && dots.length > 0) {
            slider.addEventListener('scroll', () => {
                const scrollLeft = slider.scrollLeft;
                const width = slider.offsetWidth;
                const activeIndex = Math.round(scrollLeft / width);
                
                dots.forEach((dot, idx) => {
                    if (idx === activeIndex) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            });
        }
    });

    function toggleReadMore(containerId, btn) {
        const container = document.getElementById(containerId);
        if (container.classList.contains('expanded')) {
            container.classList.remove('expanded');
            btn.innerText = 'Read more';
        } else {
            container.classList.add('expanded');
            btn.innerText = 'Read less';
        }
    }

    // Auto-detect if description needs "Read More" button
    function checkOverflow() {
        const mobileBox = document.getElementById('mobileDescBox');
        const mobileBtn = document.getElementById('mobileDescBtn');
        const desktopBox = document.getElementById('desktopDescBox');
        const desktopBtn = document.getElementById('desktopDescBtn');

        if (mobileBox && mobileBtn) {
            if (mobileBox.scrollHeight > mobileBox.clientHeight + 5) {
                mobileBtn.style.display = 'inline-block';
            }
        }

        if (desktopBox && desktopBtn) {
            if (desktopBox.scrollHeight > desktopBox.clientHeight + 5) {
                desktopBtn.style.display = 'inline-block';
            }
        }
    }

    window.addEventListener('load', checkOverflow);
    window.addEventListener('resize', checkOverflow);

    function changeQty(amt, inputId) {
        const input = document.getElementById(inputId);
        let val = parseInt(input.value) + amt;
        if (val < 1) val = 1;
        input.value = val;
    }

    // Wishlist Toggle Logic
    document.querySelectorAll('.wishlist-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            const self = this;
            
            const url = "{{ route('wishlist.toggle', ':id') }}".replace(':id', id);
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                const icons = document.querySelectorAll(`.wishlist-toggle[data-id="${id}"] i`);
                const texts = document.querySelectorAll(`.wishlist-toggle[data-id="${id}"] .wish-text`);
                
                if (data.status === 'added') {
                    icons.forEach(i => { i.classList.replace('fa-regular', 'fa-solid'); });
                    texts.forEach(t => { t.innerText = t.parentElement.classList.contains('btn-m-heart') ? 'Added to wishlist' : 'Saved in wishlist'; });
                    document.querySelectorAll(`.wishlist-toggle[data-id="${id}"]`).forEach(b => {
                        b.style.color = '#fa3434';
                        if(b.classList.contains('btn-m-heart')) b.style.borderColor = '#fa3434';
                    });
                } else {
                    icons.forEach(i => { i.classList.replace('fa-solid', 'fa-regular'); });
                    texts.forEach(t => { t.innerText = t.parentElement.classList.contains('btn-m-heart') ? 'Add to wishlist' : 'Save for later'; });
                    document.querySelectorAll(`.wishlist-toggle[data-id="${id}"]`).forEach(b => {
                        b.style.color = '';
                        if(b.classList.contains('btn-m-heart')) b.style.borderColor = '';
                    });
                }
            });
        });
    });
</script>
@endsection
