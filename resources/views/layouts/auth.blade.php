<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Authentication') - Brand</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth-premium.css') }}?v={{ time() }}">
    
    <!-- GSAP for Premium Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <!-- Theme JS -->
    <script src="{{ asset('js/theme.js') }}"></script>
    
    @yield('styles')
</head>
<body class="auth-body">

    <!-- Premium Preloader -->
    <div id="auth-loader">
        <div class="loader-boxes">
            <div class="l-box"></div>
            <div class="l-box"></div>
            <div class="l-box"></div>
            <div class="l-box"></div>
        </div>
        <div class="loader-text">ShopBrand</div>
    </div>

    <div class="auth-theme-toggle">
        <button class="theme-btn theme-toggle" title="Toggle Theme">
            <i class="fa-solid fa-moon theme-toggle-icon"></i>
        </button>
    </div>

    <main class="auth-main">
        @yield('content')
    </main>

    <script>
        // Hide loader on load
        window.addEventListener('load', function() {
            const loader = document.getElementById('auth-loader');
            if(loader) {
                loader.classList.add('hide');
                setTimeout(() => loader.remove(), 600);
            }
        });

        // Show on form submit
        document.addEventListener('submit', function(e) {
            if (e.defaultPrevented) return;
            const loader = document.getElementById('auth-loader');
            if(!loader) {
                // Re-create if already removed
                const newLoader = document.createElement('div');
                newLoader.id = 'auth-loader';
                newLoader.innerHTML = `
                    <div class="loader-boxes">
                        <div class="l-box"></div>
                        <div class="l-box"></div>
                        <div class="l-box"></div>
                        <div class="l-box"></div>
                    </div>
                    <div class="loader-text">ShopBrand</div>
                `;
                document.body.appendChild(newLoader);
            } else {
                loader.classList.remove('hide');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
