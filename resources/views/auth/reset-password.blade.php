@extends('layouts.auth')

@section('title', 'Reset Password')

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
                <h2>Set New<br>Password</h2>
                <p>Ensure your account is secure with a strong password. Enter your new credentials below.</p>
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
                <h3>New Password</h3>
                <p>Please enter your email and choose a new password.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="email" name="email" id="email" placeholder=" " value="{{ request()->email ?? old('email') }}" required autofocus>
                        <span class="icon"><i class="fa-regular fa-envelope"></i></span>
                        <label for="email">Email Address</label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" placeholder=" " required>
                        <span class="icon"><i class="fa-solid fa-lock"></i></span>
                        <label for="password">New Password</label>
                        <i class="fa-regular fa-eye toggle-password"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder=" " required>
                        <span class="icon"><i class="fa-solid fa-lock-check"></i></span>
                        <label for="password_confirmation">Confirm Password</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Reset Password <i class="fa-solid fa-key"></i>
                </button>
            </form>

            <div class="bottom-text">
                <a href="{{ route('login') }}">← Back to login</a>
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
