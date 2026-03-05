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

    <!-- Global Preloader for Auth -->
    <div id="auth-loader" style="position:fixed; inset:0; background:var(--auth-bg, #ffffff); z-index:9999; display:flex; align-items:center; justify-content:center;">
        <div style="width:40px; height:40px; border:4px solid #f3f3f3; border-top:4px solid var(--auth-primary, #4f46e5); border-radius:50%; animation:spin 1s linear infinite;"></div>
    </div>
    <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>

    <div class="auth-theme-toggle">
        <button class="theme-btn theme-toggle" title="Toggle Theme">
            <i class="fa-solid fa-moon theme-toggle-icon"></i>
        </button>
    </div>

    <main class="auth-main">
        @yield('content')
    </main>

    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('auth-loader');
            if(loader) {
                gsap.to(loader, { opacity: 0, duration: 0.5, onComplete: () => loader.remove() });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
