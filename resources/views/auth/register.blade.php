@extends('layouts.auth')

@section('title', 'Create Account')
@section('showcase-image', asset('images/auth/watch-collection.png'))
@section('showcase-alt', 'Luxury Watch Collection')
@section('showcase-badge', 'JOIN THE COMMUNITY')
@section('showcase-title')
Start Your <span>Journey</span> Today
@endsection
@section('showcase-desc', 'Create your account and get access to exclusive deals, early launches, and premium member benefits.')
@section('showcase-stats')
<div class="showcase-stat">
    <div class="showcase-stat-value">10K<span>+</span></div>
    <div class="showcase-stat-label">Members</div>
</div>
<div class="showcase-stat">
    <div class="showcase-stat-value">100<span>%</span></div>
    <div class="showcase-stat-label">Authentic</div>
</div>
<div class="showcase-stat">
    <div class="showcase-stat-value">Free<span></span></div>
    <div class="showcase-stat-label">Shipping</div>
</div>
@endsection

@section('content')
<div class="auth-card">
    <!-- Branding -->
    <div class="auth-brand">
        <span class="auth-brand-icon"><i class="fa-solid fa-gem"></i></span>
        <div class="auth-brand-name">{{ config('app.name') }}</div>
    </div>

    <!-- Header -->
    <div class="form-header">
        <div class="form-header-label gs-reveal">GET STARTED</div>
        <h3 class="gs-reveal">Create Your Account</h3>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('register.store') }}" id="register-form">
        @csrf
        <div class="form-group gs-reveal">
            <div class="input-wrapper">
                <span class="icon"><i class="fa-regular fa-user"></i></span>
                <input type="text" name="name" id="name" placeholder=" " required value="{{ old('name') }}">
                <label for="name">Full Name</label>
            </div>
        </div>

        <div class="form-group gs-reveal">
            <div class="input-wrapper">
                <span class="icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" name="email" id="email" placeholder=" " required value="{{ old('email') }}">
                <label for="email">Email Address</label>
            </div>
        </div>

        <div class="form-group gs-reveal">
            <div class="input-wrapper">
                <span class="icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" id="password" placeholder=" " required>
                <label for="password">Password</label>
                <i class="fa-regular fa-eye toggle-password" onclick="togglePass('password', this)"></i>
            </div>
        </div>

        <div class="form-group gs-reveal">
            <div class="input-wrapper">
                <span class="icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" " required>
                <label for="password_confirmation">Confirm Password</label>
                <i class="fa-regular fa-eye toggle-password" onclick="togglePass('password_confirmation', this)"></i>
            </div>
        </div>

        <button type="submit" class="btn-submit gs-reveal" style="margin-top: 4px;">
            <span>CREATE ACCOUNT</span>
            <i class="fa-solid fa-arrow-right"></i>
        </button>

        <div class="divider gs-reveal"><span>OR</span></div>

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
        Already have an account? <a href="{{ route('login') }}" class="flip-trigger">Sign In</a>
    </div>

    <div class="security-badge gs-reveal">
        <i class="fa-solid fa-shield-halved"></i>
        <span>Your data is safe & secure</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePass(id, el) {
        const i = document.getElementById(id);
        if (i.type === 'password') { i.type = 'text'; el.classList.replace('fa-eye', 'fa-eye-slash'); }
        else { i.type = 'password'; el.classList.replace('fa-eye-slash', 'fa-eye'); }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const dk = document.documentElement.getAttribute('data-theme') !== 'light';
        @if(session('success'))
            Swal.fire({ icon:'success', title:'Success!', text:"{{ session('success') }}", timer:4000, showConfirmButton:false,
                background: dk?'#0f172a':'#fff', color: dk?'#e2e8f0':'#0f172a', iconColor:'#06b6d4' });
        @endif
        @if($errors->any())
            Swal.fire({ icon:'error', title:'Error!', text:"{{ $errors->first() }}",
                background: dk?'#0f172a':'#fff', color: dk?'#e2e8f0':'#0f172a' });
        @endif
    });
</script>
@endsection
