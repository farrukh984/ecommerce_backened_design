@extends('layouts.auth')

@section('title', 'Login')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="split-auth-container">
    <div class="auth-left">
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <div class="showcase-content">
                <h2>Welcome Back!</h2>
                <p>Discover the latest premium trends and exclusive deals tailored just for you.</p>
                <div class="visual-art">
                    <div class="circle c1"></div>
                    <div class="circle c2"></div>
                    <div class="circle c3"></div>
                </div>
            </div>
            <p class="copyright">© 2026 ShopBrand Inc. All rights reserved.</p>
        </div>
    </div>
    
    <div class="auth-right">
        <div class="auth-form-wrapper">
            <div class="mobile-brand">
                <i class="fa-solid fa-bag-shopping"></i> ShopBrand.
            </div>
            
            <div class="form-header">
                <h3>Sign In</h3>
                <p>Access your dashboard using your email and password.</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" placeholder=" " required>
                        <span class="icon"><i class="fa-regular fa-envelope"></i></span>
                        <label for="email">Email Address</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" placeholder=" " required>
                        <span class="icon"><i class="fa-solid fa-lock"></i></span>
                        <label for="password">Password</label>
                        <i class="fa-regular fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="form-actions">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-submit">
                    Login securely <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>

                <div class="divider">
                    <span>Or continue with</span>
                </div>

                <div class="social-buttons">
                    <a href="{{ route('google.redirect') }}" class="btn-social google">
                        <i class="fa-brands fa-google"></i> Google
                    </a>
                    <button type="button" class="btn-social facebook">
                        <i class="fa-brands fa-facebook-f"></i> Facebook
                    </button>
                </div>
            </form>

            <div class="bottom-text">
                New to ShopBrand? <a href="{{ route('register') }}">Create an account</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('toggle-password')) {
            const wrapper = e.target.closest('.input-wrapper');
            const input = wrapper.querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                e.target.classList.remove('fa-eye');
                e.target.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                e.target.classList.remove('fa-eye-slash');
                e.target.classList.add('fa-eye');
            }
        }
    });
</script>
@endsection

