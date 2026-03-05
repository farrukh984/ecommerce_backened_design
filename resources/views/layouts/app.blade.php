<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brand - Ecommerce</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Brand Ecommerce - Shop the latest trending electronic items, deals and offers">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS (order matters) -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @yield('styles')

    <!-- Theme JS (runs before body to prevent flash) -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Loader CSS -->
    <style>
        /* ══════════════════════════════════════════════════════════
           GLOBAL PAGE LOADER (PREMIUM)
           ══════════════════════════════════════════════════════════ */
        #global-loader {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 999999;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.6s;
        }
        [data-theme="dark"] #global-loader {
            background: #0f172a; /* MATCH var(--bg-body) */
        }
        
        .loader-boxes {
            position: relative;
            width: 60px; height: 60px;
        }
        .loader-boxes .l-box {
            position: absolute;
            width: 24px; height: 24px;
            background: linear-gradient(135deg, #0ea5e9, #4f46e5);
            border-radius: 6px;
            animation: l-box-move 1.5s infinite ease-in-out;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
        }
        .loader-boxes .l-box:nth-child(1) { top: 0; left: 0; animation-delay: 0s; }
        .loader-boxes .l-box:nth-child(2) { top: 0; right: 0; animation-delay: -0.375s; }
        .loader-boxes .l-box:nth-child(3) { bottom: 0; right: 0; animation-delay: -0.75s; }
        .loader-boxes .l-box:nth-child(4) { bottom: 0; left: 0; animation-delay: -1.125s; }

        @keyframes l-box-move {
            0%, 100% { transform: scale(1) rotate(0deg); opacity:1; }
            50% { transform: scale(0.5) rotate(90deg); opacity:0.5; }
        }

        .loader-text {
            margin-top: 24px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            letter-spacing: 3px;
            text-transform: uppercase;
            animation: loader-pulse 1.5s ease-in-out infinite;
            font-family: 'Outfit', sans-serif;
        }
        [data-theme="dark"] .loader-text {
            color: #94a3b8;
        }
        @keyframes loader-pulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }
        body.is-loading {
            overflow: hidden !important;
        }
        #global-loader.hide {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    </style>
</head>
<body class="@yield('body_class') is-loading">

    <!-- Global Preloader -->
    <div id="global-loader">
        <div class="loader-boxes">
            <div class="l-box"></div>
            <div class="l-box"></div>
            <div class="l-box"></div>
            <div class="l-box"></div>
        </div>
        <div class="loader-text">ShopBrand</div>
    </div>

    <script>
        // Once everything is loaded (CSS, Images, etc.), hide the loader
        window.addEventListener('load', function() {
            const loader = document.getElementById('global-loader');
            setTimeout(() => {
                loader.classList.add('hide');
                document.body.classList.remove('is-loading');
            }, 350);
        });

        // ──────── IMMEDIATE TRANSITION LOGIC ────────
        // Show loader IMMEDIATELY when a link is clicked or form submitted
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && 
                link.href && 
                !link.href.startsWith('#') && 
                !link.href.includes('javascript:') &&
                !link.getAttribute('target') && 
                !e.ctrlKey && !e.shiftKey && !e.metaKey &&
                link.hostname === window.location.hostname) {
                
                const loader = document.getElementById('global-loader');
                if(loader) {
                    loader.classList.remove('hide');
                    document.body.classList.add('is-loading');
                }
            }
        });

        // Show on form submit
        document.addEventListener('submit', function(e) {
            // Stay hidden if standard submission is prevented (e.g., AJAX handling)
            if (e.defaultPrevented) return;

            const loader = document.getElementById('global-loader');
            if(loader) {
                loader.classList.remove('hide');
                document.body.classList.add('is-loading');
            }
        });

        // Hide when navigating back (BFcache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                const loader = document.getElementById('global-loader');
                if(loader) {
                    loader.classList.add('hide');
                    document.body.classList.remove('is-loading');
                }
            }
        });
    </script>

    @hasSection('hide_chrome')
    @else
        @include('partials.navbar')
    @endif

    <main>
        @yield('content')
    </main>

    @hasSection('hide_chrome')
    @else
        @include('partials.footer')
        @include('partials.sidebars')
    @endif

    @hasSection('hide_chrome')
    @else
        <script src="{{ asset('js/sidebars.js') }}" defer></script>
    @endif

    <!-- GSAP for Smooth Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>

    @yield('scripts')

</body>
</html>
