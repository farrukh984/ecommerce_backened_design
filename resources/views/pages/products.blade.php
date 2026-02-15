@extends('layouts.app')

@section('content')

<div class="container listing-wrapper">

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        Home > Clothings > Men's wear > Summer clothing
    </div>

    <div class="listing-layout">

        <!-- Sidebar -->
        @include('partials.filters')

        <!-- Product Area -->
        <div class="product-area">

            <!-- Top Filter Bar -->
            <div class="top-bar">
                <div>
                    <strong>12,911</strong> items in <strong>Mobile accessory</strong>
                </div>

                <div class="top-controls">
                    <label>
                        <input type="checkbox"> Verified only
                    </label>

                    <select>
                        <option>Featured</option>
                        <option>Low price</option>
                        <option>High price</option>
                    </select>

                    <div class="grid-icon">☰</div>
                </div>
            </div>


            <!-- Product Cards -->
            @for($i = 0; $i < 4; $i++)
            <div class="product-card">

                <div class="product-image">
                    <img src="https://via.placeholder.com/180">
                </div>

                <div class="product-info">
                    <h3>Canon Camera EOS 2000, Black 10x zoom</h3>

                    <div class="price-row">
                        <span class="price">$998.00</span>
                        <span class="old-price">$1128.00</span>
                    </div>

                    <div class="rating-row">
                        ⭐⭐⭐⭐☆
                        <span>7.5</span>
                        <span class="orders">154 orders</span>
                        <span class="shipping">Free Shipping</span>
                    </div>

                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                        sed do eiusmod tempor incididunt ut labore.
                    </p>

                    <a href="#" class="details-link">View details</a>
                </div>

                <div class="wishlist">
                    ♥
                </div>

            </div>
            @endfor

        </div>

    </div>
</div>


<!-- Pagination -->
<div class="pagination-wrapper">

    <select>
        <option>Show 10</option>
        <option>Show 20</option>
        <option>Show 50</option>
    </select>

    <div class="pagination">
        <span class="arrow">‹</span>
        <span class="active">1</span>
        <span>2</span>
        <span>3</span>
        <span class="arrow">›</span>
    </div>

</div>


<div class="newsletter-section">
    <h3>Subscribe on our newsletter</h3>
    <p>Get daily news on upcoming offers from many suppliers all over the world</p>

    <div class="newsletter-form">
        <input type="email" placeholder="Email">
        <button>Subscribe</button>
    </div>
</div>


@endsection
