@extends('layouts.auth')

@section('title', 'Admin OTP Verification')
@section('showcase-image', asset('images/auth/security-shield.png'))
@section('showcase-alt', 'Admin Security Verification')
@section('showcase-badge', 'ADMIN ACCESS')
@section('showcase-title')
Secure <span>Admin</span> Portal
@endsection
@section('showcase-desc', 'Multi-factor authentication ensures only authorized administrators can access the dashboard.')
@section('showcase-stats')
<div class="showcase-stat">
    <div class="showcase-stat-value">MFA<span></span></div>
    <div class="showcase-stat-label">Protected</div>
</div>
<div class="showcase-stat">
    <div class="showcase-stat-value">E2E<span></span></div>
    <div class="showcase-stat-label">Encrypted</div>
</div>
<div class="showcase-stat">
    <div class="showcase-stat-value">0<span></span></div>
    <div class="showcase-stat-label">Breaches</div>
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
        <div class="form-header-label gs-reveal">ADMIN ACCESS</div>
        <h3 class="gs-reveal">Enter OTP Code</h3>
        <p class="gs-reveal">Verify your identity to access admin dashboard</p>
    </div>

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

    <form method="POST" action="{{ route('admin.otp.verify') }}">
        @csrf
        <div class="form-group gs-reveal">
            <div class="input-wrapper">
                <span class="icon"><i class="fa-solid fa-shield-halved"></i></span>
                <input type="text" name="otp" id="otp" placeholder=" " required maxlength="6" inputmode="numeric" pattern="\d*">
                <label for="otp">Security Code</label>
            </div>
        </div>

        <div class="gs-reveal" style="margin-bottom: 20px;">
            <p style="font-size: 12px; color: var(--a-text-2); line-height: 1.6;">
                <i class="fa-solid fa-info-circle" style="color: var(--a-accent);"></i> 
                We've sent a 6-digit code to your admin email. Check your inbox and spam folder.
            </p>
        </div>

        <button type="submit" class="btn-submit gs-reveal">
            <span>VERIFY & CONTINUE</span>
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
        </button>
    </form>

    <div class="bottom-text gs-reveal" style="margin-top: 24px;">
        <a href="{{ route('login') }}"><i class="fa-solid fa-arrow-left"></i> Back to login</a>
    </div>

    <div class="security-badge gs-reveal">
        <i class="fa-solid fa-shield-halved"></i>
        <span>Multi-factor authentication</span>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Layout handles all GSAP animations
</script>
@endsection