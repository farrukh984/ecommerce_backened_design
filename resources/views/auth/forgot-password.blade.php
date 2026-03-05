@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
<div class="split-auth-container reverse">
    {{-- RIGHT SIDE: BRAND SHOWCASE --}}
    <div class="auth-right-panel">
        <div class="panel-img-wrap">
            <img src="https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=1000" alt="Recovery Showcase">
        </div>
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <div class="showcase-content">
                <h2 class="gs-reveal">Password<br>Recovery.</h2>
                <p class="gs-reveal">Don't worry! It happens. Enter your email and we'll help you get back to your premium shopping experience.</p>
            </div>
            <p class="copyright">© 2026 ShopBrand Inc. All rights reserved.</p>
        </div>
    </div>
    
    {{-- LEFT SIDE: FORGOT FORM --}}
    <div class="auth-left">
        <div class="auth-form-wrapper">
            <div class="mobile-brand">
                <i class="fa-solid fa-bag-shopping"></i> ShopBrand.
            </div>
            
            <div class="form-header">
                <h3 class="gs-reveal">Reset Password</h3>
                <p class="gs-reveal">We'll send you a secure link to reset your password.</p>
            </div>

            @if(session('status') || session('success'))
                <div class="alert alert-success gs-reveal">
                    <i class="fa-solid fa-circle-check"></i> {{ session('status') ?? session('success') }}
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

            <form method="POST" action="{{ route('password.email') }}" id="forgot-form">
                @csrf
                
                <div class="form-group gs-reveal">
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" placeholder=" " required value="{{ old('email') }}" autofocus>
                        <span class="icon"><i class="fa-regular fa-envelope"></i></span>
                        <label for="email">Email Address</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit gs-reveal">
                    <span>Send Reset Link</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

            <div class="bottom-text gs-reveal" style="margin-top: 30px;">
                <a href="{{ route('login') }}" class="forgot-link">
                    <i class="fa-solid fa-arrow-left"></i> Back to Sign In
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                clearProps: "all"
            }
        );
    });
</script>
@endsection