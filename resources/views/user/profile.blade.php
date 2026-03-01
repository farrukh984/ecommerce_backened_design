@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

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
                        <div id="coverPreviewImg" style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary), var(--secondary)); opacity: 0.8;"></div>
                    @endif
                </div>
                
                <label for="userCoverImage" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.9); padding: 10px 20px; border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; box-shadow: var(--shadow-md); border: 1px solid var(--border); color: var(--text-main);">
                    <i class="fa-solid fa-camera"></i> Update Cover
                </label>
                <input type="file" name="cover_image" id="userCoverImage" accept="image/*" style="display: none;" onchange="previewCoverImage(this)" form="userProfileForm">

                <!-- Avatar Floating -->
                <div class="profile-avatar-floating">
                    <div class="profile-image-container" id="userProfileImgContainer" style="width: 140px; height: 140px; border-radius: 35px; border: 6px solid white; box-shadow: var(--shadow-lg); background: #f8fafc; overflow: hidden; position: relative;">
                        @if(auth()->user()->profile_image)
                            <img src="{{ display_image(auth()->user()->profile_image) }}" id="userPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div id="userPreviewImg" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: var(--text-muted); font-size: 40px;">
                                <i class="fa-solid fa-user"></i>
                            </div>
                        @endif
                        <label for="userProfileImage" style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white; opacity: 0; transition: 0.3s; cursor: pointer;">
                            <i class="fa-solid fa-pen"></i>
                        </label>
                        <input type="file" name="profile_image" id="userProfileImage" accept="image/*" style="display: none;" onchange="previewUserImage(this)" form="userProfileForm">
                    </div>
                </div>
            </div>

            <div style="padding: 60px 40px 40px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
                    <div>
                        <h2 style="font-family: 'Outfit', sans-serif; font-size: 28px; font-weight: 800; margin: 0 0 8px;">{{ auth()->user()->name }}</h2>
                        <p style="color: var(--text-sub); font-size: 15px; font-weight: 500; margin: 0;">Update your personal profile and account security settings.</p>
                    </div>
                    
                    <div style="min-width: 240px; background: #f8fafc; padding: 20px; border-radius: var(--radius-md); border: 1px solid var(--border);">
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 10px; font-weight: 700;">
                            <span style="color: var(--text-main);">Profile Completion</span>
                            <span style="color: var(--primary);">{{ auth()->user()->profile_completion }}%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ auth()->user()->profile_completion }}%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--secondary)); border-radius: 10px;"></div>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                    <div style="background: #dcfce7; color: #166534; padding: 16px 20px; border-radius: 12px; margin-bottom: 30px; font-weight: 600; display: flex; align-items: center; gap: 12px; border: 1px solid #bbf7d0;">
                        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div style="background: #fee2e2; color: #991b1b; padding: 16px 20px; border-radius: 12px; margin-bottom: 30px; font-weight: 600; display: flex; align-items: center; gap: 12px; border: 1px solid #fecaca;">
                        <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('user.profile.update') }}" class="profile-form" enctype="multipart/form-data" id="userProfileForm">
                    @csrf
                    
                    <div style="margin-bottom: 40px;">
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; margin: 0 0 20px; display: flex; align-items: center; gap: 10px;">
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
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; margin: 0 0 20px; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-location-dot" style="color: var(--primary);"></i> Address Details
                        </h3>
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
                    </div>

                    <div style="margin-bottom: 40px;">
                        <h3 style="font-family: 'Outfit', sans-serif; font-size: 18px; font-weight: 700; margin: 0 0 20px; display: flex; align-items: center; gap: 10px;">
                            <i class="fa-solid fa-shield-halved" style="color: var(--primary);"></i> Account Security
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
                        <button type="submit" class="profile-submit-btn">
                            Save Profile Changes
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
