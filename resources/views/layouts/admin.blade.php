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

                <div class="menu-label" style="margin-top: 24px;">Settings</div>
                <a href="{{ route('admin.features.index') }}" class="menu-item {{ request()->routeIs('admin.features.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-sliders"></i> Features
                </a>
                <a href="{{ route('admin.conditions.index') }}" class="menu-item {{ request()->routeIs('admin.conditions.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-tags"></i> Conditions
                </a>

                <div class="menu-label" style="margin-top: 24px;">Actions</div>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="menu-item" style="color: #eb001b;">
                    <i class="fa-solid fa-right-from-bracket" style="color: #eb001b;"></i> Logout
                </a>
            </div>

            <div class="sidebar-footer">
                <div class="user-profile-badge">
                    <div class="user-avatar-small">{{ substr(auth()->user()->name, 0, 1) }}</div>
                    <span>{{ explode(' ', auth()->user()->name)[0] }}</span>
                </div>
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
