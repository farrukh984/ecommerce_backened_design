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
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                </div>
                @foreach($product->images as $gImg)
                <div class="m-slide">
                    <img src="{{ asset('storage/' . $gImg->image) }}" alt="Gallery Image">
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
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                    <div class="qty-control" style="display: flex; align-items: center; border: 1px solid #dee2e7; border-radius: 6px; overflow: hidden; height: 40px;">
                        <button type="button" onclick="changeQty(-1, 'm-qty')" style="width: 35px; height: 100%; border: none; background: #f7f7f7; font-size: 18px; cursor: pointer;">-</button>
                        <input type="number" name="quantity" id="m-qty" value="1" min="1" style="width: 40px; text-align: center; border: none; font-size: 14px; font-weight: 600; outline: none; -moz-appearance: textfield;">
                        <button type="button" onclick="changeQty(1, 'm-qty')" style="width: 35px; height: 100%; border: none; background: #f7f7f7; font-size: 16px; cursor: pointer;">+</button>
                    </div>
                    @if($product->stock_quantity > 0)
                        <button type="submit" class="btn-m-inquiry" style="flex: 1; border: none;">Send inquiry</button>
                    @else
                        <button type="button" class="btn-m-inquiry" style="flex: 1; border: none; background: #94a3b8; cursor: not-allowed;" disabled>Sold Out</button>
                    @endif
                </div>
            </form>

            <div class="m-button-row">
                <button class="btn-m-heart wishlist-toggle" data-id="{{ $product->id }}" style="width: 100%; height: 40px; flex-direction: row; gap: 8px; font-size: 14px; {{ $inWishlist ? 'color: #fa3434; border-color: #fa3434;' : '' }}">
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
                <a href="javascript:void(0)" class="m-read-more-btn" id="mobileDescBtn" style="display: none;" onclick="toggleReadMore('mobileDescBox', this)">Read more</a>
            </div>

            <!-- Shipping & Delivery (Mobile) -->
            <div class="m-shipping-info" style="margin-top: 25px; border-top: 1px solid #eee; padding-top: 20px;">
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 12px;">Shipping & Delivery</h3>
                <div style="display: flex; gap: 12px; margin-bottom: 15px;">
                    <i class="fa-solid fa-truck-fast" style="color: #0d6efd; font-size: 18px;"></i>
                    <div style="font-size: 13px; color: #505050; line-height: 1.4;">
                        Fast international shipping from <strong>{{ $product->supplier->location ?? 'Global Warehouse' }}</strong>. 
                        Estimated delivery: 7-15 business days.
                    </div>
                </div>
                <div style="background: #f8fafc; padding: 12px; border-radius: 8px; font-size: 12px; color: #64748b;">
                    <i class="fa-solid fa-shield-check" style="color: #16a34a; margin-right: 5px;"></i> All shipments are fully insured and trackable.
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
                <div class="thumb-item active" onclick="changeMainImg('{{ asset('storage/' . $product->image) }}', this)">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Main Image">
                </div>
                @foreach($product->images as $gImg)
                <div class="thumb-item" onclick="changeMainImg('{{ asset('storage/' . $gImg->image) }}', this)">
                    <img src="{{ asset('storage/' . $gImg->image) }}" alt="Gallery Image">
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
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; color: #505050; margin-bottom: 8px;">Quantity:</label>
                        <div style="display: flex; align-items: center; border: 1px solid #dee2e7; border-radius: 6px; overflow: hidden; height: 36px; width: 110px;">
                            <button type="button" onclick="changeQty(-1, 'd-qty')" style="width: 35px; height: 100%; border: none; background: #f7f7f7; font-size: 18px; cursor: pointer;">-</button>
                            <input type="number" name="quantity" id="d-qty" value="1" min="1" style="width: 40px; text-align: center; border: none; font-size: 14px; font-weight: 600; outline: none;">
                            <button type="button" onclick="changeQty(1, 'd-qty')" style="width: 35px; height: 100%; border: none; background: #f7f7f7; font-size: 16px; cursor: pointer;">+</button>
                        </div>
                    </div>
                    <div class="s-actions">
                        @if($product->stock_quantity > 0)
                            <button type="submit" class="btn-pry-blue" style="width: 100%; margin-bottom: 10px; border: none;">Send inquiry</button>
                        @else
                            <button type="button" class="btn-pry-blue" style="width: 100%; margin-bottom: 10px; border: none; background: #94a3b8; cursor: not-allowed;" disabled>Sold Out</button>
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

            <div class="tab-content" id="reviews-tab" style="display: none;">
                <div class="reviews-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                    <h4 style="margin: 0; font-size: 20px; font-weight: 700;">Customer Feedback</h4>
                    <div class="rating-summary" style="text-align: right;">
                        <span style="font-size: 24px; font-weight: 800; color: #1c1c1c;">{{ number_format($product->approvedReviews->avg('rating') ?: $product->rating, 1) }}</span>
                        <div style="color: #ff9017; font-size: 14px;">
                            @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= round($product->approvedReviews->avg('rating') ?: $product->rating) ? 'solid' : 'regular' }} fa-star"></i>
                            @endfor
                        </div>
                        <span style="font-size: 12px; color: #8b96a5;">{{ $product->approvedReviews->count() }} reviews</span>
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
                        <div class="add-review-card" style="background: #f8fafc; border-radius: 12px; padding: 20px; margin-bottom: 30px; border: 1px solid #e2e8f0;">
                            <h5 style="margin-top: 0; margin-bottom: 15px;">Write a Review</h5>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="rating-input" style="margin-bottom: 15px;">
                                    <label style="display: block; margin-bottom: 5px; font-size: 14px;">Your Rating:</label>
                                    <div class="star-rating" style="display: flex; gap: 5px; cursor: pointer; font-size: 20px; color: #dee2e7;">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fa-regular fa-star star-btn" data-value="{{ $i }}"></i>
                                        @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="review_rating_val" value="5" required>
                                </div>
                                <div class="form-group" style="margin-bottom: 15px;">
                                    <textarea name="comment" rows="3" class="form-control" placeholder="Tell others about your experience..." style="width: 100%; padding: 12px; border: 1px solid #dee2e7; border-radius: 8px; resize: none;" required></textarea>
                                </div>
                                <button type="submit" class="btn-pry-blue" style="border: none; padding: 10px 25px;">Submit Review</button>
                            </form>
                        </div>
                    @elseif($alreadyReviewed && !$alreadyReviewed->is_approved)
                        <div style="background: #e0f2fe; border: 1px solid #bae6fd; border-radius: 10px; padding: 15px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-clock" style="color: #0ea5e9;"></i>
                            <span style="font-size: 13px; color: #0369a1;">Your review is pending admin approval. It will be visible soon!</span>
                        </div>
                    @elseif(!$hasBought)
                         <div style="background: #fff9f0; border: 1px solid #ffeeba; border-radius: 10px; padding: 15px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-circle-info" style="color: #ff9017;"></i>
                            <span style="font-size: 13px; color: #856404;">Only verified buyers can leave a review.</span>
                         </div>
                    @endif
                @endauth

                <div class="reviews-list">
                    @forelse($product->approvedReviews as $review)
                        <div class="review-item" style="margin-bottom: 20px; border-bottom: 1px solid #eff2f4; padding-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
                                <div style="display: flex; gap: 12px; align-items: center;">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                        {{ substr($review->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <strong style="font-size: 15px;">{{ $review->user->name }}</strong>
                                            <span style="background: #dcfce7; color: #166534; font-size: 10px; padding: 2px 6px; border-radius: 4px; font-weight: 600;">Verified Buyer</span>
                                        </div>
                                        <div style="color: #ff9017; font-size: 11px;">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span style="font-size: 11px; color: #8b96a5;">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <p style="color: #4b5563; font-size: 14px; margin: 0; line-height: 1.6; padding-left: 52px;">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div style="text-align: center; padding: 40px 0;">
                            <i class="fa-regular fa-comments" style="font-size: 40px; color: #dee2e7; margin-bottom: 15px; display: block;"></i>
                            <p style="color: #8b96a5;">No reviews for this product yet. Be the first to share your thoughts!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="tab-content" id="shipping-tab" style="display: none;">
                <div class="shipping-info-wrapper">
                    <h4 style="font-size: 20px; font-weight: 700; margin-bottom: 25px;">Logistics & Delivery</h4>
                    <div class="shipping-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="shipping-card" style="padding: 20px; border: 1px solid #f1f5f9; border-radius: 12px; background: #fff;">
                            <div style="width: 45px; height: 45px; border-radius: 10px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 15px;">
                                <i class="fa-solid fa-plane-up"></i>
                            </div>
                            <h5 style="margin: 0 0 8px; font-size: 16px;">Fast International Shipping</h5>
                            <p style="font-size: 13px; color: #64748b; line-height: 1.5;">Shipped from {{ $product->supplier->location ?? 'Global Hub' }}. Track your order every step of the way with our real-time portal.</p>
                        </div>
                        <div class="shipping-card" style="padding: 20px; border: 1px solid #f1f5f9; border-radius: 12px; background: #fff;">
                            <div style="width: 45px; height: 45px; border-radius: 10px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 15px;">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <h5 style="margin: 0 0 8px; font-size: 16px;">Premium Packaging</h5>
                            <p style="font-size: 13px; color: #64748b; line-height: 1.5;">Every {{ $product->name }} is packed with industry-standard export materials to ensure Zero-Damage arrival.</p>
                        </div>
                    </div>
                    
                    <div class="premium-delivery-timeline" style="margin-top: 30px; background: #f8fafc; padding: 25px; border-radius: 15px;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <i class="fa-solid fa-clock-rotate-left" style="color: #3b82f6; font-size: 20px;"></i>
                            <h5 style="margin: 0; font-size: 16px;">Estimated Timeline</h5>
                        </div>
                        <div style="display: flex; justify-content: space-between; position: relative;">
                            <div style="position: absolute; top: 10px; left: 0; right: 0; height: 4px; background: #e2e8f0; z-index: 1;"></div>
                            <div class="timeline-point" style="z-index: 2; text-align: center; width: 33%;">
                                <div style="width: 24px; height: 24px; border-radius: 50%; background: #3b82f6; border: 4px solid #fff; margin: 0 auto 10px; box-shadow: 0 0 10px rgba(59,130,246,0.2);"></div>
                                <span style="display: block; font-size: 12px; font-weight: 700; color: #1e293b;">Order Confirmed</span>
                                <span style="display: block; font-size: 11px; color: #94a3b8;">Instant</span>
                            </div>
                            <div class="timeline-point" style="z-index: 2; text-align: center; width: 33%;">
                                <div style="width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; border: 4px solid #fff; margin: 0 auto 10px;"></div>
                                <span style="display: block; font-size: 12px; font-weight: 600; color: #64748b;">Quality Check</span>
                                <span style="display: block; font-size: 11px; color: #94a3b8;">1-2 Days</span>
                            </div>
                            <div class="timeline-point" style="z-index: 2; text-align: center; width: 33%;">
                                <div style="width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; border: 4px solid #fff; margin: 0 auto 10px;"></div>
                                <span style="display: block; font-size: 12px; font-weight: 600; color: #64748b;">Arrives to You</span>
                                <span style="display: block; font-size: 11px; color: #94a3b8;">7-15 Days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="about-tab" style="display: none;">
                <div class="seller-profile-wrapper" style="opacity: 0;">
                    <div style="display: flex; gap: 30px; align-items: center; margin-bottom: 35px; background: linear-gradient(135deg, #0d6efd08, #ffffff); padding: 30px; border-radius: 20px; border: 1px solid #0d6efd15;">
                        <div class="seller-big-avatar" style="width: 100px; height: 100px; border-radius: 25px; background: #0d6efd; color: white; display: flex; align-items: center; justify-content: center; font-size: 40px; font-weight: 800; transform: rotate(-5deg); box-shadow: 10px 10px 30px rgba(13,110,253,0.15);">
                             {{ substr($product->supplier->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <h3 style="margin: 0; font-size: 24px; font-weight: 800; color: #1e293b;">{{ $product->supplier->name ?? 'Global Brand' }}</h3>
                                @if($product->supplier && $product->supplier->is_verified)
                                    <div title="Verified Supplier" style="width: 22px; height: 22px; background: #00b517; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                @endif
                            </div>
                            <p style="margin: 0 0 15px; color: #64748b; font-size: 15px;"><i class="fa-solid fa-location-dot" style="margin-right: 5px; color: #ef4444;"></i> {{ $product->supplier->location ?? 'Headquarters' }}</p>
                            <div style="display: flex; gap: 15px;">
                                <div style="background: #f1f5f9; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #475569;">
                                    <i class="fa-solid fa-award" style="color: #eab308; margin-right: 5px;"></i> Top Rated Seller
                                </div>
                                <div style="background: #f1f5f9; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 600; color: #475569;">
                                     <i class="fa-solid fa-globe" style="color: #3b82f6; margin-right: 5px;"></i> Official Supplier
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                        <div>
                            <h5 style="font-size: 18px; margin-bottom: 15px;">Seller Story</h5>
                            <p style="line-height: 1.8; color: #4b5563; font-size: 14px;">Our partner, <strong>{{ $product->supplier->name ?? 'this supplier' }}</strong>, has been a leading force in the electronics and consumer goods market for over a decade. Committed to excellence and quality assurance, they have served over thousands of satisfied customers globally.</p>
                        </div>
                        <div style="background: #1e293b; color: #fff; padding: 25px; border-radius: 18px; position: relative; overflow: hidden;">
                             <i class="fa-solid fa-quote-right" style="position: absolute; right: -10px; bottom: -10px; font-size: 100px; color: #ffffff08;"></i>
                             <h5 style="margin: 0 0 10px; color: #94a3b8; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">Quality Commitment</h5>
                             <p style="margin: 0; font-size: 16px; font-style: italic; line-height: 1.6; color: #cbd5e1;">"We ensure every single product meets international standards before shipping to our valued customers."</p>
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
                    <img src="{{ asset('storage/' . $yml->image) }}" alt="{{ $yml->name }}">
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
                    <img src="{{ asset('storage/' . $rel->image) }}" alt="{{ $rel->name }}">
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
