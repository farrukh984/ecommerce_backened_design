<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_premium.css') }}">
    
    <!-- GSAP for Smooth Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
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
                <button class="sidebar-close md-only" onclick="toggleSidebar()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
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
                    @php
                        $unviewedOrdersCount = \App\Models\Order::where('is_viewed', false)->count();
                    @endphp
                    @if($unviewedOrdersCount > 0)
                        <span style="background: white; color: var(--admin-primary); border-radius: 6px; padding: 2px 8px; font-size: 10px; font-weight: 800; margin-left: auto; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">{{ $unviewedOrdersCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-group"></i> User Base
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="menu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-star"></i> Product Reviews
                    @php
                        $unviewedReviewsCount = \App\Models\ProductReview::where('is_viewed', false)->count();
                    @endphp
                    @if($unviewedReviewsCount > 0)
                        <span style="background: white; color: var(--admin-primary); border-radius: 6px; padding: 2px 8px; font-size: 10px; font-weight: 800; margin-left: auto; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">{{ $unviewedReviewsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.messages.index') }}" class="menu-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-comments"></i> Inquiries
                    @php
                        $unreadAdminCount = \App\Models\Message::where('is_read', false)
                            ->where('user_id', '!=', auth()->id())
                            ->whereHas('conversation', function($q) {
                                $q->where('sender_id', auth()->id())
                                  ->orWhere('receiver_id', auth()->id());
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

        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <main class="admin-main">
            
            <!-- Topbar -->
            <div class="admin-topbar">
                <div class="topbar-left">
                    <button class="mobile-toggle md-only" onclick="toggleSidebar()">
                        <i class="fa-solid fa-bars-staggered"></i>
                    </button>
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
        // Sidebar toggle logic - Using CSS for position for maximum stability
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const isMobile = window.innerWidth <= 991;
            
            if (sidebar.classList.contains('active')) {
                // Close
                sidebar.classList.remove('active');
                if (isMobile) {
                    gsap.to(overlay, { opacity: 0, display: 'none', duration: 0.3 });
                }
            } else {
                // Open
                sidebar.classList.add('active');
                if (isMobile) {
                    gsap.set(overlay, { display: 'block', opacity: 0 });
                    gsap.to(overlay, { opacity: 1, duration: 0.3 });
                }
            }
        }

        // Entrance animations for content
        document.addEventListener('DOMContentLoaded', () => {
            const isMobile = window.innerWidth <= 991;

            // Note: Removed sidebar entrance animation to ensure stability
            
            // Subtle menu hover effect
            document.querySelectorAll('.menu-item').forEach(item => {
                item.addEventListener('mouseenter', () => {
                    const icon = item.querySelector('i');
                    if(icon) gsap.to(icon, { x: 5, duration: 0.3, ease: "power2.out" });
                });
                item.addEventListener('mouseleave', () => {
                    const icon = item.querySelector('i');
                    if(icon) gsap.to(icon, { x: 0, duration: 0.3, ease: "power2.out" });
                });
            });
        });
    </script>
</body>
</html>
