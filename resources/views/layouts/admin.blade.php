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
                    <i class="fa-solid fa-lock"></i>
                </div>
                <span>ADMIN PANEL</span>
            </div>

            <div class="sidebar-menu">
                <div class="menu-label">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Overview
                </a>
                
                <div class="menu-label" style="margin-top: 24px;">Catalog</div>
                <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box"></i> Products
                </a>
                <a href="{{ route('admin.categories.index') }}" class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-list"></i> Categories
                </a>
                <a href="{{ route('admin.brands.index') }}" class="menu-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-copyright"></i> Brands
                </a>

                <div class="menu-label" style="margin-top: 24px;">Management</div>
                <a href="{{ route('admin.orders.index') }}" class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-bag-shopping"></i> Orders
                </a>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i> Users
                </a>
                <a href="{{ route('admin.messages.index') }}" class="menu-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comment-dots"></i> Messages
                    @php
                        $unreadAdminCount = \App\Models\Message::where('is_read', false)
                            ->whereHas('conversation', function($q) {
                                $q->where('receiver_id', auth()->id());
                            })->count();
                    @endphp
                    @if($unreadAdminCount > 0)
                        <span style="background: var(--admin-primary); color: white; border-radius: 50%; padding: 2px 8px; font-size: 10px; margin-left: auto;">{{ $unreadAdminCount }}</span>
                    @endif
                </a>

                <div class="menu-label" style="margin-top: 24px;">Settings</div>
                <a href="{{ route('admin.features.index') }}" class="menu-item {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders"></i> Features
                </a>
                <a href="{{ route('admin.conditions.index') }}" class="menu-item {{ request()->routeIs('admin.conditions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Conditions
                </a>
                <a href="{{ route('admin.profile') }}" class="menu-item {{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-gear"></i> Profile Settings
                </a>

                <div class="menu-label" style="margin-top: 24px;">Actions</div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-item" style="color: #eb001b;">
                    <i class="fa-solid fa-right-from-bracket" style="color: #eb001b;"></i> Logout
                </a>

                <!-- Sidebar Profile Strength -->
                <div style="padding: 10px 16px; margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; font-size: 11px;">
                        <span style="font-weight: 600; color: #64748b;">Profile Strength</span>
                        <span style="font-weight: 700; color: var(--admin-primary);">{{ auth()->user()->profile_completion }}%</span>
                    </div>
                    <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden; border: 1px solid #e2e8f0;">
                        <div style="width: {{ auth()->user()->profile_completion }}%; height: 100%; background: linear-gradient(90deg, var(--admin-primary), var(--admin-secondary)); border-radius: 3px;"></div>
                    </div>
                </div>
            </div>

            <div class="sidebar-footer">
                <a href="{{ route('admin.profile') }}" class="user-profile-badge" style="text-decoration: none;">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" style="width: 28px; height: 28px; border-radius: 50%; object-fit: cover;">
                    @else
                        <div class="user-avatar-small">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    @endif
                    <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
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
                        <a href="{{ route('admin.products.index') }}" class="stock-warning-pill" style="margin-right: 15px; background: #fff7ed; border: 1px solid #ffedd5; color: #9a3412; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                            <i class="fa-solid fa-triangle-exclamation" style="color: #ea580c;"></i>
                            {{ $lowStockCount }} Low Stock Items
                        </a>
                    @endif
                    <a href="/" target="_blank" class="btn-outline" style="text-decoration: none;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Visit Site
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
