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
            @php
                $sidebarConversations = \App\Models\Conversation::where('sender_id', auth()->id())
                    ->orWhere('receiver_id', auth()->id())
                    ->with(['sender', 'receiver', 'messages' => function($q) {
                        $q->latest()->limit(1);
                    }])
                    ->latest('last_message_at')
                    ->take(10)
                    ->get();
                $sidebarUnreadTotal = \App\Models\Message::where('is_read', false)
                    ->whereHas('conversation', function($q) {
                        $q->where('sender_id', auth()->id())->orWhere('receiver_id', auth()->id());
                    })
                    ->where('user_id', '!=', auth()->id())
                    ->count();
            @endphp

            @if($sidebarConversations->count())
                @if($sidebarUnreadTotal > 0)
                    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: #1d4ed8; font-weight: 600;">
                        <i class="fa-solid fa-bell"></i> {{ $sidebarUnreadTotal }} unread message{{ $sidebarUnreadTotal > 1 ? 's' : '' }}
                    </div>
                @endif
                <div class="mini-cart-list">
                    @foreach($sidebarConversations as $sConv)
                        @php
                            $sOtherUser = $sConv->sender_id === auth()->id() ? $sConv->receiver : $sConv->sender;
                            $sLastMsg = $sConv->messages->first();
                            $sUnread = \App\Models\Message::where('conversation_id', $sConv->id)
                                ->where('user_id', '!=', auth()->id())
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        <a href="{{ route('user.messages.chat', $sConv->id) }}" class="mini-cart-item" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f1f5f9;">
                            @if($sOtherUser->profile_image)
                                <img src="{{ asset('storage/' . $sOtherUser->profile_image) }}" alt="{{ $sOtherUser->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2px solid #e2e8f0;">
                            @else
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; flex-shrink: 0;">
                                    {{ strtoupper(substr($sOtherUser->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="m-item-info" style="flex: 1; min-width: 0;">
                                <h5 style="margin: 0 0 2px; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                    {{ $sOtherUser->name }}
                                    @if($sOtherUser->role === 'admin')
                                        <i class="fa-solid fa-circle-check" style="color: #3b82f6; font-size: 11px;"></i>
                                    @endif
                                    @if($sUnread > 0)
                                        <span style="margin-left: auto; width: 18px; height: 18px; border-radius: 50%; background: #3b82f6; color: white; font-size: 9px; font-weight: 700; display: flex; align-items: center; justify-content: center;">{{ $sUnread }}</span>
                                    @endif
                                </h5>
                                <span style="font-size: 12px; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">
                                    @if($sLastMsg)
                                        {{ $sLastMsg->user_id === auth()->id() ? 'You: ' : '' }}{{ $sLastMsg->type === 'image' ? '📷 Image' : Str::limit($sLastMsg->message, 30) }}
                                    @else
                                        Start a conversation
                                    @endif
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="cart-sidebar-footer">
                    <a href="{{ route('user.messages') }}" class="btn-pry-blue">View All Messages</a>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-regular fa-comment-dots"></i>
                    <p>No messages yet.</p>
                    <a href="{{ route('user.messages') }}" class="btn-pry-blue" style="display: inline-block; width: auto; padding: 10px 20px; margin-top: 10px;">Start Chat with Support</a>
                </div>
            @endif
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
                $recentOrders = auth()->user()->orders()->with('items.product')->latest()->take(5)->get();
            @endphp
            @if($recentOrders->count())
                <div class="mini-cart-list">
                    @foreach($recentOrders as $order)
                        @php
                            $statusColors = [
                                'pending' => ['bg' => '#fff8eb', 'color' => '#a16207'],
                                'approved' => ['bg' => '#e0f2fe', 'color' => '#0369a1'],
                                'processing' => ['bg' => '#f3e8ff', 'color' => '#7e22ce'],
                                'shipped' => ['bg' => '#e7f0ff', 'color' => '#0d6efd'],
                                'delivered' => ['bg' => '#dcfce7', 'color' => '#166534'],
                                'cancelled' => ['bg' => '#fee2e2', 'color' => '#991b1b'],
                            ];
                            $sc = $statusColors[$order->status] ?? $statusColors['pending'];
                        @endphp
                        <a href="{{ route('user.orders.show', $order->id) }}" class="mini-cart-item" style="text-decoration: none; color: inherit; display: flex; align-items: center; gap: 12px; padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                            {{-- Product image stack --}}
                            <div style="display: flex; align-items: center; flex-shrink: 0;">
                                @foreach($order->items->take(2) as $idx => $item)
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="" style="width: 36px; height: 36px; border-radius: 8px; object-fit: cover; border: 2px solid #fff; {{ $idx > 0 ? 'margin-left: -10px;' : '' }} box-shadow: 0 1px 3px rgba(0,0,0,0.06);">
                                    @else
                                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #f1f5f9; border: 2px solid #fff; {{ $idx > 0 ? 'margin-left: -10px;' : '' }} display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 11px;">
                                            <i class="fa-solid fa-box"></i>
                                        </div>
                                    @endif
                                @endforeach
                                @if($order->items->count() > 2)
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #f1f5f9; border: 2px solid #fff; margin-left: -10px; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700; color: #64748b;">
                                        +{{ $order->items->count() - 2 }}
                                    </div>
                                @endif
                            </div>
                            <div class="m-item-info" style="flex: 1; min-width: 0;">
                                <h5 style="margin: 0 0 3px; font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: space-between;">
                                    <span>Order #{{ $order->id }}</span>
                                    <span style="font-weight: 800; color: #0f172a; font-size: 13px;">${{ number_format($order->total_amount, 2) }}</span>
                                </h5>
                                <div style="display: flex; align-items: center; justify-content: space-between;">
                                    <span style="font-size: 11px; color: #94a3b8;">{{ $order->created_at->diffForHumans() }}</span>
                                    <span style="padding: 2px 8px; border-radius: 12px; background: {{ $sc['bg'] }}; color: {{ $sc['color'] }}; font-weight: 600; font-size: 10px;">{{ ucfirst($order->status) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="cart-sidebar-footer">
                    <a href="{{ route('user.orders') }}" class="btn-pry-blue">View All Orders</a>
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
