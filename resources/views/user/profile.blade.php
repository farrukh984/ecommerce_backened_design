@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('css/user-profile.css') }}?v={{ time() }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'profile'])

    <main class="dashboard-main">
        <section class="profile-panel">
            <!-- Modern Profile Header with Cover Image -->
            <div class="profile-header-premium">
                <div class="cover-image-preview">
                    @if(auth()->user()->cover_image)
                        <img src="{{ display_image(auth()->user()->cover_image) }}" id="coverPreviewImg">
                    @else
                        <div class="cover-placeholder" id="coverPreviewImg"></div>
                    @endif
                </div>
                
                <label for="userCoverImage" class="btn-update-cover">
                    <i class="fa-solid fa-camera"></i> Update Cover
                </label>
                <input type="file" name="cover_image" id="userCoverImage" accept="image/*" style="display: none;" onchange="previewCoverImage(this)" form="userProfileForm">

                <!-- Avatar Floating -->
                <div class="profile-avatar-floating">
                    <div class="profile-image-container" id="userProfileImgContainer">
                        @if(auth()->user()->profile_image)
                            <img src="{{ display_image(auth()->user()->profile_image) }}" id="userPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="avatar-placeholder" id="userPreviewImg">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <label for="userProfileImage" class="avatar-edit-overlay">
                            <i class="fa-solid fa-pen"></i>
                        </label>
                        <input type="file" name="profile_image" id="userProfileImage" accept="image/*" style="display: none;" onchange="previewUserImage(this)" form="userProfileForm">
                    </div>
                </div>
            </div>

            <div class="profile-content">
                <div class="profile-info-header">
                    <div>
                        <h2 class="profile-title">{{ auth()->user()->name }}</h2>
                        <p class="profile-subtitle">Update your personal profile and account security settings.</p>
                    </div>
                    
                    <div class="completion-card">
                        <div class="completion-header">
                            <span class="label">Profile Completion</span>
                            <span class="value">{{ auth()->user()->profile_completion }}%</span>
                        </div>
                        <div class="completion-bar-bg">
                            <div class="completion-bar-fill" style="width: {{ auth()->user()->profile_completion }}%;"></div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert-success">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('user.profile.update') }}" class="profile-form" enctype="multipart/form-data" id="userProfileForm">
                    @csrf
                    
                    <div style="margin-bottom: 40px;">
                        <h3 class="form-section-title">
                            <i class="fa-solid fa-user-circle" style="color: var(--primary);"></i> Personal Information
                        </h3>
                        <div class="form-grid">
                            <label>
                                <span>Full Name</span>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required placeholder="Enter your full name">
                            </label>
                            <label>
                                <span>Email Address</span>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required placeholder="email@example.com">
                            </label>
                            <label>
                                <span>Phone Number</span>
                                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="+92 300 1234567">
                            </label>
                            <label>
                                <span>Country</span>
                                <input type="text" name="country" value="{{ old('country', auth()->user()->country ?? 'Pakistan') }}">
                            </label>
                        </div>
                    </div>

                    <div style="margin-bottom: 40px;">
                        <h3 class="form-section-title">
                            <i class="fa-solid fa-location-dot" style="color: var(--primary);"></i> Shipping Address
                        </h3>
                        <div class="form-grid address-grid">
                            <label>
                                <span>Street Address</span>
                                <input type="text" name="address" value="{{ old('address', auth()->user()->address) }}" placeholder="House #, Street...">
                            </label>
                            <label>
                                <span>City</span>
                                <input type="text" name="city" value="{{ old('city', auth()->user()->city) }}" placeholder="Karachi">
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
                    </div>

                    <div style="margin-bottom: 40px;">
                        <h3 class="form-section-title">
                            <i class="fa-solid fa-lock" style="color: var(--primary);"></i> Change Password
                        </h3>
                        <div class="form-grid">
                            <label>
                                <span>Current Password</span>
                                <input type="password" name="current_password" placeholder="••••••••">
                            </label>
                            <label>
                                <span>New Password</span>
                                <input type="password" name="new_password" placeholder="••••••••">
                            </label>
                            <label>
                                <span>Confirm New Password</span>
                                <input type="password" name="new_password_confirmation" placeholder="••••••••">
                            </label>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: flex-end;">
                        <button type="submit" class="btn-save-profile">
                            <i class="fa-solid fa-floppy-disk"></i> Save All Changes
                        </button>
                    </div>
                </form>
            </div>
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
                const container = document.getElementById('userProfileImgContainer');
                const existingImg = container.querySelector('img');
                const existingPlaceholder = container.querySelector('#userPreviewImg');
                
                if (existingImg) {
                    existingImg.src = e.target.result;
                } else if (existingPlaceholder) {
                    existingPlaceholder.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: 100%; object-fit: cover;">';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Add interactivity to avatar label
    const avatarContainer = document.getElementById('userProfileImgContainer');
    const avatarOverlay = avatarContainer.querySelector('label');
    avatarContainer.addEventListener('mouseenter', () => avatarOverlay.style.opacity = '1');
    avatarContainer.addEventListener('mouseleave', () => avatarOverlay.style.opacity = '0');
</script>
@endsection
