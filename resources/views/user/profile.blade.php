@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'profile'])

    <main class="dashboard-main">
        <section class="profile-panel">
            <div class="profile-hero">
                <div class="profile-avatar-xl" id="userAvatarDisplay" style="overflow: hidden; position: relative;">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div>
                    <h2>Profile Settings</h2>
                    <p>Update your account details, profile picture and password securely.</p>
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

            <form method="POST" action="{{ route('user.profile.update') }}" class="profile-form" enctype="multipart/form-data">
                @csrf

                <!-- Profile Image Upload Section -->
                <div class="profile-image-upload-section">
                    <div class="profile-image-container" id="userProfileImgContainer">
                        @if(auth()->user()->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" id="userPreviewImg" class="profile-img-preview">
                        @else
                            <div id="userPreviewImg" class="profile-img-placeholder">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                        @endif
                        <label for="userProfileImage" class="profile-img-overlay">
                            <i class="fa-solid fa-pen"></i>
                        </label>
                    </div>
                    <div class="profile-image-info">
                        <h4>Profile Picture</h4>
                        <p>Upload a new avatar. JPG, PNG or WEBP. Max 2MB.</p>
                        <label for="userProfileImage" class="profile-upload-btn">
                            <i class="fa-solid fa-upload"></i> Choose Photo
                        </label>
                        <input type="file" name="profile_image" id="userProfileImage" accept="image/*" style="display: none;" onchange="previewUserImage(this)">
                    </div>
                </div>

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

@section('scripts')
<script>
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
                    existingPlaceholder.outerHTML = '<img src="' + e.target.result + '" id="userPreviewImg" class="profile-img-preview">';
                }

                // Update avatar in header
                const avatarDisplay = document.getElementById('userAvatarDisplay');
                avatarDisplay.innerHTML = '<img src="' + e.target.result + '" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
