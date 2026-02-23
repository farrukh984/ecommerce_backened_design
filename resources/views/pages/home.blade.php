@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; margin: 20px auto; max-width: 1200px; border-radius: 8px; border: 1px solid #c3e6cb;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error" style="background: #f8d7da; color: #721c24; padding: 15px; margin: 20px auto; max-width: 1200px; border-radius: 8px; border: 1px solid #f5c6cb;">
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
                                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
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
    <div class="deals-section">

        <div class="deals-info">
            <h3>{{ $activeDeal?->title ?? 'Deals and offers' }}</h3>
            <p class="deals-sub">{{ $activeDeal?->description ?? 'Hygiene equipments' }}</p>
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
            @foreach($deals as $deal)
            <div class="deal-item">
                <a href="{{ route('products.show', $deal->id) }}" style="text-decoration: none; color: inherit; text-align: center; display: block;">
                    <img src="{{ filter_var($deal->image, FILTER_VALIDATE_URL) ? $deal->image : asset('storage/'.$deal->image) }}" alt="{{ $deal->name }}">
                    <p>{{ $deal->name }}</p>
                    <span class="discount">-{{ $deal->discount }}%</span>
                </a>
            </div>
            @endforeach
        </div>
    </div>


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
             style="background-image: url('{{ asset('storage/'.$category->background_image) }}'); background-size: cover; background-position: center;"
         @endif
    >
        <h3>{!! str_replace(' ', '<br>', $category->name) !!}</h3>
        <a href="{{ route('products.index', ['category' => $category->id]) }}" class="btn-source">Source now</a>
    </div>
            <div class="category-grid">
            @foreach($category->products->take(8) as $product)
            <div class="category-item">
                <a href="{{ route('products.show', $product->id) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center; width: 100%;">
                    <div class="cat-info">
                        <p class="cat-name">{{ $product->name }}</p>
                        <span class="cat-price">
                            From<br>USD {{ $product->price }}
                        </span>
                    </div>

                    <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/'.$product->image) }}" 
                         alt="{{ $product->name }}">
                </a>
            </div>
            @endforeach
        </div>
</div>
@endforeach




</div>


<!-- ================= INQUIRY SECTION ================= -->

<div class="inquiry-section" id="inquiry-section" style="background-image: linear-gradient(rgba(0, 32, 70, 0.7), rgba(0, 32, 70, 0.7)), url('{{ asset('images/figma_home_contact_section.png') }}'); background-size: cover; background-position: center; border-radius: 8px; margin-top: 30px; padding: 40px 0;">
    <div class="container inquiry-flex">
        <div class="inquiry-left">
            <h2 style="color: #fff; font-size: 32px; font-weight: 700;">An easy way to send <br>requests to all suppliers</h2>
            <p style="color: rgba(255,255,255,0.8); margin-top: 15px; font-size: 16px;">One request, multiple quotes. Compare prices, delivery times, and supplier qualifications easily in one place.</p>
        </div>

        <div class="inquiry-form" style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <h4 style="margin-bottom: 20px; font-weight: 700;">Send quote to suppliers</h4>
            <form method="POST" action="{{ route('inquiry.send') }}">
                @csrf
                <div class="form-group" style="margin-bottom: 12px;">
                    <input type="text" name="item" placeholder="What item you need?" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #dee2e7; border-radius: 6px;" required>
                </div>
                <div class="form-group" style="margin-bottom: 12px;">
                    <textarea name="details" placeholder="Type more details" rows="3" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #dee2e7; border-radius: 6px;"></textarea>
                </div>

                <div class="form-row" style="display: flex; gap: 10px; margin-bottom: 20px;">
                    <input type="number" name="quantity" placeholder="Quantity" class="form-control" style="flex: 1; padding: 10px; border: 1px solid #dee2e7; border-radius: 6px;" required>
                    <select name="unit" class="form-control" style="width: 80px; padding: 10px; border: 1px solid #dee2e7; border-radius: 6px;">
                        <option value="Pcs">Pcs</option>
                        <option value="Kg">Kg</option>
                        <option value="Sets">Sets</option>
                    </select>
                </div>

                <button type="submit" class="btn-inquiry" style="width: 100%; background: #0d6efd; color: #fff; border: none; padding: 12px; border-radius: 6px; font-weight: 700; cursor: pointer;">Send inquiry</button>
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
    <a href="{{ route('products.show', $product->id) }}" style="text-decoration: none; color: inherit;">
        <img src="{{ filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('storage/'.$product->image) }}" 
             alt="{{ $product->name }}">

        <div class="rec-info">
            <h4>${{ $product->price }}</h4>
            <p>{{ $product->name }}</p>

            @if($product->old_price)
                <small style="text-decoration:line-through;color:#999;">
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

    <div class="extra-services" style="margin-top: 40px; padding-bottom: 20px;">
        <h3 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Our extra services</h3>

        <div class="services-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px;">

            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="service-item" style="background: #fff; border: 1px solid #e3e8ee; border-radius: 8px; overflow: hidden; transition: transform 0.3s ease; cursor: pointer;">
                    <div class="service-img-wrapper" style="height: 120px; position: relative; overflow: hidden;">
                        <img src="{{ asset('images/Extra_services_1.png') }}" alt="Industry Hubs" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; bottom: -20px; right: 20px; width: 45px; height: 45px; background: #e7f0ff; border: 3px solid #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #0d6efd;">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                    </div>
                    <div style="padding: 25px 20px 20px;">
                        <p style="font-weight: 600; font-size: 16px; line-height: 1.4;">Source from Industry Hubs</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="service-item" style="background: #fff; border: 1px solid #e3e8ee; border-radius: 8px; overflow: hidden; transition: transform 0.3s ease; cursor: pointer;">
                    <div class="service-img-wrapper" style="height: 120px; position: relative; overflow: hidden;">
                        <img src="{{ asset('images/Extra_services_2.png') }}" alt="Customize Products" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; bottom: -20px; right: 20px; width: 45px; height: 45px; background: #e8f5e9; border: 3px solid #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #15803d;">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                    </div>
                    <div style="padding: 25px 20px 20px;">
                        <p style="font-weight: 600; font-size: 16px; line-height: 1.4;">Customize Your Products</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="service-item" style="background: #fff; border: 1px solid #e3e8ee; border-radius: 8px; overflow: hidden; transition: transform 0.3s ease; cursor: pointer;">
                    <div class="service-img-wrapper" style="height: 120px; position: relative; overflow: hidden;">
                        <img src="{{ asset('images/Extra_services_3.png') }}" alt="Shipping" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; bottom: -20px; right: 20px; width: 45px; height: 45px; background: #fff3e0; border: 3px solid #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #f38332;">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                    </div>
                    <div style="padding: 25px 20px 20px;">
                        <p style="font-weight: 600; font-size: 16px; line-height: 1.4;">Fast, reliable shipping by ocean or air</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('products.index') }}" style="text-decoration: none; color: inherit;">
                <div class="service-item" style="background: #fff; border: 1px solid #e3e8ee; border-radius: 8px; overflow: hidden; transition: transform 0.3s ease; cursor: pointer;">
                    <div class="service-img-wrapper" style="height: 120px; position: relative; overflow: hidden;">
                        <img src="{{ asset('images/Extra_services_4.png') }}" alt="Monitoring" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; bottom: -20px; right: 20px; width: 45px; height: 45px; background: #e3f2fd; border: 3px solid #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f46e5;">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                    </div>
                    <div style="padding: 25px 20px 20px;">
                        <p style="font-weight: 600; font-size: 16px; line-height: 1.4;">Product monitoring and inspection</p>
                    </div>
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