@php
    $active = $active ?? '';
    $user = auth()->user();
    $unreadMessages = \App\Models\Message::where('is_read', false)
        ->whereHas('conversation', function($q) {
            $q->where('receiver_id', auth()->id());
        })->count();

    $pendingOrdersCount = $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count();
@endphp

<aside class="dashboard-sidebar">
    <div class="sidebar-home-link">
        <a href="{{ route('home') }}" class="home-btn-premium">
            <i class="fa-solid fa-arrow-left"></i> Back to Home
        </a>
    </div>

    <div class="sidebar-profile-card">
        <div class="profile-avatar-wrapper">
            @if($user->profile_image)
                <img src="{{ asset('storage/' . $user->profile_image) }}" class="profile-avatar" alt="Profile">
            @else
                <div class="profile-avatar" style="display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #3b82f6, #06b6d4); color: white; font-weight: 800; font-size: 32px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div style="position: absolute; bottom: 5px; right: 5px; width: 18px; height: 18px; background: #10b981; border: 3px solid #fff; border-radius: 50%;"></div>
        </div>
        <h3 class="profile-name">{{ $user->name }}</h3>
        <p class="profile-email">{{ $user->email }}</p>
        
        @if($user->rank)
        <div style="margin-top: 15px;">
            <span style="background: {{ $user->rank === 'Gold' ? '#fef3c7' : '#f1f5f9' }}; color: {{ $user->rank === 'Gold' ? '#92400e' : '#475569' }}; padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                {{ $user->rank }} Member
            </span>
        </div>
        @endif
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('user.dashboard') }}" class="sidebar-link {{ $active === 'overview' ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i> Overview
        </a>
        <a href="{{ route('user.orders') }}" class="sidebar-link {{ $active === 'orders' ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping"></i> My Orders
            @if($pendingOrdersCount > 0)
                <span style="margin-left: auto; background: rgba(255,255,255,0.2); color: white; border-radius: 6px; padding: 2px 8px; font-size: 10px; font-weight: 700;">{{ $pendingOrdersCount }}</span>
            @endif
        </a>
        <a href="{{ route('user.wishlist') }}" class="sidebar-link {{ $active === 'wishlist' ? 'active' : '' }}">
            <i class="fa-solid fa-heart"></i> Wishlist
        </a>
        <a href="{{ route('user.messages') }}" class="sidebar-link {{ $active === 'messages' ? 'active' : '' }}">
            <i class="fa-solid fa-comment-dots"></i> Messages
            @if($unreadMessages > 0)
                <span style="margin-left: auto; background: #ef4444; color: white; border-radius: 6px; padding: 2px 8px; font-size: 10px; font-weight: 700;">{{ $unreadMessages }}</span>
            @endif
        </a>
        <a href="{{ route('user.profile') }}" class="sidebar-link {{ $active === 'profile' ? 'active' : '' }}">
            <i class="fa-solid fa-user-gear"></i> Account Settings
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
            @csrf
            <button type="submit" class="sidebar-link" style="width: 100%; border: none; background: transparent; cursor: pointer; color: #ef4444;">
                <i class="fa-solid fa-right-from-bracket"></i> Sign Out
            </button>
        </form>
    </div>
</aside>
