<!-- Global Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<!-- Profile Sidebar -->
<div id="profileSidebar" class="global-sidebar">
    <div class="sidebar-header">
        <h3>My Profile</h3>
        <button class="close-sidebar"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="sidebar-content">
        @auth
            <div class="user-profile-header">
                <div class="profile-avatar">
                    @if(auth()->user()->profile_image)
                        <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    @else
                        {{ substr(auth()->user()->name, 0, 1) }}
                    @endif
                </div>
                <div class="profile-info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->email }}</span>
                </div>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('user.profile') }}"><i class="fa-regular fa-user"></i> Account Settings</a></li>
                <li><a href="{{ route('user.profile') }}"><i class="fa-solid fa-location-dot"></i> Shipping Address</a></li>
                <li><a href="{{ route('user.profile') }}"><i class="fa-solid fa-gear"></i> Preferences</a></li>
                <li class="menu-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                    </form>
                </li>
            </ul>
        @else
            <div class="auth-required">
                <i class="fa-solid fa-lock"></i>
                <p>Please login to see your profile details.</p>
                <a href="{{ route('login') }}" class="btn-pry-blue">Sign In</a>
                <p class="auth-sub">New here? <a href="{{ route('register') }}">Register now</a></p>
            </div>
        @endauth
    </div>
</div>

<!-- Message Sidebar -->
<div id="messageSidebar" class="global-sidebar">
    <div class="sidebar-header">
        <h3>Messages</h3>
        <button class="close-sidebar"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="sidebar-content">
        @auth
            <div class="empty-state">
                <i class="fa-regular fa-comment-dots"></i>
                <p>No new messages from suppliers yet.</p>
            </div>
        @else
            <div class="auth-required">
                <i class="fa-solid fa-lock"></i>
                <p>Please login to see your messages.</p>
                <a href="{{ route('login') }}" class="btn-pry-blue">Sign In</a>
            </div>
        @endauth
    </div>
</div>

<!-- Orders Sidebar -->
<div id="ordersSidebar" class="global-sidebar">
    <div class="sidebar-header">
        <h3>My Orders</h3>
        <button class="close-sidebar"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="sidebar-content">
        @auth
            @php
                $recentOrders = auth()->user()->orders()->latest()->take(5)->get();
            @endphp
            @if($recentOrders->count())
                <div class="mini-cart-list">
                    @foreach($recentOrders as $order)
                        <div class="mini-cart-item">
                            <div class="m-item-info">
                                <h5>Order #{{ $order->id }}</h5>
                                <span>{{ ucfirst($order->status) }} - ${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="cart-sidebar-footer">
                    <a href="{{ route('user.dashboard') }}" class="btn-pry-blue">View Dashboard</a>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-regular fa-clipboard"></i>
                    <p>You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}" class="btn-pry-blue">Start Shopping</a>
                </div>
            @endif
        @else
            <div class="auth-required">
                <i class="fa-solid fa-lock"></i>
                <p>Please login to track your orders.</p>
                <a href="{{ route('login') }}" class="btn-pry-blue">Sign In</a>
            </div>
        @endauth
    </div>
</div>

<!-- Mini Cart Sidebar -->
<div id="cartSidebar" class="global-sidebar">
    <div class="sidebar-header">
        <h3>My Cart</h3>
        <button class="close-sidebar"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="sidebar-content">
        @php $cart = session()->get('cart', []); @endphp
        @if(count($cart) > 0)
            <div class="mini-cart-list">
                @foreach($cart as $id => $details)
                    <div class="mini-cart-item">
                        <img src="{{ asset('storage/' . $details['image']) }}" alt="{{ $details['name'] }}">
                        <div class="m-item-info">
                            <h5>{{ Str::limit($details['name'], 30) }}</h5>
                            <span>{{ $details['quantity'] }} x ${{ number_format($details['price'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="cart-sidebar-footer">
                @php $total = 0; foreach($cart as $item) $total += $item['price'] * $item['quantity']; @endphp
                <div class="cart-total-row">
                    <span>Total:</span>
                    <strong>${{ number_format($total, 2) }}</strong>
                </div>
                <a href="{{ route('cart') }}" class="btn-pry-blue">View Shopping Cart</a>
            </div>
        @else
            <div class="empty-state">
                <i class="fa-solid fa-cart-shopping"></i>
                <p>Your cart is empty.</p>
                <a href="{{ route('products.index') }}" class="btn-pry-blue">Start Shopping</a>
            </div>
        @endif
    </div>
</div>
