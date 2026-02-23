@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('header_title', 'Account Settings')

@section('admin_content')

<div style="max-width: 1100px; margin: 0 auto;">
    <section class="premium-card" style="padding: 0; overflow: visible;">
        <!-- Premium Header with Cover Image -->
        <div style="height: 220px; background: #f1f5f9; border-radius: var(--admin-radius-lg) var(--admin-radius-lg) 0 0; position: relative; overflow: visible;">
            <div id="coverContainer" style="width: 100%; height: 100%; overflow: hidden; border-radius: inherit;">
                @if(auth()->user()->cover_image)
                    <img src="{{ asset('storage/' . auth()->user()->cover_image) }}" id="coverPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <div id="coverPreviewImg" style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); opacity: 0.9;"></div>
                @endif
            </div>

            <!-- Change Cover Button -->
            <label for="adminCoverImage" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); padding: 10px 20px; border-radius: 12px; cursor: pointer; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; box-shadow: var(--admin-shadow-md); border: 1px solid white; color: var(--admin-text);">
                <i class="fa-solid fa-camera"></i> Change Cover
            </label>
            <input type="file" name="cover_image" id="adminCoverImage" accept="image/*" style="display: none;" onchange="previewCoverImage(this)" form="adminProfileForm">

            <!-- Avatar & Info Floating -->
            <div style="position: absolute; bottom: -60px; left: 40px; display: flex; align-items: flex-end; gap: 28px;">
                <div style="width: 150px; height: 150px; border-radius: 40px; border: 7px solid white; box-shadow: var(--admin-shadow-lg); background: #f8fafc; overflow: hidden; position: relative;">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" id="adminPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div id="adminPreviewImg" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #eef2ff; color: var(--admin-primary); font-size: 48px; font-weight: 800;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <label for="adminProfileImage" style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; color: white; opacity: 0; transition: 0.3s; cursor: pointer;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0'">
                        <i class="fa-solid fa-pen-to-square" style="font-size: 24px;"></i>
                    </label>
                    <input type="file" name="profile_image" id="adminProfileImage" accept="image/*" style="display: none;" onchange="previewAdminImage(this)" form="adminProfileForm">
                </div>
                <div style="margin-bottom: 25px;">
                    <h2 style="font-family: 'Outfit', sans-serif; font-size: 32px; font-weight: 800; color: #fff; text-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 5px;">{{ auth()->user()->name }}</h2>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="background: rgba(255,255,255,0.25); backdrop-filter: blur(8px); color: white; padding: 5px 15px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: 1px solid rgba(255,255,255,0.3);">
                            <i class="fa-solid fa-shield-check"></i> Super Administrator
                        </span>
                        <span style="background: rgba(16, 185, 129, 0.2); backdrop-filter: blur(8px); color: #10b981; padding: 5px 15px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: 1px solid rgba(16, 185, 129, 0.3);">
                            <i class="fa-solid fa-circle"></i> Online
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div style="padding: 100px 48px 48px;">
            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 18px 24px; border-radius: 16px; margin-bottom: 32px; font-weight: 700; display: flex; align-items: center; gap: 12px; border: 1px solid #bbf7d0; animation: slideDown 0.4s ease;">
                    <i class="fa-solid fa-circle-check" style="font-size: 20px;"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fee2e2; color: #991b1b; padding: 18px 24px; border-radius: 16px; margin-bottom: 32px; font-weight: 700; display: flex; align-items: center; gap: 12px; border: 1px solid #fecaca; animation: slideDown 0.4s ease;">
                    <i class="fa-solid fa-circle-exclamation" style="font-size: 20px;"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" class="profile-form" enctype="multipart/form-data" id="adminProfileForm">
                @csrf
                
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 48px;">
                    <!-- Personal Info -->
                    <div style="display: flex; flex-direction: column; gap: 40px;">
                        <div>
                            <h3 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; background: var(--admin-primary-glow); color: var(--admin-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-user-gear"></i>
                                </div>
                                Basic Information
                            </h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required placeholder="Enter your full name">
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required placeholder="admin@example.com">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}" placeholder="+92 300 1234567">
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" name="country" class="form-control" value="{{ old('country', auth()->user()->country ?? 'Pakistan') }}">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; background: rgba(14, 165, 233, 0.1); color: #0ea5e9; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                Address Details
                            </h3>
                            <div class="form-group">
                                <label>Street Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->address) }}" placeholder="Full street address">
                            </div>
                            <div class="form-row" style="grid-template-columns: 1fr 1fr 1fr;">
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
                        </div>

                        <div>
                            <h3 style="font-family: 'Outfit', sans-serif; font-size: 20px; font-weight: 800; margin-bottom: 24px; display: flex; align-items: center; gap: 12px;">
                                <div style="width: 32px; height: 32px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                Change Password
                            </h3>
                            <div class="form-group" style="background: #fffbeb; border: 1px solid #fde68a; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
                                <div style="display: flex; gap: 10px; align-items: center; color: #92400e; font-size: 13px; font-weight: 600;">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Leave password fields empty if you don't want to change it.
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                                <small style="display: block; margin-top: 5px; color: var(--admin-text-sub); font-size: 11px;">Required only if changing password</small>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                                </div>
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <div style="display: flex; justify-content: flex-end;">
                            <button type="submit" class="btn-primary" style="padding: 16px 48px; font-size: 15px;">
                                <i class="fa-solid fa-floppy-disk"></i> Save Changes
                            </button>
                        </div>
                    </div>

                    <!-- Side Status -->
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div style="background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 24px; padding: 32px; position: sticky; top: 32px;">
                            <h4 style="font-size: 12px; font-weight: 800; color: var(--admin-text-sub); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-signal" style="font-size: 10px;"></i> Account Metrics
                            </h4>
                            
                            <div style="margin-bottom: 32px; background: white; padding: 20px; border-radius: 16px; border: 1px solid var(--admin-border);">
                                <div style="display: flex; justify-content: space-between; font-size: 13px; font-weight: 800; margin-bottom: 12px;">
                                    <span style="color: var(--admin-text);">Profile Strength</span>
                                    <span style="color: var(--admin-primary);">{{ auth()->user()->profile_completion }}%</span>
                                </div>
                                <div style="width: 100%; height: 10px; background: #e2e8f0; border-radius: 5px; overflow: hidden;">
                                    <div style="width: {{ auth()->user()->profile_completion }}%; height: 100%; background: linear-gradient(90deg, var(--admin-primary), var(--admin-secondary)); border-radius: 5px;"></div>
                                </div>
                            </div>

                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                <div style="display: flex; align-items: center; gap: 14px; background: white; padding: 14px; border-radius: 14px; border: 1px solid var(--admin-border);">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                        <i class="fa-solid fa-envelope-circle-check"></i>
                                    </div>
                                    <div style="font-size: 13px; font-weight: 700;">Email Verified</div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 14px; background: white; padding: 14px; border-radius: 14px; border: 1px solid var(--admin-border);">
                                    <div style="width: 32px; height: 32px; border-radius: 8px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                                        <i class="fa-solid fa-key"></i>
                                    </div>
                                    <div style="font-size: 13px; font-weight: 700;">2FA Status: Restricted</div>
                                </div>
                            </div>

                            <div style="margin-top: 32px; padding: 20px; background: #f1f5f9; border-radius: 16px;">
                                <div style="font-size: 11px; color: var(--admin-text-sub); font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">Security Policy</div>
                                <p style="font-size: 12px; color: var(--admin-text); line-height: 1.6; margin: 0;">
                                    Any changes to administrator credentials are logged for security auditing purposes.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection

@section('scripts')
<script>
    function previewAdminImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('adminPreviewImg');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    preview.outerHTML = '<img src="' + e.target.result + '" id="adminPreviewImg" style="width: 100%; height: 100%; object-fit: cover;">';
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewCoverImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const coverContainer = document.getElementById('coverContainer');
                coverContainer.innerHTML = '<img src="' + e.target.result + '" id="coverPreviewImg" style="width: 100%; height: 100%; object-fit: cover; animation: fadeIn 0.3s;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<style>
@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
@endsection
