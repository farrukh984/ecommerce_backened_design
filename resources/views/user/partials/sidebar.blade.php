<aside class="dashboard-sidebar">
    <div class="user-profile-mini">
        <div class="user-avatar" style="overflow: hidden;">
            @if(auth()->user()->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
            @else
                {{ substr(auth()->user()->name, 0, 1) }}
            @endif
        </div>
        <div class="user-info">
            <h4>{{ auth()->user()->name }}</h4>
            <span>Customer Account</span>
        </div>
    </div>

    <!-- Sidebar Profile Strength -->
    <div style="padding: 0 20px; margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; font-size: 11px;">
            <span style="font-weight: 600; color: #64748b;">Profile Strength</span>
            <span style="font-weight: 700; color: var(--primary-color);">{{ auth()->user()->profile_completion }}%</span>
        </div>
        <div style="width: 100%; height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
            <div style="width: {{ auth()->user()->profile_completion }}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #06b6d4); border-radius: 3px;"></div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('user.dashboard') }}" class="nav-item {{ ($active ?? '') === 'overview' ? 'active' : '' }}">
            <i class="fa-solid fa-house-chimney"></i> My Overview
        </a>

        <a href="{{ route('home') }}" class="nav-item {{ ($active ?? '') === 'home' ? 'active' : '' }}">
            <i class="fa-solid fa-house-chimney"></i> Home
        </a>

        <a href="{{ route('user.orders') }}" class="nav-item {{ ($active ?? '') === 'orders' ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping"></i> My Orders
        </a>
        <a href="{{ route('user.wishlist') }}" class="nav-item {{ ($active ?? '') === 'wishlist' ? 'active' : '' }}">
            <i class="fa-solid fa-heart"></i> Wishlist
        </a>
        <a href="{{ route('user.profile') }}" class="nav-item {{ ($active ?? '') === 'profile' ? 'active' : '' }}">
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
