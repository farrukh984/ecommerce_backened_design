@extends('layouts.app')

@section('content')

<div class="container listing-wrapper">

    <!-- ============ BREADCRUMB ============ -->
    <div class="breadcrumb-bar">
        <a href="/home">Home</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="#">Clothings</a>
        <i class="fa-solid fa-chevron-right"></i>
        <a href="#">Men's wear</a>
        <i class="fa-solid fa-chevron-right"></i>
        <span>Summer clothing</span>
    </div>

    <div class="listing-layout">

        <!-- ============ FILTER SIDEBAR ============ -->
        <aside class="filter-sidebar" id="filterSidebar">
            
            <div class="filter-header-mobile">
                <span>Filters</span>
                <button class="close-filter-btn" id="closeFilterBtn"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form method="GET" action="{{ route('products.index') }}">

                <!-- Category -->
                <div class="filter-group">
                    <div class="filter-title">Category</div>
                    <ul>
                        @foreach($categories ?? [] as $cat)
                            @php
                                $catValue = is_object($cat) ? $cat->id : $cat;
                                $catLabel = is_object($cat) ? $cat->name : $cat;
                            @endphp
                            <li>
                                <a href="{{ request()->fullUrlWithQuery(['category' => $catValue]) }}"
                                   class="{{ request('category') == $catValue ? 'active-filter' : '' }}">
                                    {{ $catLabel }}
                                </a>
                            </li>
                        @endforeach
                        <li><a href="#" class="see-all-link">See all</a></li>
                    </ul>
                </div>

                <!-- Brands -->
                <div class="filter-group">
                    <div class="filter-title">Brands</div>
                    @foreach($brands ?? [] as $brand)
                        <label class="filter-check">
                            <input type="checkbox" name="brands[]" value="{{ $brand }}"
                                {{ in_array($brand, request('brands', [])) ? 'checked' : '' }}>
                            <span>{{ $brand }}</span>
                        </label>
                    @endforeach
                    <a href="#" class="see-all-link">See all</a>
                </div>

                <!-- Features -->
                <div class="filter-group">
                    <div class="filter-title">Features</div>
                    @foreach($features ?? [] as $feature)
                        @php
                            $featValue = is_object($feature) ? $feature->id : $feature;
                            $featLabel = is_object($feature) ? $feature->name : $feature;
                        @endphp
                        <label class="filter-check">
                            <input type="checkbox" name="features[]" value="{{ $featValue }}"
                                {{ in_array($featValue, request('features', [])) ? 'checked' : '' }}>
                            <span>{{ $featLabel }}</span>
                        </label>
                    @endforeach
                    <a href="#" class="see-all-link">See all</a>
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
                    @foreach($conditions ?? $conditionOptions as $condition)
                        @php
                            $condValue = is_object($condition) ? $condition->id : $condition;
                            $condLabel = is_object($condition) ? $condition->name : $condition;
                        @endphp
                        <label class="filter-radio">
                            <input type="radio" name="condition" value="{{ $condValue }}"
                                {{ request('condition') == $condValue ? 'checked' : '' }}
                                {{ $loop->first && !request('condition') ? 'checked' : '' }}>
                            <span>{{ $condLabel }}</span>
                        </label>
                    @endforeach
                </div>

                <!-- Ratings -->
                <div class="filter-group">
                    <div class="filter-title">Ratings</div>
                    @foreach([5, 4, 3, 2, 1] as $star)
                        <label class="filter-check">
                            <input type="checkbox" name="ratings[]" value="{{ $star }}"
                                {{ in_array($star, request('ratings', [])) ? 'checked' : '' }}>
                            <span class="star-rating">
                                @for($i = 0; $i < $star; $i++)
                                    <i class="fa-solid fa-star"></i>
                                @endfor
                                @for($i = $star; $i < 5; $i++)
                                    <i class="fa-regular fa-star"></i>
                                @endfor
                            </span>
                        </label>
                    @endforeach
                </div>

            </form>
        </aside>


        <!-- ============ PRODUCT AREA ============ -->
        <div class="product-area">

            <!-- Top Toolbar -->
            <div class="top-bar">
                <div class="top-bar-left">
                    <div>
                        <strong>{{ $products->total() ?? $products->count() }}</strong> items in
                        <strong>Mobile accessory</strong>
                    </div>
                    
                    <!-- Mobile Filter Toggle -->
                    <button class="mobile-filter-btn" id="mobileFilterBtn">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                </div>

                <div class="top-bar-right">
                    <label class="verified-check">
                        <input type="checkbox"> Verified only
                    </label>

                    <select class="sort-select">
                        <option>Featured</option>
                        <option>Lowest price</option>
                        <option>Highest price</option>
                        <option>Newest</option>
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
                    <button class="wishlist-btn" title="Add to wishlist">
                        <i class="fa-regular fa-heart"></i>
                    </button>

                    <!-- Image -->
                    <div class="product-img">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                    </div>

                    <!-- Info -->
                    <div class="product-info">
                        <h3 class="product-title">{{ $product->name }}</h3>

                        <div class="product-price">
                            <span class="price-current">${{ number_format($product->price, 2) }}</span>
                            @if(!empty($product->old_price))
                                <span class="price-old">${{ number_format($product->old_price, 2) }}</span>
                            @endif
                        </div>

                        <div class="product-rating">
                            <span class="stars-display">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= ($product->rating ?? 0))
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star"></i>
                                    @endif
                                @endfor
                            </span>
                            <span class="rating-num">{{ $product->rating ?? '0' }}</span>
                            <span class="dot-sep">·</span>
                            <span class="orders-count">154 orders</span>
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
                    @if(method_exists($products, 'links'))
                        {{ $products->appends(request()->query())->links() }}
                    @endif
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

});
</script>
@endsection