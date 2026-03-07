@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        {{ session('error') }}
    </div>
@endif

<div class="container home-wrapper">

    <!-- ================= HERO SECTION ================= -->
    <div class="hero-section">

        <div class="sidebar">
            <ul>
                @foreach($categories as $category)
                <li class="{{ $loop->first ? 'active' : '' }}">
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" style="text-decoration: none; color: inherit; display: block;">
                        {{ $category->name }}
                    </a>
                </li>
                @endforeach 
                <li><a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit; display: block;">More category</a></li>
            </ul>
        </div>

        <div class="hero-banner">
            <div class="hero-text">
                <p class="hero-subtitle">Latest trending</p>
                <h1>Electronic items</h1>
                <a href="{{ route('products.index') }}" class="btn-learn-more">Learn more</a>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/home_banner_figma.png') }}" alt="Electronic Items">
            </div>
        </div>

        <div class="hero-right">
            <div class="card blue-card">
                <div class="user-welcome">
                    <div class="card-avatar">
                        @auth
                            @if(auth()->user()->profile_image)
                                <img src="{{ display_image(auth()->user()->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                            @else
                                <i class="fa-regular fa-circle-user"></i>
                            @endif
                        @else
                            <i class="fa-regular fa-circle-user"></i>
                        @endauth
                    </div>
                    <div class="welcome-text">
                        <h4>Hi, {{ auth()->check() ? auth()->user()->name : 'user' }}</h4>
                        <p>let's get started</p>
                    </div>
                </div>
                @if(!auth()->check())
                    <a href="{{ route('register') }}" class="btn-join">Join now</a>
                    <a href="{{ route('login') }}" class="btn-login">Log in</a>
                @else
                    <a href="{{ auth()->user()->is_admin ? route('admin.dashboard') : route('user.dashboard') }}" class="btn-join">Dashboard</a>
                @endif
            </div>

            <div class="card orange-card">
                <p>Get US $10 off</p>
                <span>with a new supplier</span>
            </div>

            <div class="card teal-card">
                <p>Send quotes with supplier preferences</p>
            </div>
        </div>

    </div>


    <!-- ================= DEALS SECTION ================= -->
    @if($activeDeal)
    <div class="deals-section">

        <div class="deals-info">
            <h3>{{ $activeDeal->title }}</h3>
            <p class="deals-sub">{{ $activeDeal->description }}</p>
            <div class="countdown" id="countdown">
                <div class="count-box">
                    <span class="count-num" id="days">00</span>
                    <span class="count-label">Days</span>
                </div>
                <div class="count-box">
                    <span class="count-num" id="hours">00</span>
                    <span class="count-label">Hour</span>
                </div>
                <div class="count-box">
                    <span class="count-num" id="minutes">00</span>
                    <span class="count-label">Min</span>
                </div>
                <div class="count-box">
                    <span class="count-num" id="seconds">00</span>
                    <span class="count-label">Sec</span>
                </div>
            </div>
        </div>

        <div class="deals-grid">
            @foreach($deals as $item)
            @if($item->product)
            <div class="deal-item">
                <a href="{{ route('products.show', $item->product_id) }}" class="deal-link">
                    <img src="{{ display_image($item->product->image) }}" alt="{{ $item->product->name }}">
                    <p>{{ $item->product->name }}</p>
                    
                    @php
                        $discountedPrice = $item->product->price * (1 - $item->discount_percent / 100);
                    @endphp
                    
                    <div class="deal-price-info" style="margin-bottom: 8px;">
                        <span style="font-weight: 700; color: var(--text-primary); font-size: 15px;">
                            ${{ number_format($discountedPrice, 2) }}
                        </span>
                        <span style="text-decoration: line-through; color: var(--text-muted, #8b96a5); font-size: 12px; margin-left: 4px;">
                            ${{ number_format($item->product->price, 2) }}
                        </span>
                    </div>

                    <span class="discount">-{{ $item->discount_percent }}%</span>
                </a>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif


    <!-- @foreach($categories as $category)
    <div class="category-section">
        <div class="category-left {{ $loop->iteration % 2 == 0 ? 'blue-bg' : 'green-bg' }}">
            <h3>{!! str_replace(' ', '<br>', $category->name) !!}</h3>
            <a href="#" class="btn-source">Source now</a>
        </div>

    </div>
    @endforeach  -->


@foreach($categories as $category)
<div class="category-section">
    <div class="category-left {{ $loop->iteration % 2 == 0 ? 'blue-bg' : 'green-bg' }}" 
         @if($category->background_image)
             style="background-image: url('{{ display_image($category->background_image) }}'); background-size: cover; background-position: center;"
         @endif
    >
        <h3>{!! str_replace(' ', '<br>', $category->name) !!}</h3>
        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn-source">Source now</a>
    </div>
            <div class="category-grid">
            @foreach($category->products->take(8) as $product)
            <div class="category-item">
                <a href="{{ route('products.show', $product->id) }}" class="cat-link">
                    <div class="cat-info">
                        <p class="cat-name">{{ $product->name }}</p>
                        <span class="cat-price">
                            From<br>USD {{ $product->price }}
                        </span>
                    </div>

                    <img src="{{ display_image($product->image) }}" 
                         alt="{{ $product->name }}">
                </a>
            </div>
            @endforeach
        </div>
</div>
@endforeach




</div>


<!-- ================= INQUIRY SECTION ================= -->

<div class="inquiry-section" id="inquiry-section" style="background-image: linear-gradient(rgba(var(--inquiry-overlay-rgb, 0, 32, 70), 0.7), rgba(var(--inquiry-overlay-rgb, 0, 32, 70), 0.7)), url('{{ asset('images/figma_home_contact_section.png') }}');">
    <div class="container inquiry-flex">
        <div class="inquiry-left">
            <h2>An easy way to send <br>requests to all suppliers</h2>
            <p>One request, multiple quotes. Compare prices, delivery times, and supplier qualifications easily in one place.</p>
        </div>

        <div class="inquiry-form">
            <h4>Send quote to suppliers</h4>
            <form method="POST" action="{{ route('inquiry.send') }}">
                @csrf
                <div class="form-group">
                    <input type="text" name="item" placeholder="What item you need?" class="form-control" required>
                </div>
                <div class="form-group">
                    <textarea name="details" placeholder="Type more details" rows="3" class="form-control"></textarea>
                </div>

                <div class="form-row">
                    <input type="number" name="quantity" placeholder="Quantity" class="form-control" required>
                    <select name="unit" class="form-control">
                        <option value="Pcs">Pcs</option>
                        <option value="Kg">Kg</option>
                        <option value="Sets">Sets</option>
                    </select>
                </div>

                <button type="submit" class="btn-inquiry">Send inquiry</button>
            </form>
        </div>
    </div>
</div>


<div class="container">

    <!-- ================= RECOMMENDED ================= -->

    <div class="recommended-section">
        <h3>Recommended items</h3>

        <div class="recommended-grid">

@foreach($recommended as $product)
<div class="recommended-item">
    <a href="{{ route('products.show', $product->id) }}" class="rec-link">
        <img src="{{ display_image($product->image) }}" alt="{{ $product->name }}">

        <div class="rec-info">
            <h4>${{ $product->price }}</h4>
            <p>{{ $product->name }}</p>

            @if($product->old_price)
                <small class="old-price">
                    ${{ $product->old_price }}
                </small>
            @endif
        </div>
    </a>
</div>
@endforeach

</div>

    </div>

    <!-- ================= EXTRA SERVICES ================= -->

    <div class="extra-services">
        <h3>Our extra services</h3>

        <div class="services-grid">

            <a href="{{ route('products.index') }}" class="service-link">
                <div class="service-item">
                    <div class="service-img-wrapper">
                        <img src="{{ asset('images/Extra_services_1.png') }}" alt="Industry Hubs">
                        <div class="service-icon blue">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                    <p>Source from Industry Hubs</p>
                </div>
            </a>

            <a href="{{ route('products.index') }}" class="service-link">
                <div class="service-item">
                    <div class="service-img-wrapper">
                        <img src="{{ asset('images/Extra_services_2.png') }}" alt="Customize Products">
                        <div class="service-icon green">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                    </div>
                    <p>Customize Your Products</p>
                </div>
            </a>

            <a href="{{ route('products.index') }}" class="service-link">
                <div class="service-item">
                    <div class="service-img-wrapper">
                        <img src="{{ asset('images/Extra_services_3.png') }}" alt="Shipping">
                        <div class="service-icon orange">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                    </div>
                    <p>Fast, reliable shipping by ocean or air</p>
                </div>
            </a>

            <a href="{{ route('products.index') }}" class="service-link">
                <div class="service-item">
                    <div class="service-img-wrapper">
                        <img src="{{ asset('images/Extra_services_4.png') }}" alt="Monitoring">
                        <div class="service-icon indigo">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <p>Product monitoring and inspection</p>
                </div>
            </a>

        </div>
    </div>



    <!-- ================= SUPPLIERS ================= -->

    <div class="suppliers-section">
        <h3>Suppliers by region</h3>

        <div class="suppliers-grid">
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/ae.png" alt="UAE">
                    <div class="supplier-info">
                        <strong>Arabic Emirates</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/au.png" alt="Australia">
                    <div class="supplier-info">
                        <strong>Australia</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/us.png" alt="USA">
                    <div class="supplier-info">
                        <strong>United States</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/ru.png" alt="Russia">
                    <div class="supplier-info">
                        <strong>Russia</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/it.png" alt="Italy">
                    <div class="supplier-info">
                        <strong>Italy</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/dk.png" alt="Denmark">
                    <div class="supplier-info">
                        <strong>Denmark</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/fr.png" alt="France">
                    <div class="supplier-info">
                        <strong>France</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/cn.png" alt="China">
                    <div class="supplier-info">
                        <strong>China</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="supplier-card">
                    <img src="https://flagcdn.com/80x60/gb.png" alt="Great Britain">
                    <div class="supplier-info">
                        <strong>Great Britain</strong>
                        <span>shopname.ae</span>
                    </div>
                </div>
            </a>
        </div>
    </div>


    <!-- ================= NEWSLETTER ================= -->

    <div class="newsletter">
        <h3>Subscribe on our newsletter</h3>
        <p>Get daily news on upcoming offers from many suppliers all over the world</p>

        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="newsletter-form">
            @csrf
            <input type="email" name="email" placeholder="Email" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>

</div>

@endsection


@section('scripts')
<script>
// Countdown Timer — Dynamic from admin deal
function startCountdown() {
    @if($activeDeal && $activeDeal->end_date)
        const endDate = new Date("{{ $activeDeal->end_date->toIso8601String() }}");
    @else
        // Fallback: no active deal, show zeros
        document.getElementById('days').textContent = '00';
        document.getElementById('hours').textContent = '00';
        document.getElementById('minutes').textContent = '00';
        document.getElementById('seconds').textContent = '00';
        return;
    @endif

    function updateTimer() {
        const now = new Date();
        const diff = endDate - now;

        if (diff <= 0) {
            document.getElementById('days').textContent = '00';
            document.getElementById('hours').textContent = '00';
            document.getElementById('minutes').textContent = '00';
            document.getElementById('seconds').textContent = '00';
            // Hide section when time is up
            const section = document.querySelector('.deals-section');
            if(section) section.style.display = 'none';
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }

    updateTimer();
    setInterval(updateTimer, 1000);
}

// Auto-hide success/error messages after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    startCountdown();
    
    const alerts = document.querySelectorAll('.alert-success, .alert-error');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});
</script>
@endsection