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
