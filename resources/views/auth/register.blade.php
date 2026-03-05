@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="split-auth-container reverse">
    {{-- RIGHT SIDE: BRAND SHOWCASE --}}
    <div class="auth-right-panel">
        <div class="panel-img-wrap">
            <img src="{{ asset('images/auth-showcase.png') }}" onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=1000'" alt="Showcase">
        </div>
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <div class="showcase-content">
                <h2 class="gs-reveal">Join the Future<br>of Retail.</h2>
                <p class="gs-reveal">Be part of something extraordinary. Create your account and unlock a world of premium commerce.</p>
            </div>
            <p class="copyright">© 2026 ShopBrand Inc. All rights reserved.</p>
        </div>
    </div>
    
    {{-- LEFT SIDE: REGISTER FORM --}}
    <div class="auth-left">
        <div class="auth-form-wrapper">
            <div class="mobile-brand">
                <i class="fa-solid fa-bag-shopping"></i> ShopBrand.
            </div>
            
            <div class="form-header">
                <h3 class="gs-reveal">Create Account</h3>
                <p class="gs-reveal">Join our exclusive premium network today.</p>
            </div>

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="alert alert-danger gs-reveal">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.store') }}" id="register-form">
                @csrf
                
                <div class="form-group gs-reveal">
                    <div class="input-wrapper">
                        <input type="text" name="name" id="name" placeholder=" " required value="{{ old('name') }}">
                        <span class="icon"><i class="fa-regular fa-user"></i></span>
                        <label for="name">Full Name</label>
                    </div>
                </div>

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

                <div class="form-group gs-reveal">
                    <div class="input-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" " required>
                        <span class="icon"><i class="fa-solid fa-shield-check"></i></span>
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit gs-reveal" style="margin-top: 10px;">
                    <span>Create Account</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

                <div class="divider gs-reveal">
                    <span>Or sign up with</span>
                </div>

                <div class="social-buttons gs-reveal">
                    <a href="{{ route('google.redirect') }}" class="btn-social">
                        <img src="https://www.gstatic.com/images/branding/product/2x/googleg_48dp.png" alt="Google" width="20"> 
                        <span>Google</span>
                    </a>
                    <a href="{{ route('facebook.redirect') }}" class="btn-social">
                        <i class="fa-brands fa-facebook" style="color: #1877f2; font-size: 20px;"></i> 
                        <span>Facebook</span>
                    </a>
                </div>
            </form>

            <div class="bottom-text gs-reveal">
                Already have an account? <a href="{{ route('login') }}">Sign In</a>
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
        gsap.from(".split-auth-container", {
            opacity: 0,
            y: 40,
            duration: 1.2,
            ease: "expo.out"
        });

        gsap.from(".gs-reveal", {
            opacity: 0,
            y: 20,
            stagger: 0.08,
            duration: 0.8,
            ease: "power2.out",
            delay: 0.4
        });

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
