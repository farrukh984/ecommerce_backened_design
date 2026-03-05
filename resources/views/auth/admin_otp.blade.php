@extends('layouts.auth')

@section('title', 'Admin OTP Verification')

@section('styles')
    {{-- Custom styles for OTP input specifically if needed --}}
@endsection

@section('content')
<div class="split-auth-container">
    <!-- LEFT FORM PANEL -->
    <div class="auth-left">
        <div class="auth-form-wrapper">
            <div class="mobile-brand">
                <i class="fa-solid fa-bag-shopping"></i> ShopBrand.
            </div>

            <div class="form-header">
                <h3>Enter OTP</h3>
                <p>Verify your identity to access the administrative dashboard.</p>
            </div>

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

            <form method="POST" action="{{ route('admin.otp.verify') }}">
                @csrf

                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="text" name="otp" id="otp" placeholder=" " required maxlength="6" inputmode="numeric" pattern="\d*">
                        <span class="icon"><i class="fa-solid fa-shield-halved"></i></span>
                        <label for="otp">Security Code</label>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <p style="font-size: 13px; color: var(--auth-text-sub); line-height: 1.5;">
                        <i class="fa-solid fa-info-circle"></i> We've sent a 6-digit code to your admin email. Please check your inbox and spam folder.
                    </p>
                </div>

                <button type="submit" class="btn-submit">
                    Verify & Continue <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="bottom-text">
                <a href="{{ route('login') }}">← Back to login</a>
            </div>
        </div>
    </div>

    <!-- RIGHT BRAND PANEL -->
    <div class="auth-right-panel">
        <div class="panel-img-wrap">
            <img src="{{ asset('images/auth/admin_otp.png') }}" alt="Security Verification">
        </div>
        
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <div class="showcase-content">
                <h2>Secure Admin Portal</h2>
                <p>Our Multi-Factor Authentication (MFA) ensures that only authorized personnel can access sensitive store management functions.</p>
            </div>
            <p class="copyright">© 2026 ShopBrand Inc. All rights reserved.</p>
        </div>
    </div>
</div>
@endsection