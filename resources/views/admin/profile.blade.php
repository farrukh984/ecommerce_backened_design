@extends('layouts.admin')

@section('title', 'Profile Settings')
@section('header_title', 'Profile Settings')

@section('admin_content')

    <div class="premium-card" style="max-width: 900px; padding: 0; overflow: hidden;">
        <!-- Modern Admin Profile Header with Cover Image -->
        <div style="position: relative; height: 180px; background: #f8fafc;">
            @if(auth()->user()->cover_image)
                <img src="{{ asset('storage/' . auth()->user()->cover_image) }}" id="adminCoverPreview" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div id="adminCoverPreview" style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); opacity: 0.9;"></div>
            @endif
            
            <label for="adminCoverImage" style="position: absolute; top: 15px; right: 15px; background: rgba(255,255,255,0.9); padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 6px; border: 1px solid var(--admin-border);">
                <i class="fa-solid fa-camera"></i> Change Cover
            </label>
            <input type="file" name="cover_image" id="adminCoverImage" accept="image/*" style="display: none;" onchange="previewAdminCover(this)" form="adminProfileForm">

            <!-- Avatar Floating -->
            <div style="position: absolute; bottom: -35px; left: 30px; display: flex; align-items: flex-end; gap: 15px;">
                <div id="adminAvatarPreview" style="width: 100px; height: 100px; border-radius: 12px; overflow: hidden; border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.1); background: white;">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" id="adminPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div id="adminPreviewImg" style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: 800;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div style="padding-bottom: 5px;">
                    <label for="adminProfileImage" style="background: white; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                        <i class="fa-solid fa-pen" style="font-size: 12px; color: var(--admin-primary);"></i>
                    </label>
                    <input type="file" name="profile_image" id="adminProfileImage" accept="image/*" style="display: none;" onchange="previewAdminImage(this)" form="adminProfileForm">
                </div>
            </div>
        </div>

        <div style="padding: 50px 30px 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--admin-border);">
            <div>
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 22px;">{{ auth()->user()->name }}</h2>
                <p style="color: var(--admin-text-sub); font-size: 13px;">Manage your admin account details</p>
            </div>
            <!-- Profile strength -->
            <div style="width: 180px;">
                <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 4px; font-weight: 700;">
                    <span>Profile Strength</span>
                    <span style="color: var(--admin-primary);">{{ auth()->user()->profile_completion }}%</span>
                </div>
                <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; border: 1px solid #e2e8f0;">
                    <div style="width: {{ auth()->user()->profile_completion }}%; height: 100%; background: linear-gradient(90deg, var(--admin-primary), var(--admin-secondary)); border-radius: 3px;"></div>
                </div>
            </div>
        </div>

        <div class="form-container" style="padding: 30px;">
            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; font-size: 14px; font-weight: 500; margin-bottom: 24px;">
                    <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" id="adminProfileForm">
                @csrf
                
            <!-- Name & Email -->
            <div class="form-row" style="margin-bottom: 24px; margin-top: 20px;">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                </div>
            </div>

            <!-- Phone & Country -->
            <div class="form-row" style="margin-bottom: 24px;">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" placeholder="e.g. +92 300 1234567">
                </div>
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', auth()->user()->country ?? 'Pakistan') }}">
                </div>
            </div>

            <!-- Address -->
            <div class="form-group" style="margin-bottom: 24px;">
                <label>Full Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->address) }}" placeholder="Street address, apartment, suite, etc.">
            </div>

            <!-- City, State, Zip -->
            <div class="form-row" style="margin-bottom: 24px;">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', auth()->user()->city) }}">
                </div>
                <div class="form-group">
                    <label>State / Province</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state', auth()->user()->state) }}">
                </div>
                <div class="form-group">
                    <label>Zip Code</label>
                    <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', auth()->user()->zip_code) }}">
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
    function previewAdminCover(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('adminCoverPreview').outerHTML = '<img src="' + e.target.result + '" id="adminCoverPreview" style="width: 100%; height: 100%; object-fit: cover;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

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
