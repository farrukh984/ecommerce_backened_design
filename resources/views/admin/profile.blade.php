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
                    <img src="{{ display_image(auth()->user()->cover_image) }}" id="coverPreviewImg">
                @else
                    <div id="coverPreviewImg" class="cover-placeholder"></div>
                @endif
            </div>

            <!-- Change Cover Button -->
            <div class="cover-actions">
                @if(auth()->user()->cover_image)
                    <button type="button" class="remove-image-btn" id="removeCoverBtn" onclick="removeCover()">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                @endif
                <label for="adminCoverImage" class="change-cover-btn">
                    <i class="fa-solid fa-camera"></i> <span>Change Cover</span>
                </label>
            </div>
            <input type="file" name="cover_image" id="adminCoverImage" accept="image/*" style="display: none;" onchange="previewCoverImage(this)" form="adminProfileForm">
            <input type="hidden" name="remove_cover_image" id="removeCoverInput" value="0" form="adminProfileForm">

            <!-- Avatar & Info Floating -->
            <div class="profile-identity-section">
                <div class="avatar-wrapper">
                    @if(auth()->user()->profile_image)
                        <img src="{{ display_image(auth()->user()->profile_image) }}" id="adminPreviewImg">
                    @else
                        <div id="adminPreviewImg" class="avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <label for="adminProfileImage" class="avatar-edit-overlay">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </label>
                    @if(auth()->user()->profile_image)
                        <button type="button" class="remove-avatar-btn" id="removeProfileBtn" onclick="removeProfile()">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    @endif
                    <input type="file" name="profile_image" id="adminProfileImage" accept="image/*" style="display: none;" onchange="previewAdminImage(this)" form="adminProfileForm">
                    <input type="hidden" name="remove_profile_image" id="removeProfileInput" value="0" form="adminProfileForm">
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
    
    .cover-image-container { width: 100%; height: 100%; border-radius: inherit; overflow: hidden; background: var(--admin-border, #e2e8f0); }
    .cover-image-container img { width: 100%; height: 100%; object-fit: cover; }
    .cover-placeholder { width: 100%; height: 100%; background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary)); }
    
    .cover-actions { position: absolute; top: 20px; right: 20px; display: flex; gap: 10px; z-index: 10; }
    
    .remove-image-btn {
        background: rgba(239, 68, 68, 0.9);
        color: white; border: none; width: 38px; height: 38px;
        border-radius: 12px; cursor: pointer; display: flex;
        align-items: center; justify-content: center; font-size: 14px;
        backdrop-filter: blur(10px); transition: 0.3s;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
    .remove-image-btn:hover { background: #ef4444; transform: translateY(-2px); }

    .change-cover-btn {
        background: var(--admin-card, rgba(255,255,255,0.9));
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 12px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 700;
        display: flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .profile-identity-section {
        position: absolute; bottom: -60px; left: 40px;
        display: flex; align-items: flex-end; gap: 24px;
        z-index: 20;
    }

    .avatar-wrapper {
        width: 140px; height: 140px;
        border-radius: 35px; border: 6px solid var(--admin-card, white);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        background: var(--admin-card, #fff); overflow: hidden; position: relative;
    }
    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-placeholder { 
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        background: var(--admin-card-alt, #f1f5f9); color: var(--admin-primary); font-size: 40px; font-weight: 800;
    }
    
    .avatar-edit-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,0.4);
        display: flex; align-items: center; justify-content: center;
        color: white; opacity: 0; transition: 0.3s; cursor: pointer;
    }
    .avatar-wrapper:hover .avatar-edit-overlay { opacity: 1; }
    
    .remove-avatar-btn {
        position: absolute; top: 5px; right: 5px;
        background: #ef4444; color: white; border: none;
        width: 24px; height: 24px; border-radius: 8px;
        font-size: 12px; cursor: pointer; z-index: 30;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3); transition: 0.3s;
        opacity: 0;
    }
    .avatar-wrapper:hover .remove-avatar-btn { opacity: 1; }
    .remove-avatar-btn:hover { transform: scale(1.1); background: #dc2626; }

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
        display: flex; align-items: center; gap: 12px; color: var(--admin-text, #1e293b);
    }
    .title-icon {
        width: 32px; height: 32px; border-radius: 8px; display: flex;
        align-items: center; justify-content: center; font-size: 14px;
    }
    .basic-info-icon { background: var(--primary-light, #eff6ff); color: var(--admin-primary); }
    .address-icon { background: #f0fdf4; color: #10b981; }
    .security-icon { background: #fef2f2; color: #ef4444; }

    .password-notice {
        background: var(--badge-bg, #fffbeb); border: 1px solid var(--admin-border, #fde68a); padding: 12px 16px;
        border-radius: 12px; font-size: 12px; color: var(--warning, #92400e); font-weight: 600;
        margin-bottom: 20px; display: flex; gap: 10px; align-items: center;
    }

    .metrics-sidebar { position: sticky; top: 30px; background: var(--admin-card-alt, #f8fafc); border: 1px solid var(--admin-border, #e2e8f0); border-radius: 20px; padding: 24px; }
    .metrics-title { font-size: 11px; font-weight: 800; color: var(--admin-text-sub, #64748b); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; }
    
    .strength-card { background: var(--admin-card, white); padding: 15px; border-radius: 12px; border: 1px solid var(--admin-border, #e2e8f0); margin-bottom: 20px; }
    .strength-info { display: flex; justify-content: space-between; font-size: 12px; font-weight: 700; margin-bottom: 10px; }
    .progress-bar { height: 8px; background: var(--admin-card-alt, #f1f5f9); border-radius: 10px; overflow: hidden; }
    .progress-fill { height: 100%; background: linear-gradient(90deg, var(--admin-primary), var(--admin-secondary)); }

    .status-list { display: flex; flex-direction: column; gap: 12px; }
    .status-item { 
        display: flex; align-items: center; gap: 12px; padding: 12px; 
        background: var(--admin-card, white); border-radius: 12px; border: 1px solid var(--admin-border, #e2e8f0); font-size: 13px; font-weight: 600;
        color: var(--admin-text);
    }
    .status-item.verified i { color: #10b981; }
    .status-item.restricted i { color: #3b82f6; }

    .security-info-box { margin-top: 24px; padding: 15px; background: var(--admin-card-alt, #f1f5f9); border-radius: 12px; }
    .info-label { font-size: 10px; font-weight: 800; color: var(--text-muted, #94a3b8); text-transform: uppercase; margin-bottom: 6px; }
    .security-info-box p { font-size: 11px; color: var(--admin-text-sub, #64748b); line-height: 1.5; margin: 0; }

    .alert { padding: 15px 20px; border-radius: 15px; margin-bottom: 25px; font-weight: 700; display: flex; align-items: center; gap: 12px; font-size: 14px; }
    .alert-success { background: var(--success-bg, #f0fdf4); color: var(--success-text, #166534); border: 1px solid var(--success-border, #bbf7d0); }
    .alert-danger { background: var(--danger-bg, #fef2f2); color: var(--danger, #991b1b); border: 1px solid var(--danger-border, #fecaca); }

    /* Dark Mode Overrides */
    [data-theme="dark"] .metrics-sidebar,
    [data-theme="dark"] .strength-card,
    [data-theme="dark"] .status-item,
    [data-theme="dark"] .security-info-box {
        background: #1a2332 !important;
        border-color: #334155 !important;
    }
    
    [data-theme="dark"] .avatar-wrapper {
        border-color: #1a2332 !important;
        background: #1e293b !important;
    }
    
    [data-theme="dark"] .avatar-placeholder {
        background: #1a2332;
    }

    [data-theme="dark"] .basic-info-icon { background: rgba(79, 70, 229, 0.15); color: #818cf8; }
    [data-theme="dark"] .address-icon { background: rgba(16, 185, 129, 0.15); color: #4ade80; }
    [data-theme="dark"] .security-icon { background: rgba(239, 68, 68, 0.15); color: #fb7185; }
    
    [data-theme="dark"] .password-notice {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
    }

    [data-theme="dark"] .change-cover-btn {
        background: #1e293b;
        color: #f1f5f9;
        border: 1px solid #334155;
    }

    [data-theme="dark"] .alert-success { background: rgba(22, 101, 52, 0.1); color: #4ade80; border-color: rgba(22, 101, 52, 0.2); }
    [data-theme="dark"] .alert-danger { background: rgba(153, 27, 27, 0.1); color: #fb7185; border-color: rgba(153, 27, 27, 0.2); }

    @media (max-width: 991px) {
        .profile-grid { grid-template-columns: 1fr; }
        .metrics-sidebar { position: static; margin-top: 20px; }
    }

    @media (max-width: 768px) {
        .profile-header-cover { height: 160px; }
        .profile-identity-section { left: 50%; bottom: -80px; transform: translateX(-50%); flex-direction: column; align-items: center; text-align: center; gap: 15px; width: 100%; }
        .avatar-wrapper { width: 120px; height: 120px; border-radius: 30px; }
        .identity-info h2 { font-size: 24px; color: var(--admin-text, #1e293b); text-shadow: none; margin-bottom: 10px; }
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
    function removeCover() {
        Swal.fire({
            title: 'Remove Cover Image?',
            text: "This will remove your current cover image.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, remove it!',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#fff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#f1f5f9' : '#1e293b'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('removeCoverInput').value = '1';
                document.getElementById('coverContainer').innerHTML = '<div id="coverPreviewImg" class="cover-placeholder"></div>';
                const btn = document.getElementById('removeCoverBtn');
                if(btn) btn.style.display = 'none';
                
                Swal.fire({
                    title: 'Removed!',
                    text: 'Image has been removed from view. Save changes to finalize.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#fff',
                    color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#f1f5f9' : '#1e293b'
                });
            }
        });
    }

    function removeProfile() {
        Swal.fire({
            title: 'Remove Profile Image?',
            text: "This will remove your current profile picture.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Yes, remove it!',
            background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#fff',
            color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#f1f5f9' : '#1e293b'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('removeProfileInput').value = '1';
                document.getElementById('adminPreviewImg').outerHTML = '<div id="adminPreviewImg" class="avatar-placeholder">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>';
                const btn = document.getElementById('removeProfileBtn');
                if(btn) btn.style.display = 'none';

                Swal.fire({
                    title: 'Removed!',
                    text: 'Image has been removed from view. Save changes to finalize.',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false,
                    background: document.documentElement.getAttribute('data-theme') === 'dark' ? '#1e293b' : '#fff',
                    color: document.documentElement.getAttribute('data-theme') === 'dark' ? '#f1f5f9' : '#1e293b'
                });
            }
        });
    }
</script>
@endsection
