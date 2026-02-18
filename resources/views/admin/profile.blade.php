@extends('layouts.admin')

@section('title', 'Profile Settings')
@section('header_title', 'Profile Settings')

@section('admin_content')

<div class="premium-card" style="max-width: 800px;">
    <div class="action-header">
        <div class="header-title">
            <h2>Admin Profile</h2>
            <p>Update your account details and profile picture</p>
        </div>
    </div>

    <div class="form-container">
        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; margin-bottom: 24px;">
                <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; margin-bottom: 24px;">
                <i class="fa-solid fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf

            <!-- Profile Image Section -->
            <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 32px; padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px solid var(--admin-border);">
                <div id="adminAvatarPreview" style="width: 90px; height: 90px; border-radius: 16px; overflow: hidden; border: 3px solid var(--admin-primary); flex-shrink: 0; position: relative;">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" id="adminPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div id="adminPreviewImg" style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: 800; font-family: 'Outfit', sans-serif;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div>
                    <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; margin-bottom: 8px;">Profile Picture</h3>
                    <p style="font-size: 13px; color: var(--admin-text-sub); margin-bottom: 12px;">JPG, PNG or WEBP. Max 2MB.</p>
                    <label for="adminProfileImage" class="btn-primary" style="cursor: pointer; padding: 8px 16px; font-size: 13px;">
                        <i class="fa-solid fa-camera"></i> Choose Photo
                    </label>
                    <input type="file" name="profile_image" id="adminProfileImage" accept="image/*" style="display: none;" onchange="previewAdminImage(this)">
                </div>
            </div>

            <!-- Name & Email -->
            <div class="form-row" style="margin-bottom: 24px;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
            </div>

            <!-- Password Change -->
            <div style="margin-bottom: 24px;">
                <h3 style="font-family: 'Outfit', sans-serif; font-size: 16px; margin-bottom: 16px; padding-top: 16px; border-top: 1px solid var(--admin-border);">Change Password</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Enter new password">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>
                    <div></div>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="padding: 12px 28px;">
                <i class="fa-solid fa-check"></i> Save Changes
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function previewAdminImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.getElementById('adminAvatarPreview');
                container.innerHTML = '<img src="' + e.target.result + '" id="adminPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
