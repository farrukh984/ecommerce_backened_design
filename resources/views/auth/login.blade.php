@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="split-auth-container reverse">
    {{-- RIGHT SIDE: BRAND SHOWCASE WITH IMAGE --}}
    <div class="auth-right-panel">
        <div class="panel-img-wrap">
            <img src="https://images.unsplash.com/photo-1607083206968-13611e3d76db?auto=format&fit=crop&q=80&w=1000" alt="Login Showcase">
        </div>
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <div class="showcase-content">
                <h2 class="gs-reveal">Elevate Your<br>Shopping.</h2>
                <p class="gs-reveal">Join our exclusive community and access premium products with institutional security and breathtaking design.</p>
            </div>
            <p class="copyright">© 2026 ShopBrand Inc. All rights reserved.</p>
        </div>
    </div>
    
    {{-- LEFT SIDE: LOGIN FORM --}}
    <div class="auth-left">
        <div class="auth-form-wrapper">
            <div class="mobile-brand">
                <i class="fa-solid fa-bag-shopping"></i> ShopBrand.
            </div>
            
            <div class="form-header">
                <h3 class="gs-reveal">Sign In</h3>
                <p class="gs-reveal">Welcome back! Please enter your details.</p>
            </div>

            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success gs-reveal">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger gs-reveal">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" id="login-form">
                @csrf
                
                <div class="form-group gs-reveal">
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" placeholder=" " required value="{{ old('email') }}">
                        <span class="icon"><i class="fa-regular fa-envelope"></i></span>
                        <label for="email">Email Address</label>
                    </div>
                </div>

                <div class="form-group gs-reveal">
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" placeholder=" " required>
                        <span class="icon"><i class="fa-solid fa-lock"></i></span>
                        <label for="password">Password</label>
                        <i class="fa-regular fa-eye toggle-password" onclick="togglePass('password', this)"></i>
                    </div>
                </div>

                <div class="form-actions gs-reveal">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-submit gs-reveal">
                    <span>Login Securely</span>
                    <i class="fa-solid fa-shield-check"></i>
                </button>

                <div class="divider gs-reveal">
                    <span>Or continue with</span>
                </div>

                <div class="social-buttons gs-reveal">
                    <a href="{{ route('google.redirect') }}" class="btn-social">
                        <img src="https://www.gstatic.com/images/branding/product/2x/googleg_48dp.png" alt="Google"> 
                        <span>Google</span>
                    </a>
                    <a href="{{ route('facebook.redirect') }}" class="btn-social">
                        <i class="fa-brands fa-facebook" style="color: #1877f2;"></i> 
                        <span>Facebook</span>
                    </a>
                </div>
            </form>

            <div class="bottom-text gs-reveal">
                Don't have an account? <a href="{{ route('register') }}">Create Account</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePass(id, el) {
        const input = document.getElementById(id);
        if (input.type === 'password') {
            input.type = 'text';
            el.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            el.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // GSAP Animations
        gsap.from(".split-auth-container", {
            opacity: 0,
            y: 40,
            duration: 1.2,
            ease: "expo.out"
        });

        gsap.fromTo(".gs-reveal", 
            { opacity: 0, y: 30 },
            { 
                opacity: 1, 
                y: 0, 
                stagger: 0.1, 
                duration: 0.8, 
                ease: "power3.out", 
                delay: 0.5,
                clearProps: "all" // Important: clears inline styles after animation
            }
        );

        gsap.from(".auth-right-panel", {
            x: 50,
            opacity: 0,
            duration: 1.2,
            ease: "expo.out",
            delay: 0.2
        });
    });
</script>
@endsection
