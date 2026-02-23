<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_premium.css') }}">
    
    @yield('styles')
</head>
<body>

    <div class="admin-layout">
        
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-logo">
                <div class="logo-box">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <span>ADMIN PANEL</span>
            </div>

            <div class="sidebar-menu">
                <div class="menu-label">Analytics</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i> Overview
                </a>
                
                <div class="menu-label">Catalog Management</div>
                <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-boxes-stacked"></i> Products
                </a>
                <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-indent"></i> Categories
                </a>
                <a href="{{ route('admin.brands.index') }}" class="menu-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-copyright"></i> Brands
                </a>
                <a href="{{ route('admin.deals.index') }}" class="menu-item {{ request()->routeIs('admin.deals.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-fire"></i> Deals & Offers
                </a>

                <div class="menu-label">Customer Operations</div>
                <a href="{{ route('admin.orders.index') }}" class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-receipt"></i> Orders
                </a>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i> User Base
                </a>
                <a href="{{ route('admin.messages.index') }}" class="menu-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i> Inquiries
                    @php
                        $unreadAdminCount = \App\Models\Message::where('is_read', false)
                            ->whereHas('conversation', function($q) {
                                $q->where('receiver_id', auth()->id());
                            })->count();
                    @endphp
                    @if($unreadAdminCount > 0)
                        <span style="background: white; color: var(--admin-primary); border-radius: 6px; padding: 2px 8px; font-size: 10px; font-weight: 800; margin-left: auto; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">{{ $unreadAdminCount }}</span>
                    @endif
                </a>

                <div class="menu-label">System</div>
                <a href="{{ route('admin.features.index') }}" class="menu-item {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders"></i> Site Features
                </a>
                <a href="{{ route('admin.conditions.index') }}" class="menu-item {{ request()->routeIs('admin.conditions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Tag Conditions
                </a>
                <a href="{{ route('admin.profile') }}" class="menu-item {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-shield"></i> Account Settings
                </a>

                <div class="menu-label">Session</div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-item" style="color: #ef4444;">
                    <i class="fa-solid fa-power-off" style="color: #ef4444;"></i> Secure Logout
                </a>
            </div>

            <div class="sidebar-footer">
                <a href="{{ route('admin.profile') }}" class="user-profile-badge" style="text-decoration: none;">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" style="width: 32px; height: 32px; border-radius: 10px; object-fit: cover; border: 2px solid white;">
                    @else
                        <div class="user-avatar-small">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    @endif
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-weight: 800; font-size: 13px; line-height: 1;">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        <span style="font-size: 10px; color: var(--admin-text-sub); margin-top: 2px;">Administrator</span>
                    </div>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            
            <!-- Topbar -->
            <div class="admin-topbar">
                <div class="topbar-left">
                    <h1>@yield('header_title', 'Dashboard')</h1>
                </div>
                <div class="topbar-right">
                    @php
                        $lowStockCount = \App\Models\Product::where('stock_quantity', '<', 10)->count();
                    @endphp
                    @if($lowStockCount > 0)
                        <a href="{{ route('admin.products.index') }}" class="stock-warning-pill" style="margin-right: 15px; background: #fff7ed; border: 1px solid #ffedd5; color: #9a3412; padding: 10px 18px; border-radius: 14px; font-size: 12px; font-weight: 800; display: flex; align-items: center; gap: 10px; text-decoration: none; box-shadow: 0 4px 10px rgba(234, 88, 12, 0.1);">
                            <i class="fa-solid fa-triangle-exclamation" style="color: #ea580c; font-size: 14px;"></i>
                            {{ $lowStockCount }} Low Stock Alerts
                        </a>
                    @endif
                    <a href="/" target="_blank" class="btn-outline" style="text-decoration: none;">
                        <i class="fa-solid fa-globe"></i> Visit Website
                    </a>
                </div>
            </div>

            <!-- Page Content -->
            @yield('admin_content')

        </main>
    </div>

    @yield('scripts')
    
    <script>
        // Sidebar toggle for mobile if needed
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
    </script>
</body>
</html>
