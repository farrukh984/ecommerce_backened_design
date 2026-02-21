@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'profile'])

    <main class="dashboard-main">
        <section class="profile-panel" style="padding: 0; overflow: hidden;">
            <!-- Modern Profile Header with Cover Image -->
            <div class="profile-header-premium" style="position: relative; height: 200px; background: #f1f5f9;">
                <div class="cover-image-preview" style="width: 100%; height: 100%;">
                    @if(auth()->user()->cover_image)
                        <img src="{{ asset('storage/' . auth()->user()->cover_image) }}" id="coverPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div id="coverPreviewImg" style="width: 100%; height: 100%; background: linear-gradient(135deg, #0ea5e9, #6366f1); opacity: 0.8;"></div>
                    @endif
                </div>
                
                <label for="userCoverImage" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.9); padding: 8px 16px; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-camera"></i> Change Cover
                </label>
                <input type="file" name="cover_image" id="userCoverImage" accept="image/*" style="display: none;" onchange="previewCoverImage(this)" form="userProfileForm">

                <!-- Avatar Floating -->
                <div style="position: absolute; bottom: -40px; left: 30px; display: flex; align-items: flex-end; gap: 20px;">
                    <div class="profile-image-container" id="userProfileImgContainer" style="width: 120px; height: 120px; border-radius: 20px; border: 5px solid white; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                        @if(auth()->user()->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" id="userPreviewImg" class="profile-img-preview" style="border-radius: 15px;">
                        @else
                            <div id="userPreviewImg" class="profile-img-placeholder" style="border-radius: 15px;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <label for="userProfileImage" class="profile-img-overlay" style="border-radius: 15px;">
                            <i class="fa-solid fa-pen"></i>
                        </label>
                        <input type="file" name="profile_image" id="userProfileImage" accept="image/*" style="display: none;" onchange="previewUserImage(this)" form="userProfileForm">
                    </div>
                </div>
            </div>

            <div style="padding: 60px 30px 30px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 30px;">
                    <div>
                        <h2 style="font-family: 'Outfit', sans-serif; font-size: 24px; margin-bottom: 5px;">{{ auth()->user()->name }}</h2>
                        <p style="color: #64748b; font-size: 14px;">Update your account personal details and settings.</p>
                    </div>
                    <!-- Profile strength inside panel -->
                    <div style="width: 200px;">
                        <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; font-weight: 600;">
                            <span>Profile Strength</span>
                            <span style="color: var(--primary);">{{ auth()->user()->profile_completion }}%</span>
                        </div>
                        <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                            <div style="width: {{ auth()->user()->profile_completion }}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #06b6d4); border-radius: 3px;"></div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="profile-alert success" style="margin-bottom: 20px;">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="profile-alert error" style="margin-bottom: 20px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('user.profile.update') }}" class="profile-form" enctype="multipart/form-data" id="userProfileForm">
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
                        <span>Phone Number</span>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="e.g. +92 300 1234567">
                    </label>
                    <label>
                        <span>Country</span>
                        <input type="text" name="country" value="{{ old('country', auth()->user()->country ?? 'Pakistan') }}">
                    </label>
                </div>

                <div class="form-grid" style="grid-template-columns: 1fr;">
                    <label>
                        <span>Full Address</span>
                        <input type="text" name="address" value="{{ old('address', auth()->user()->address) }}" placeholder="Street address, apartment, suite, etc.">
                    </label>
                </div>

                <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr;">
                    <label>
                        <span>City</span>
                        <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}">
                    </label>
                    <label>
                        <span>State / Province</span>
                        <input type="text" name="state" value="{{ old('state', auth()->user()->state) }}">
                    </label>
                    <label>
                        <span>Zip Code</span>
                        <input type="text" name="zip_code" value="{{ old('zip_code', auth()->user()->zip_code) }}">
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

@section('scripts')
<script>
    function previewCoverImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('coverPreviewImg').outerHTML = '<img src="' + e.target.result + '" id="coverPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewUserImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Update sidebar preview
                const container = document.getElementById('userProfileImgContainer');
                const existingImg = container.querySelector('.profile-img-preview');
                const existingPlaceholder = container.querySelector('.profile-img-placeholder');
                
                if (existingImg) {
                    existingImg.src = e.target.result;
                } else if (existingPlaceholder) {
                    existingPlaceholder.outerHTML = '<img src="' + e.target.result + '" id="userPreviewImg" class="profile-img-preview" style="border-radius: 15px;">';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
