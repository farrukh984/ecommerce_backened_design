@extends('layouts.admin')

@section('title', 'Admin Profile')
@section('header_title', 'Account Settings')

@section('admin_content')
<div class="profile-page-wrapper">
    <section class="premium-card profile-main-card">
        <!-- Premium Header with Cover Image -->
        <div class="profile-header-cover">
            <div id="coverContainer" class="cover-image-container">
                @if(auth()->user()->cover_image)
                    <img src="{{ asset('storage/' . auth()->user()->cover_image) }}" id="coverPreviewImg">
                @else
                    <div id="coverPreviewImg" class="cover-placeholder"></div>
                @endif
            </div>

            <!-- Change Cover Button -->
            <label for="adminCoverImage" class="change-cover-btn">
                <i class="fa-solid fa-camera"></i> <span>Change Cover</span>
            </label>
            <input type="file" name="cover_image" id="adminCoverImage" accept="image/*" style="display: none;" onchange="previewCoverImage(this)" form="adminProfileForm">

            <!-- Avatar & Info Floating -->
            <div class="profile-identity-section">
                <div class="avatar-wrapper">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" id="adminPreviewImg">
                    @else
                        <div id="adminPreviewImg" class="avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <label for="adminProfileImage" class="avatar-edit-overlay">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </label>
                    <input type="file" name="profile_image" id="adminProfileImage" accept="image/*" style="display: none;" onchange="previewAdminImage(this)" form="adminProfileForm">
                </div>
                <div class="identity-info">
                    <h2>{{ auth()->user()->name }}</h2>
                    <div class="status-pills">
                        <span class="pill super-admin">
                            <i class="fa-solid fa-shield-check"></i> Admin
                        </span>
                        <span class="pill status-online">
                            <i class="fa-solid fa-circle"></i> Online
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-content-body">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.profile.update') }}" class="profile-form" enctype="multipart/form-data" id="adminProfileForm">
                @csrf
                
                <div class="profile-grid">
                    <!-- Personal Info -->
                    <div class="form-sections-stack">
                        <div class="settings-section">
                            <h3 class="section-title">
                                <div class="title-icon basic-info-icon">
                                    <i class="fa-solid fa-user-gear"></i>
                                </div>
                                Basic Information
                            </h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                                </div>
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', auth()->user()->phone) }}">
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" name="country" class="form-control" value="{{ old('country', auth()->user()->country ?? 'Pakistan') }}">
                                </div>
                            </div>
                        </div>

                        <div class="settings-section">
                            <h3 class="section-title">
                                <div class="title-icon address-icon">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                Address Details
                            </h3>
                            <div class="form-group">
                                <label>Street Address</label>
                                <input type="text" name="address" class="form-control" value="{{ old('address', auth()->user()->address) }}">
                            </div>
                            <div class="form-row address-row">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', auth()->user()->city) }}">
                                </div>
                                <div class="form-group">
                                    <label>Province</label>
                                    <input type="text" name="state" class="form-control" value="{{ old('state', auth()->user()->state) }}">
                                </div>
                                <div class="form-group">
                                    <label>Zip Code</label>
                                    <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', auth()->user()->zip_code) }}">
                                </div>
                            </div>
                        </div>

                        <div class="settings-section">
                            <h3 class="section-title">
                                <div class="title-icon security-icon">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                Security
                            </h3>
                            <div class="password-notice">
                                <i class="fa-solid fa-circle-info"></i>
                                Leave empty to keep your current password.
                            </div>
                            <div class="form-group">
                                <label>Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-primary main-save-btn">
                                <i class="fa-solid fa-floppy-disk"></i> Save All Changes
                            </button>
                        </div>
                    </div>

                    <!-- Side Stats -->
                    <div class="metrics-column">
                        <div class="metrics-sidebar">
                            <h4 class="metrics-title">
                                <i class="fa-solid fa-signal"></i> Account Metrics
                            </h4>
                            
                            <div class="strength-card">
                                <div class="strength-info">
                                    <span>Profile Strength</span>
                                    <span class="value">{{ auth()->user()->profile_completion }}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ auth()->user()->profile_completion }}%"></div>
                                </div>
                            </div>

                            <div class="status-list">
                                <div class="status-item verified">
                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                    <span>Verified</span>
                                </div>
                                <div class="status-item restricted">
                                    <i class="fa-solid fa-key"></i>
                                    <span>2FA: Restricted</span>
                                </div>
                            </div>

                            <div class="security-info-box">
                                <div class="info-label">Security Notice</div>
                                <p>All account changes are logged for auditing purposes.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

@endsection

@section('styles')
<style>
    .profile-page-wrapper { max-width: 1100px; margin: 0 auto; }
    .profile-main-card { padding: 0 !important; overflow: visible !important; }
    
    .profile-header-cover {
        height: 220px;
        position: relative;
        border-radius: 20px 20px 0 0;
    }
    
    .cover-image-container { width: 100%; height: 100%; border-radius: inherit; overflow: hidden; background: #e2e8f0; }
    .cover-image-container img { width: 100%; height: 100%; object-fit: cover; }
    .cover-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); }
    
    .change-cover-btn {
        position: absolute; top: 20px; right: 20px;
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
        display: flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 10;
    }

    .profile-identity-section {
        position: absolute; bottom: -60px; left: 40px;
        display: flex; align-items: flex-end; gap: 24px;
        z-index: 20;
    }

    .avatar-wrapper {
        width: 140px; height: 140px;
        border-radius: 35px; border: 6px solid white;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        background: #fff; overflow: hidden; position: relative;
    }
    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-placeholder { 
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        background: #f1f5f9; color: var(--admin-primary); font-size: 40px; font-weight: 800;
    }
    
    .avatar-edit-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,0.4);
        display: flex; align-items: center; justify-content: center;
        color: white; opacity: 0; transition: 0.3s; cursor: pointer;
    }
    .avatar-wrapper:hover .avatar-edit-overlay { opacity: 1; }

    .identity-info h2 {
        font-size: 28px; font-weight: 800; color: white;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3); margin-bottom: 8px;
    }
    .status-pills { display: flex; gap: 8px; }
    .pill {
        padding: 4px 12px; border-radius: 50px; font-size: 10px; font-weight: 700;
        text-transform: uppercase; border: 1px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(5px);
    }
    .super-admin { background: rgba(67, 65, 65, 0.2); color: white; text-shadow: 0 2px 10px rgba(0,0,0,0.3);}
    .status-online { background: rgba(16, 185, 129, 0.2); color: #10b981; border-color: rgba(16, 185, 129, 0.4); }

    .profile-content-body { padding: 90px 40px 40px; }
    .profile-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; }
    
    .settings-section { margin-bottom: 40px; }
    .section-title { 
        font-size: 18px; font-weight: 800; margin-bottom: 24px; 
        display: flex; align-items: center; gap: 12px; color: #1e293b;
    }
    .title-icon {
        width: 32px; height: 32px; border-radius: 8px; display: flex;
        align-items: center; justify-content: center; font-size: 14px;
    }
    .basic-info-icon { background: #eff6ff; color: var(--admin-primary); }
    .address-icon { background: #f0fdf4; color: #10b981; }
    .security-icon { background: #fef2f2; color: #ef4444; }

    .password-notice {
        background: #fffbeb; border: 1px solid #fde68a; padding: 12px 16px;
        border-radius: 12px; font-size: 12px; color: #92400e; font-weight: 600;
        margin-bottom: 20px; display: flex; gap: 10px; align-items: center;
    }

    .metrics-sidebar { position: sticky; top: 30px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 24px; }
    .metrics-title { font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
    
    .strength-card { background: white; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
    .strength-info { display: flex; justify-content: space-between; font-size: 12px; font-weight: 700; margin-bottom: 10px; }
    .progress-bar { height: 8px; background: #f1f5f9; border-radius: 10px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, var(--admin-primary), var(--admin-secondary)); }

    .status-list { display: flex; flex-direction: column; gap: 12px; }
    .status-item { 
        display: flex; align-items: center; gap: 12px; padding: 12px; 
        background: white; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 600;
    }
    .status-item.verified i { color: #10b981; }
    .status-item.restricted i { color: #3b82f6; }

    .security-info-box { margin-top: 24px; padding: 15px; background: #f1f5f9; border-radius: 12px; }
    .info-label { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px; }
    .security-info-box p { font-size: 11px; color: #64748b; line-height: 1.5; margin: 0; }

    .alert { padding: 15px 20px; border-radius: 15px; margin-bottom: 25px; font-weight: 700; display: flex; align-items: center; gap: 12px; font-size: 14px; }
    .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
    .alert-danger { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

    @media (max-width: 991px) {
        .profile-grid { grid-template-columns: 1fr; }
        .metrics-sidebar { position: static; margin-top: 20px; }
    }

    @media (max-width: 768px) {
        .profile-header-cover { height: 160px; }
        .profile-identity-section { left: 50%; bottom: -80px; transform: translateX(-50%); flex-direction: column; align-items: center; text-align: center; gap: 15px; width: 100%; }
        .avatar-wrapper { width: 120px; height: 120px; border-radius: 30px; }
        .identity-info h2 { font-size: 24px; color: #1e293b; text-shadow: none; margin-bottom: 10px; }
        .status-pills { justify-content: center; }
        .pill { background: #f1f5f9; color: #64748b; border-color: #e2e8f0; }
        .profile-content-body { padding: 100px 20px 30px; }
        .change-cover-btn span { display: none; }
        .change-cover-btn { padding: 10px; }
        .main-save-btn { width: 100%; padding: 15px; }
        .address-row { grid-template-columns: 1fr !important; }
    }
</style>
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
                    preview.outerHTML = '<img src="' + e.target.result + '" id="adminPreviewImg">';
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
                coverContainer.innerHTML = '<img src="' + e.target.result + '" id="coverPreviewImg" style="animation: fadeIn 0.3s;">';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
