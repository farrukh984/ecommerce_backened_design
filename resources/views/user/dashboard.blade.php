@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">

<div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <aside class="dashboard-sidebar">
        <div class="user-profile-mini">
            <div class="user-avatar">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="user-info">
                <h4>{{ auth()->user()->name }}</h4>
                <span>Customer Account</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="#" class="nav-item active">
                <i class="fa-solid fa-house-chimney"></i> My Overview
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-bag-shopping"></i> My Orders
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-heart"></i> Wishlist
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-location-dot"></i> Addresses
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-credit-card"></i> Payment Methods
            </a>
            <a href="#" class="nav-item">
                <i class="fa-solid fa-user-gear"></i> Profile Settings
            </a>
            
            <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
                @csrf
                <button type="submit" class="nav-item logout-btn" style="background: none; border: none; width: 100%; cursor: pointer;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <main class="dashboard-main">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="banner-content">
                <h1>Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! 🛍️</h1>
                <p>Check your latest orders and exclusive offers.</p>
            </div>
            <i class="fa-solid fa-tags banner-decoration"></i>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="stat-info">
                    <h3>12</h3>
                    <p>Total Orders</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon pink">
                    <i class="fa-solid fa-heart"></i>
                </div>
                <div class="stat-info">
                    <h3>5</h3>
                    <p>Wishlist Items</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <i class="fa-solid fa-gem"></i>
                </div>
                <div class="stat-info">
                    <h3>450</h3>
                    <p>Loyalty Points</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div class="stat-info">
                    <h3>2</h3>
                    <p>Active Coupons</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity Table -->
        <div class="recent-activity">
            <div class="section-header">
                <h2>Recent Order History</h2>
                <a href="#" class="action-btn">View All Orders</a>
            </div>
            
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#7829</td>
                            <td>Feb 14, 2026</td>
                            <td><span class="status-badge completed">Delivered</span></td>
                            <td>$124.00</td>
                            <td><a href="#" class="action-btn">Track</a></td>
                        </tr>
                        <tr>
                            <td>#7821</td>
                            <td>Jan 28, 2026</td>
                            <td><span class="status-badge pending">Processing</span></td>
                            <td>$55.00</td>
                            <td><a href="#" class="action-btn">Track</a></td>
                        </tr>
                        <tr>
                            <td>#7540</td>
                            <td>Dec 15, 2025</td>
                            <td><span class="status-badge canceled">Canceled</span></td>
                            <td>$210.50</td>
                            <td><a href="#" class="action-btn">Details</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection
