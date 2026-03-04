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
</head>
<body class="@yield('body_class')">

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
        <script src="{{ asset('js/sidebars.js') }}"></script>
    @endif

    <!-- GSAP for Smooth Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

    @yield('scripts')

</body>
</html>
