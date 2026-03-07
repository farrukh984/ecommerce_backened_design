@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}?v={{ time() }}">
<link rel="stylesheet" href="{{ asset('css/user-profile.css') }}?v={{ time() }}">

@section('styles')
<style>
    .header-actions-premium {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        gap: 12px;
        z-index: 10;
    }

    .btn-remove-cover {
        background: rgba(239, 68, 68, 0.85);
        color: white;
        border: none;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        backdrop-filter: blur(8px);
    }

    .btn-remove-cover:hover {
        background: #ef4444;
        transform: translateY(-2px);
    }

    .btn-remove-avatar {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #ef4444;
        color: white;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 20;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        opacity: 0;
    }

    .profile-image-container:hover .btn-remove-avatar {
        opacity: 1;
    }

    .btn-remove-avatar:hover {
        transform: scale(1.1);
        background: #dc2626;
    }
</style>
@endsection

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
                
                <div class="header-actions-premium">
                    @if(auth()->user()->cover_image)
                        <button type="button" class="btn-remove-cover" id="removeCoverBtn" onclick="removeCover()">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    @endif
                    <label for="userCoverImage" class="btn-update-cover">
                        <i class="fa-solid fa-camera"></i> Update Cover
                    </label>
                </div>
                <input type="file" name="cover_image" id="userCoverImage" accept="image/*" style="display: none;" onchange="previewCoverImage(this)" form="userProfileForm">
                <input type="hidden" name="remove_cover_image" id="removeCoverInput" value="0" form="userProfileForm">

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
                        @if(auth()->user()->profile_image)
                            <button type="button" class="btn-remove-avatar" id="removeProfileBtn" onclick="removeProfile()">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endif
                        <input type="file" name="profile_image" id="userProfileImage" accept="image/*" style="display: none;" onchange="previewUserImage(this)" form="userProfileForm">
                        <input type="hidden" name="remove_profile_image" id="removeProfileInput" value="0" form="userProfileForm">
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
    function removeCover() {
        if(confirm('Are you sure you want to remove your cover image?')) {
            document.getElementById('removeCoverInput').value = '1';
            document.getElementById('coverPreviewImg').outerHTML = '<div class="cover-placeholder" id="coverPreviewImg"></div>';
            const btn = document.getElementById('removeCoverBtn');
            if(btn) btn.style.display = 'none';
        }
    }

    function removeProfile() {
        if(confirm('Are you sure you want to remove your profile image?')) {
            document.getElementById('removeProfileInput').value = '1';
            document.getElementById('userPreviewImg').outerHTML = '<div class="avatar-placeholder" id="userPreviewImg"><i class="fa-solid fa-user"></i></div>';
            const btn = document.getElementById('removeProfileBtn');
            if(btn) btn.style.display = 'none';
        }
    }
</script>
@endsection
