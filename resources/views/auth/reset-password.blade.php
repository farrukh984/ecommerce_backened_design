@extends('layouts.auth')

@section('title', 'Reset Password')
@section('showcase-image', asset('images/auth/security-shield.png'))
@section('showcase-alt', 'Secure Password Reset')
@section('showcase-badge', 'SECURE RESET')
@section('showcase-title')
Set a <span>Strong</span> Password
@endsection
@section('showcase-desc', 'Choose a secure password that keeps your account protected. We use industry-leading encryption.')

@section('content')
<div class="auth-card">
    <!-- Branding -->
    <div class="auth-brand">
        <span class="auth-brand-icon"><i class="fa-solid fa-gem"></i></span>
        <div class="auth-brand-name">{{ config('app.name') }}</div>
    </div>

    <!-- Header -->
    <div class="form-header">
        <div class="form-header-label gs-reveal">SECURE RESET</div>
        <h3 class="gs-reveal">Set New Password</h3>
        <p class="gs-reveal">Choose a strong password for your account</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger gs-reveal">
            <ul style="list-style:none;padding:0;margin:0;">
                @foreach ($errors->all() as $error)<li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="form-group gs-reveal">
            <div class="input-wrapper">
                <span class="icon"><i class="fa-regular fa-envelope"></i></span>
                <input type="email" name="email" id="email" placeholder=" " value="{{ request()->email ?? old('email') }}" required autofocus>
                <label for="email">Email Address</label>
            </div>
        </div>

        <div class="form-group gs-reveal">
            <div class="input-wrapper">
                <span class="icon"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" id="password" placeholder=" " required>
                <label for="password">New Password</label>
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
            <span>RESET PASSWORD</span>
            <i class="fa-solid fa-key"></i>
        </button>
    </form>

    <div class="bottom-text gs-reveal" style="margin-top: 22px;">
        <a href="{{ route('login') }}"><i class="fa-solid fa-arrow-left"></i> Back to Sign In</a>
    </div>

    <div class="security-badge gs-reveal">
        <i class="fa-solid fa-shield-halved"></i>
        <span>End-to-end encrypted</span>
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
</script>
@endsection
