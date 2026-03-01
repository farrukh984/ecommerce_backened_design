<header class="top-header">
    <div class="container header-flex">

        <!-- Mobile: hamburger left -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="logo">
            <a href="{{ route('home') }}" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                <span class="logo-icon"><i class="fa-solid fa-lock"></i></span>
                <span class="brand-name">Brand</span>
            </a>
        </div>

        <div class="search-box">
            <form method="GET" action="{{ route('products.index') }}">
                <input type="text" name="q" placeholder="Search" value="{{ request('q') }}">
                <select name="category">
                    <option value="">All category</option>
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    @endif
                </select>
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Desktop icons -->
        <div class="header-icons">
            <a href="javascript:void(0)" class="icon-item" id="profileTrigger">
                @auth
                    @if(auth()->user()->profile_image)
                        <img src="{{ display_image(auth()->user()->profile_image) }}" alt="Profile" style="width: 20px; height: 20px; border-radius: 50%; object-fit: cover;">
                    @else
                        <i class="fa-regular fa-user"></i>
                    @endif
                @else
                    <i class="fa-regular fa-user"></i>
                @endauth
                <span>Profile</span>
            </a>
            <a href="javascript:void(0)" class="icon-item" id="messageTrigger">
                <i class="fa-regular fa-comment-dots"></i>
                <span>Message</span>
            </a>
            <a href="javascript:void(0)" class="icon-item" id="ordersTrigger">
                <i class="fa-regular fa-heart"></i>
                <span>Orders</span>
            </a>
            <a href="javascript:void(0)" class="icon-item" id="cartTrigger">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>My cart</span>
            </a>
        </div>

        <!-- Mobile: cart + user icons (visible only on mobile) -->
        <div class="header-icons-mobile">
            <a href="javascript:void(0)" id="mCartTrigger"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="javascript:void(0)" id="mProfileTrigger"><i class="fa-regular fa-user"></i></a>
        </div>

    </div>

    <!-- Mobile Search (full-width, below header bar) -->
    <div class="mobile-search-bar">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="mobile-search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="q" placeholder="Search" value="{{ request('q') }}">
            </div>
        </form>
    </div>

    <!-- Mobile Category Tabs (scrollable) -->
    <div class="mobile-category-tabs">
        <div class="category-tabs-scroll">
            <a href="{{ route('products.index') }}" class="cat-tab {{ !request('category') ? 'active' : '' }}">All category</a>
            @if(isset($categories))
                @foreach($categories->take(6) as $cat)
                    <a href="{{ route('products.index', ['category' => $cat->id]) }}" class="cat-tab {{ request('category') == $cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            @endif
        </div>
    </div>

    <nav class="sub-navbar">
        <div class="container sub-flex">
            <div class="menu-links">
                <a href="{{ route('products.index') }}"><i class="fa-solid fa-bars"></i> All category</a>
                <a href="{{ route('pages.hotOffers') }}">Hot offers</a>
                <a href="{{ route('pages.giftBoxes') }}">Gift boxes</a>
                <a href="{{ route('products.index') }}">Projects</a>
                <a href="{{ route('products.index') }}">Menu item</a>
                <a href="{{ route('pages.help') }}">Help <i class="fa-solid fa-chevron-down"></i></a>
            </div>
            <div class="right-links">
                <span>English, USD <i class="fa-solid fa-chevron-down"></i></span>
                <span>Ship to <img src="https://flagcdn.com/20x15/de.png" alt="DE" class="flag-icon"> <i class="fa-solid fa-chevron-down"></i></span>
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar Menu -->
    <div class="mobile-menu" id="mobileMenu">

        <!-- User Avatar & Auth -->
        <div class="sidebar-user">
            <button class="close-menu" id="closeMenuBtn"><i class="fa-solid fa-xmark"></i></button>
            <div class="sidebar-avatar">
                <i class="fa-regular fa-circle-user"></i>
            </div>
            <div class="sidebar-auth">
                <a href="{{ route('login') }}">Sign in</a>
                <span class="auth-divider">|</span>
                <a href="{{ route('register') }}">Register</a>
            </div>
        </div>

        <!-- Primary Navigation -->
        <nav class="sidebar-nav">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <a href="{{ route('products.index') }}"><i class="fa-solid fa-list"></i> Categories</a>
            @auth
                <a href="{{ route('user.wishlist') }}"><i class="fa-regular fa-heart"></i> Favorites</a>
                <a href="{{ route('user.orders') }}"><i class="fa-regular fa-clipboard"></i> My orders</a>
            @else
                <a href="{{ route('login') }}"><i class="fa-regular fa-heart"></i> Favorites</a>
                <a href="{{ route('login') }}"><i class="fa-regular fa-clipboard"></i> My orders</a>
            @endauth
        </nav>

        <!-- Settings Section -->
        <div class="sidebar-settings">
            <a href="javascript:void(0)"><i class="fa-solid fa-globe"></i> English | USD</a>
            <a href="{{ route('home') }}#inquiry-section"><i class="fa-solid fa-headset"></i> Contact us</a>
            <a href="{{ route('home') }}"><i class="fa-solid fa-building"></i> About</a>
        </div>

        <!-- Footer Links -->
        <div class="sidebar-footer-links">
            <a href="javascript:void(0)">User agreement</a>
            <a href="javascript:void(0)">Partnership</a>
            <a href="javascript:void(0)">Privacy policy</a>
        </div>

    </div>
    <div class="mobile-overlay" id="mobileOverlay"></div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.getElementById('hamburgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeBtn = document.getElementById('closeMenuBtn');
    const overlay = document.getElementById('mobileOverlay');

    function openMenu() {
        mobileMenu.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeMenu() {
        mobileMenu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    if(hamburger) hamburger.addEventListener('click', openMenu);
    if(closeBtn) closeBtn.addEventListener('click', closeMenu);
    if(overlay) overlay.addEventListener('click', closeMenu);

    // Sub-navbar smooth scroll hide/show with Vibration Fix
    const subNavbar = document.querySelector('.sub-navbar');
    let lastScrollY = window.scrollY;
    let ticking = false;
    const delta = 8; // Minimum scroll distance to trigger action (prevents vibration)

    function handleSubNavScroll() {
        const currentScrollY = window.scrollY;
        
        // If the distance scrolled is less than delta, don't do anything
        if (Math.abs(currentScrollY - lastScrollY) <= delta) {
            ticking = false;
            return;
        }

        if (subNavbar) {
            if (currentScrollY > lastScrollY && currentScrollY > 100) {
                // Scrolling DOWN — hide
                subNavbar.classList.add('sub-hidden');
            } else if (currentScrollY < lastScrollY) {
                // Scrolling UP — show
                subNavbar.classList.remove('sub-hidden');
            }
        }
        
        lastScrollY = currentScrollY;
        ticking = false;
    }

    window.addEventListener('scroll', function() {
        if (!ticking) {
            window.requestAnimationFrame(handleSubNavScroll);
            ticking = true;
        }
    });
});
</script>
