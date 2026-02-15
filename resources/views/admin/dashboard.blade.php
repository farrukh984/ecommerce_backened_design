@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header_title', 'Dashboard Overview')

@section('admin_content')

<!-- Stats Widgets -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-content">
            <h3>Total Products</h3>
            <p>1,248</p>
        </div>
        <div class="stat-icon bg-blue">
            <i class="fa-solid fa-box"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h3>Categories</h3>
            <p>24</p>
        </div>
        <div class="stat-icon bg-purple">
            <i class="fa-solid fa-list"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h3>Total Brands</h3>
            <p>56</p>
        </div>
        <div class="stat-icon bg-orange">
            <i class="fa-solid fa-copyright"></i>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-content">
            <h3>Active Orders</h3>
            <p>84</p>
        </div>
        <div class="stat-icon bg-green">
            <i class="fa-solid fa-truck-fast"></i>
        </div>
    </div>
</div>

<div class="premium-card">
    <div class="action-header">
        <div class="header-title">
            <h2>Welcome to Admin Management</h2>
            <p>Use the sidebar to manage your shop's dynamic content</p>
        </div>
    </div>
    <div style="padding: 24px;">
        <div class="form-row">
            <div style="background: #fcfcfc; padding: 20px; border-radius: 12px; border: 1px solid var(--admin-border);">
                <h3 style="font-size: 16px; margin-bottom: 12px;">Quick Tasks</h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="{{ route('admin.products.create') }}" class="btn-primary" style="justify-content: center;">
                        <i class="fa-solid fa-plus"></i> Add New Product
                    </a>
                    <a href="{{ route('admin.categories.create') }}" class="btn-outline" style="text-align: center;">
                        Add Category
                    </a>
                </div>
            </div>
            <div style="background: #fcfcfc; padding: 20px; border-radius: 12px; border: 1px solid var(--admin-border);">
                <h3 style="font-size: 16px; margin-bottom: 12px;">Site Status</h3>
                <p style="font-size: 14px; color: var(--admin-text-sub); line-height: 1.6;">
                    System is running stable. All dynamic categories and products are correctly linked to the front-end listing page.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
