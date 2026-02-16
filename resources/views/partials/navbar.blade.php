<header class="top-header">
    <div class="container header-flex">

        <!-- Mobile: hamburger left -->
        <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div class="logo">
            <span class="logo-icon"><i class="fa-solid fa-lock"></i></span>
            <span class="brand-name">Brand</span>
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
            <a href="#" class="icon-item" id="profileTrigger">
                <i class="fa-regular fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="#" class="icon-item" id="messageTrigger">
                <i class="fa-regular fa-comment-dots"></i>
                <span>Message</span>
            </a>
            <a href="#" class="icon-item" id="ordersTrigger">
                <i class="fa-regular fa-heart"></i>
                <span>Orders</span>
            </a>
            <a href="#" class="icon-item" id="cartTrigger">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>My cart</span>
            </a>
        </div>

        <!-- Mobile: cart + user icons (visible only on mobile) -->
        <div class="header-icons-mobile">
            <a href="#" id="mCartTrigger"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="#" id="mProfileTrigger"><i class="fa-regular fa-user"></i></a>
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
            <a href="#" class="cat-tab active">All category</a>
            <a href="#" class="cat-tab">Gadgets</a>
            <a href="#" class="cat-tab">Clothes</a>
            <a href="#" class="cat-tab">Accessories</a>
            <a href="#" class="cat-tab">Electronics</a>
            <a href="#" class="cat-tab">Sports</a>
            <a href="#" class="cat-tab">Home</a>
        </div>
    </div>

    <nav class="sub-navbar">
        <div class="container sub-flex">
            <div class="menu-links">
                <a href="#"><i class="fa-solid fa-bars"></i> All category</a>
                <a href="#">Hot offers</a>
                <a href="#">Gift boxes</a>
                <a href="#">Projects</a>
                <a href="#">Menu item</a>
                <a href="#">Help <i class="fa-solid fa-chevron-down"></i></a>
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
            <a href="/home"><i class="fa-solid fa-house"></i> Home</a>
            <a href="#"><i class="fa-solid fa-list"></i> Categories</a>
            <a href="#"><i class="fa-regular fa-heart"></i> Favorites</a>
            <a href="#"><i class="fa-regular fa-clipboard"></i> My orders</a>
        </nav>

        <!-- Settings Section -->
        <div class="sidebar-settings">
            <a href="#"><i class="fa-solid fa-globe"></i> English | USD</a>
            <a href="#"><i class="fa-solid fa-headset"></i> Contact us</a>
            <a href="#"><i class="fa-solid fa-building"></i> About</a>
        </div>

        <!-- Footer Links -->
        <div class="sidebar-footer-links">
            <a href="#">User agreement</a>
            <a href="#">Partnership</a>
            <a href="#">Privacy policy</a>
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
});
</script>
