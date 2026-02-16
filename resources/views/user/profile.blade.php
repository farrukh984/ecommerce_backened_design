@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'profile'])

    <main class="dashboard-main">
        <section class="profile-panel">
            <div class="profile-hero">
                <div class="profile-avatar-xl">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <h2>Profile Settings</h2>
                    <p>Update your account details and password securely.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="profile-alert success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="profile-alert error">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('user.profile.update') }}" class="profile-form">
                @csrf

                <div class="form-grid">
                    <label>
                        <span>Full Name</span>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                    </label>

                    <label>
                        <span>Email Address</span>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                    </label>
                </div>

                <div class="form-grid">
                    <label>
                        <span>Current Password</span>
                        <input type="password" name="current_password" placeholder="Enter current password">
                    </label>

                    <label>
                        <span>New Password</span>
                        <input type="password" name="new_password" placeholder="Enter new password">
                    </label>
                </div>

                <div class="form-grid">
                    <label>
                        <span>Confirm New Password</span>
                        <input type="password" name="new_password_confirmation" placeholder="Confirm new password">
                    </label>
                </div>

                <button type="submit" class="profile-submit-btn">Save Changes</button>
            </form>
        </section>
    </main>
</div>
@endsection
