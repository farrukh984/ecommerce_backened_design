@extends('layouts.auth')

@section('title', 'Admin OTP Verification')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="split-auth-container">
    <!-- LEFT BRAND PANEL -->
    <div class="auth-left">
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <div class="showcase-content">
                <h2>Verify Your Identity</h2>
                <p>Please check your admin email for the 6‑digit OTP code. It expires in 10 minutes.</p>
            </div>
            <!-- Decorative animated circles -->
            <div class="visual-art">
                <div class="circle c1"></div>
                <div class="circle c2"></div>
                <div class="circle c3"></div>
            </div>
            <p class="copyright">© 2026 ShopBrand Inc. All rights reserved.</p>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-right">
        <div class="auth-form-wrapper">
            <div class="mobile-brand">
                <i class="fa-solid fa-bag-shopping"></i> ShopBrand.
            </div>

            <div class="form-header">
                <h3>Enter OTP</h3>
                <p>We've sent a 6‑digit code to your admin email.</p>
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
                        <label for="otp">6‑digit OTP</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Verify OTP <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="bottom-text">
                <a href="{{ route('login') }}">← Back to login</a>
            </div>
        </div>
    </div>
</div>
@endsection