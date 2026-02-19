@extends('layouts.auth')

@section('title', 'Register')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="split-auth-container">
    <div class="auth-left">
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <div class="showcase-content">
                <h2>Begin Your Journey.</h2>
                <p>Join millions of shoppers and experience the future of online retail today.</p>
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
                <h3>Create Account</h3>
                <p>It's free and only takes a minute.</p>
            </div>

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf
                
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" placeholder=" " required>
                        <span class="icon"><i class="fa-regular fa-user"></i></span>
                        <label for="name">Full Name</label>
                    </div>
                </div>

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

                <button type="submit" class="btn-submit" style="margin-top: 10px;">
                    Get Started <i class="fa-solid fa-arrow-right"></i>
                </button>

                <div class="divider">
                    <span>Or sign up with</span>
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
                Already have an account? <a href="{{ route('login') }}" style="color: #6366f1;">Sign In</a>
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
