@extends('layouts.app')

@section('content')

@php
    $wishlistIds = auth()->check()
        ? auth()->user()->wishlistItems()->pluck('product_id')->all()
        : session()->get('wishlist', []);
@endphp

<div class="container listing-wrapper">

    <!-- ============ BREADCRUMB ============ -->
    <div class="breadcrumb-bar">
        <a href="/home">Home</a>
        <i class="fa-solid fa-chevron-right"></i>
        @if($currentCategory)
            @if($currentCategory->parent && $currentCategory->parent->parent)
                <a href="{{ route('products.index', ['category' => $currentCategory->parent->parent->id]) }}">{{ $currentCategory->parent->parent->name }}</a>
                <i class="fa-solid fa-chevron-right"></i>
            @endif
            @if($currentCategory->parent)
                <a href="{{ route('products.index', ['category' => $currentCategory->parent->id]) }}">{{ $currentCategory->parent->name }}</a>
                <i class="fa-solid fa-chevron-right"></i>
            @endif
            <span>{{ $currentCategory->name }}</span>
        @else
            <span>All Products</span>
        @endif
    </div>

    <div class="listing-layout">

        <!-- ============ FILTER SIDEBAR ============ -->
        <aside class="filter-sidebar" id="filterSidebar">
            
            <div class="filter-header-mobile">
                <span>Filters</span>
                <button class="close-filter-btn" id="closeFilterBtn"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form method="GET" action="{{ route('products.index') }}" id="filterForm">

                <!-- Category -->
                <div class="filter-group">
                    <div class="filter-title">Category</div>
                    <ul class="filter-list" id="list-categories">
                        @foreach($categories ?? [] as $cat)
                            @php
                                $catValue = is_object($cat) ? $cat->id : $cat;
                                $catLabel = is_object($cat) ? $cat->name : $cat;
                            @endphp
                            <li class="{{ $loop->iteration > 5 ? 'hidden-item' : '' }}">
                                <a href="{{ request()->fullUrlWithQuery(['category' => $catValue]) }}"
                                   class="{{ request('category') == $catValue ? 'active-filter' : '' }}">
                                    {{ $catLabel }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    @if(count($categories ?? []) > 5)
                        <a href="javascript:void(0)" class="see-all-link" onclick="toggleFilterItems(this, 'list-categories')">
                            <span>See all</span> <i class="fa-solid fa-chevron-down"></i>
                        </a>
                    @endif
                </div>

                <!-- Brands -->
                <div class="filter-group">
                    <div class="filter-title">Brands</div>
                    <div class="filter-list" id="list-brands">
                        @foreach($brands ?? [] as $brand)
                            <label class="filter-check {{ $loop->iteration > 5 ? 'hidden-item' : '' }}">
                                <input type="checkbox" name="brands[]" value="{{ $brand }}"
                                    {{ in_array($brand, request('brands', [])) ? 'checked' : '' }}>
                                <span>{{ $brand }}</span>
                            </label>
                        @endforeach
                    </div>
                    @if(count($brands ?? []) > 5)
                        <a href="javascript:void(0)" class="see-all-link" onclick="toggleFilterItems(this, 'list-brands')">
                            <span>See all</span> <i class="fa-solid fa-chevron-down"></i>
                        </a>
                    @endif
                </div>

                <!-- Features -->
                <div class="filter-group">
                    <div class="filter-title">Features</div>
                    <div class="filter-list" id="list-features">
                        @foreach($features ?? [] as $feature)
                            @php
                                $featValue = is_object($feature) ? $feature->id : $feature;
                                $featLabel = is_object($feature) ? $feature->name : $feature;
                            @endphp
                            <label class="filter-check {{ $loop->iteration > 5 ? 'hidden-item' : '' }}">
                                <input type="checkbox" name="features[]" value="{{ $featValue }}"
                                    {{ in_array($featValue, request('features', [])) ? 'checked' : '' }}>
                                <span>{{ $featLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                    @if(count($features ?? []) > 5)
                        <a href="javascript:void(0)" class="see-all-link" onclick="toggleFilterItems(this, 'list-features')">
                            <span>See all</span> <i class="fa-solid fa-chevron-down"></i>
                        </a>
                    @endif
                </div>

                <!-- Price Range -->
                <div class="filter-group">
                    <div class="filter-title">Price range</div>
                    <div class="price-range-slider">
                        <input type="range" min="0" max="{{ $maxPrice ?? 999 }}" value="{{ request('min_price', 0) }}" class="range-input" id="rangeMin">
                        <input type="range" min="0" max="{{ $maxPrice ?? 999 }}" value="{{ request('max_price', $maxPrice ?? 999) }}" class="range-input" id="rangeMax">
                    </div>
                    <div class="price-inputs">
                        <div class="price-field">
                            <label>Min</label>
                            <input type="number" name="min_price" value="{{ request('min_price', 0) }}" placeholder="0">
                        </div>
                        <div class="price-field">
                            <label>Max</label>
                            <input type="number" name="max_price" value="{{ request('max_price', $maxPrice ?? 999) }}" placeholder="999999">
                        </div>
                    </div>
                    <button type="submit" class="apply-btn">Apply</button>
                </div>

                <!-- Condition -->
                <div class="filter-group">
                    <div class="filter-title">Condition</div>
                    @php $conditionOptions = ['Any', 'Refurbished', 'Brand new', 'Old items']; @endphp
                    <div class="filter-list" id="list-conditions">
                        @foreach($conditions ?? $conditionOptions as $condition)
                            @php
                                $condValue = is_object($condition) ? $condition->id : $condition;
                                $condLabel = is_object($condition) ? $condition->name : $condition;
                            @endphp
                            <label class="filter-radio {{ $loop->iteration > 5 ? 'hidden-item' : '' }}">
                                <input type="radio" name="condition" value="{{ $condValue }}"
                                    {{ request('condition') == $condValue ? 'checked' : '' }}
                                    {{ $loop->first && !request('condition') ? 'checked' : '' }}>
                                <span>{{ $condLabel }}</span>
                            </label>
                        @endforeach
                    </div>
                    @if(count($conditions ?? $conditionOptions) > 5)
                        <a href="javascript:void(0)" class="see-all-link" onclick="toggleFilterItems(this, 'list-conditions')">
                            <span>See all</span> <i class="fa-solid fa-chevron-down"></i>
                        </a>
                    @endif
                </div>

                <!-- Ratings -->
                <div class="filter-group">
                    <div class="filter-title">Ratings</div>
                    <div class="filter-list" id="list-ratings">
                        @foreach([5, 4, 3, 2] as $star)
                            <label class="filter-check">
                                <input type="checkbox" name="ratings[]" value="{{ $star }}"
                                    {{ in_array($star, request('ratings', [])) ? 'checked' : '' }}
                                    onclick="document.querySelectorAll('input[name=\'ratings[]\']').forEach(i => i !== this && (i.checked = false)); this.form.submit();">
                                <span class="star-rating">
                                    @for($i = 0; $i < $star; $i++)
                                        <i class="fa-solid fa-star"></i>
                                    @endfor
                                    @for($i = $star; $i < 5; $i++)
                                        <i class="fa-regular fa-star"></i>
                                    @endfor
                                    <span class="rating-label-text">{{ number_format($star, 1) }} & up</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

            </form>
        </aside>


        <!-- ============ PRODUCT AREA ============ -->
        <div class="product-area">

            <!-- Top Toolbar -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <div>
                        <strong>{{ $products->total() }}</strong> items in
                        <strong>{{ $currentCategory ? $currentCategory->name : 'All Products' }}</strong>
                    </div>
                    
                    <!-- Mobile Filter Toggle -->
                    <button class="mobile-filter-btn" id="mobileFilterBtn">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </div>

                <div class="top-bar-right">
                    <label class="verified-check">
                        <input type="checkbox" name="verified" value="1" {{ request('verified') ? 'checked' : '' }} onchange="this.form.submit()" form="filterForm"> Verified only
                    </label>
                    <select class="sort-select" name="sort" onchange="this.form.submit()" form="filterForm">
                        <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Lowest price</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Highest price</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                    </select>
                    <!-- View Toggle Buttons -->
                    <div class="view-toggle">
                        <button class="view-btn active" id="listViewBtn" title="List view">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <button class="view-btn" id="gridViewBtn" title="Grid view">
                            <i class="fa-solid fa-grip"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Active Filter Tags -->
            @if(request('q') || request('category') || request('brands') || request('ratings') || request('conditions') || request('features') || request('min_price') || request('max_price'))
            <div class="active-filters">
                @if(request('q'))
                    <span class="filter-tag">
                        Search: {{ request('q') }}
                        <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="tag-close">×</a>
                    </span>
                @endif

                @if(request('brands'))
                    @foreach(request('brands') as $brand)
                        <span class="filter-tag">
                            {{ $brand }}
                            <a href="{{ request()->fullUrlWithQuery(['brands' => array_diff(request('brands', []), [$brand])]) }}" class="tag-close">×</a>
                        </span>
                    @endforeach
                @endif

                @if(request('ratings'))
                    @foreach(request('ratings') as $rating)
                        <span class="filter-tag">
                            ⭐ {{ $rating }} Star
                            <a href="{{ request()->fullUrlWithQuery(['ratings' => array_diff(request('ratings', []), [$rating])]) }}" class="tag-close">×</a>
                        </span>
                    @endforeach
                @endif

                @if(request('category'))
                    @php $catName = isset($categories) && method_exists($categories, 'where') ? ($categories->where('id', request('category'))->first()?->name ?? request('category')) : request('category'); @endphp
                    <span class="filter-tag">
                        {{ $catName }}
                        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" class="tag-close">×</a>
                    </span>
                @endif

                @if(request('min_price') || request('max_price'))
                    <span class="filter-tag">
                        ${{ request('min_price', '0') }} - ${{ request('max_price', '∞') }}
                        <a href="{{ request()->fullUrlWithQuery(['min_price' => null, 'max_price' => null]) }}" class="tag-close">×</a>
                    </span>
                @endif

                <a href="{{ route('products.index') }}" class="clear-all">Clear all filters</a>
            </div>
            @endif


            <!-- ======== PRODUCTS CONTAINER (List + Grid) ======== -->
            <div class="products-container" id="productsContainer">

                @forelse($products as $product)

                <!-- Single Product Card -->
                <div class="product-card" data-id="{{ $product->id }}">

                    <!-- Wishlist -->
                    @php $inWishlist = in_array($product->id, $wishlistIds); @endphp
                    <button class="wishlist-btn wishlist-toggle" data-id="{{ $product->id }}" title="{{ $inWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}">
                        <i class="{{ $inWishlist ? 'fa-solid' : 'fa-regular' }} fa-heart" style="{{ $inWishlist ? 'color: #fa3434;' : '' }}"></i>
                    </button>

                    <!-- Image -->
                    <a href="{{ route('products.show', $product->id) }}" class="product-img">
                        <img src="{{ display_image($product->image) }}" alt="{{ $product->name }}">
                    </a>

                    <!-- Info -->
                    <div class="product-info">
                        <a href="{{ route('products.show', $product->id) }}" class="product-title-link">
                            <h3 class="product-title">{{ $product->name }}</h3>
                        </a>

                        <div class="product-price">
                            <span class="price-current">${{ number_format($product->price, 2) }}</span>
                            @if(!empty($product->old_price))
                                <span class="price-old">${{ number_format($product->old_price, 2) }}</span>
                            @endif
                        </div>

                        <div class="product-rating">
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
                            <span class="rating-num">{{ $product->rating ?? '0' }}</span>
                            <span class="dot-sep">·</span>
                            @if($product->stock_quantity <= 0)
                                <span style="background: #fee2e2; color: #ef4444; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-right: 8px;">Sold Out</span>
                            @endif
                            <span class="orders-count">{{ $product->sold_count ?? 154 }} orders</span>
                            <span class="free-ship"><i class="fa-solid fa-truck"></i> Free Shipping</span>
                        </div>

                        <p class="product-desc">
                            {{ Str::limit($product->description, 150) }}
                        </p>

                        <a href="{{ route('products.show', $product->id) }}" class="view-details-link">View details</a>
                    </div>
                </div>

                @empty
                <div class="no-products">
                    <i class="fa-solid fa-box-open"></i>
                    <p>No products found matching your filters.</p>
                    <a href="{{ route('products.index') }}" class="clear-all">Clear all filters</a>
                </div>
                @endforelse

            </div>


            <!-- ======== PAGINATION ======== -->
            <div class="pagination-bar">
                <div class="page-size">
                    <select>
                        <option>Show 10</option>
                        <option>Show 20</option>
                        <option>Show 50</option>
                    </select>
                </div>

                <div class="page-numbers">
                    {{ $products->links() }}


                </div>
            </div>

        </div>
        <!-- /product-area -->

    </div>
    <!-- /listing-layout -->

</div>
<!-- /container -->


<!-- Newsletter -->
<div class="newsletter-section">
    <div class="container">
        <h3>Subscribe on our newsletter</h3>
        <p>Get daily news on upcoming offers from many suppliers all over the world</p>
        <div class="newsletter-form">
            <input type="email" placeholder="Email">
            <button>Subscribe</button>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ===== VIEW TOGGLE (List / Grid) =====
    const listBtn     = document.getElementById('listViewBtn');
    const gridBtn     = document.getElementById('gridViewBtn');
    const container   = document.getElementById('productsContainer');

    // Default is list view
    function setListView() {
        container.classList.remove('grid-view');
        container.classList.add('list-view');
        listBtn.classList.add('active');
        gridBtn.classList.remove('active');
        localStorage.setItem('productView', 'list');
    }

    function setGridView() {
        container.classList.remove('list-view');
        container.classList.add('grid-view');
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
        localStorage.setItem('productView', 'grid');
    }

    listBtn.addEventListener('click', setListView);
    gridBtn.addEventListener('click', setGridView);

    // Restore saved preference
    const saved = localStorage.getItem('productView');
    if (saved === 'grid') {
        setGridView();
    } else {
        setListView();
    }


    // ===== MOBILE FILTER TOGGLE =====
    const filterToggle = document.getElementById('mobileFilterBtn');
    const filterSidebar = document.getElementById('filterSidebar');
    const closeFilterBtn = document.getElementById('closeFilterBtn');

    function openFilter() {
        filterSidebar.classList.add('show-mobile');
        document.body.classList.add('filter-open');
    }

    function closeFilter() {
        filterSidebar.classList.remove('show-mobile');
        document.body.classList.remove('filter-open');
    }

    if (filterToggle) {
        filterToggle.addEventListener('click', openFilter);
    }

    if (closeFilterBtn) {
        closeFilterBtn.addEventListener('click', closeFilter);
    }

    // ===== CLICKABLE CARD IN GRID VIEW =====
    container.addEventListener('click', function(e) {
        // Only trigger if we are in grid-view
        if (!container.classList.contains('grid-view')) return;

        const card = e.target.closest('.product-card');
        const wishlistBtn = e.target.closest('.wishlist-btn');
        
        // If we clicked the card but NOT the wishlist button
        if (card && !wishlistBtn) {
            const productId = card.getAttribute('data-id');
            window.location.href = `/products/${productId}`;
        }
    });

    // Wishlist Toggle Logic
    document.querySelectorAll('.wishlist-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent card click
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
                const icon = self.querySelector('i');
                if (data.status === 'added') {
                    icon.classList.replace('fa-regular', 'fa-solid');
                    icon.style.color = '#fa3434';
                    self.title = 'Remove from wishlist';
                } else {
                    icon.classList.replace('fa-solid', 'fa-regular');
                    icon.style.color = '';
                    self.title = 'Add to wishlist';
                }
            });
        });
    });

});

// Global function for sidebar filter toggle
function toggleFilterItems(link, listId) {
    const list = document.getElementById(listId);
    const hiddenItems = list.querySelectorAll('.hidden-item');
    const label = link.querySelector('span');
    const icon = link.querySelector('i');
    
    if (label.innerText === 'See all') {
        hiddenItems.forEach(item => {
            item.classList.add('visible-item');
        });
        list.classList.add('expanded');
        label.innerText = 'See less';
        icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
    } else {
        hiddenItems.forEach(item => {
            item.classList.remove('visible-item');
        });
        list.classList.remove('expanded');
        label.innerText = 'See all';
        icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
    }
}
</script>
@endsection
