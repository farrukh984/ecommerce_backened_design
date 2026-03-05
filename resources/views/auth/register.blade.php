@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="split-auth-container">
    <div class="auth-left">
        <div class="visual-art">
            <div class="circle c1"></div>
            <div class="circle c2"></div>
        </div>
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <div class="showcase-content">
                <h2 class="gs-reveal">Join the Future.</h2>
                <p class="gs-reveal">Create your account today and experience the next generation of premium online shopping.</p>
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
                <h3 class="gs-reveal">Create Account</h3>
                <p class="gs-reveal">Securely join our premium community.</p>
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
                        <i class="fa-regular fa-eye toggle-password"></i>
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
                        <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_Logo.svg" alt="Google" width="18"> Google
                    </a>
                    <a href="{{ route('facebook.redirect') }}" class="btn-social">
                        <i class="fa-brands fa-facebook" style="color: #1877f2; font-size: 20px;"></i> Facebook
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
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Password
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const input = document.getElementById('password');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });

        // GSAP Animations
        gsap.from(".split-auth-container", {
            opacity: 0,
            y: 30,
            duration: 1,
            ease: "expo.out"
        });

        gsap.from(".gs-reveal", {
            opacity: 0,
            y: 20,
            stagger: 0.1,
            duration: 0.8,
            ease: "power2.out",
            delay: 0.3
        });

        // Interactive button effect
        const btn = document.querySelector('.btn-submit');
        btn.addEventListener('mouseenter', () => {
            gsap.to(btn.querySelector('i'), { x: 5, duration: 0.3 });
        });
        btn.addEventListener('mouseleave', () => {
            gsap.to(btn.querySelector('i'), { x: 0, duration: 0.3 });
        });
    });
</script>
@endsection
