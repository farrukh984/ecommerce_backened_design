@extends('layouts.auth')
@section('title', 'Admin OTP Verification')
@section('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection
@section('content')
<div class="split-auth-container">
    <div class="auth-left">
        <div class="brand-showcase">
            <h1 class="brand-logo"><i class="fa-solid fa-bag-shopping"></i> ShopBrand.</h1>
            <p>Please check your admin email for the OTP code.</p>
        </div>
    </div>
    <div class="auth-right">
        <div class="auth-form-wrapper">
            <div class="form-header">
                <h3>Enter OTP</h3>
                <p>We've sent a 6-digit code to your admin email.</p>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('admin.otp.verify') }}">
                @csrf
                <div class="form-group">
                    <div class="input-wrapper">
                        <input type="text" name="otp" id="otp" placeholder=" " required maxlength="6">
                        <label for="otp">6-digit OTP</label>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Verify OTP</button>
            </form>
            <div class="bottom-text">
                <a href="{{ route('login') }}">Back to login</a>
            </div>
        </div>
    </div>
</div>
@endsection
