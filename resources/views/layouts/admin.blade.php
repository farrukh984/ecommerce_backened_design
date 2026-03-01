<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin_premium.css') }}">
    
    <!-- GSAP & Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
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
                <a href="{{ route('admin.analytics') }}" class="menu-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Insights
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
                        <img src="{{ display_image(auth()->user()->profile_image) }}" style="width: 32px; height: 32px; border-radius: 10px; object-fit: cover; border: 2px solid white;">
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

    <!-- Premium Floating Chat Widget -->
    <div class="admin-chat-widget">
        <!-- Floating Toggle Button -->
        <button class="chat-launcher" id="chatLauncher">
            <div class="launcher-icon">
                <i class="fa-solid fa-comment-dots"></i>
            </div>
            @if(isset($unreadAdminCount) && $unreadAdminCount > 0)
                <span class="unread-badge pulse" id="quickUnreadBadge">{{ $unreadAdminCount }}</span>
            @endif
        </button>

        <!-- Chat Window -->
        <div class="chat-popup" id="chatPopup">
            <div class="chat-popup-header">
                <div class="user-status">
                    <div class="status-avatar" id="quickChatAvatar">
                        <i class="fa-solid fa-headset"></i>
                    </div>
                    <div class="status-info">
                        <h4 id="chatPopupTitle">Live Support</h4>
                        <p><span class="online-indicator"></span><span id="chatPopupSub">Active Now</span></p>
                    </div>
                </div>
                <div class="chat-actions">
                    <button id="backToConversations" style="display: none;"><i class="fa-solid fa-chevron-left"></i></button>
                    <a href="{{ route('admin.messages.index') }}" title="Open Full Inbox"><i class="fa-solid fa-expand"></i></a>
                    <button id="closeChat"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
            
            <div class="chat-popup-body" id="quickChatBody">
                <div class="loader-container">
                    <div class="chat-loader"></div>
                </div>
            </div>

            <div class="chat-popup-footer" id="chatPopupFooter" style="display: none;">
                <form id="quickSendMessageForm">
                    @csrf
                    <input type="hidden" id="quickConvId" name="conversation_id">
                    <input type="text" placeholder="Type a message..." id="quickMsgInput" name="message" autocomplete="off">
                    <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Chat Widget Styles */
        .admin-chat-widget {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            font-family: 'Outfit', sans-serif;
        }

        .chat-launcher {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .chat-launcher:hover { transform: scale(1.1) rotate(5deg); }

        .unread-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 11px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 10px;
            border: 2px solid white;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .pulse { animation: pulse 2s infinite; }

        .chat-popup {
            position: absolute;
            bottom: 80px;
            right: 0;
            width: 380px;
            height: 550px;
            background: rgba(255, 255, 255, 0.98);
            /* backdrop-filter: blur(15px); */
            border-radius: 30px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.4);
            transform-origin: bottom right;
        }

        .chat-popup-header {
            padding: 24px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .user-status { display: flex; align-items: center; gap: 12px; }
        .status-avatar { width: 44px; height: 44px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .status-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .status-info h4 { margin: 0; font-size: 15px; font-weight: 800; }
        .status-info p { margin: 0; font-size: 11px; opacity: 0.8; display: flex; align-items: center; gap: 4px; }
        .online-indicator { width: 8px; height: 8px; background: #bef264; border-radius: 50%; box-shadow: 0 0 10px #bef264; }

        .chat-actions { display: flex; gap: 10px; }
        .chat-actions button, .chat-actions a {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            cursor: pointer;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            text-decoration: none;
            font-size: 14px;
        }
        .chat-actions button:hover, .chat-actions a:hover { background: rgba(255,255,255,0.2); }

        .chat-popup-body { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 12px; background: #f8fafc; }
        .chat-popup-footer { padding: 20px; background: white; border-top: 1px solid #f1f5f9; }

        #quickSendMessageForm {
            display: flex;
            gap: 10px;
            background: #f1f5f9;
            padding: 6px;
            border-radius: 15px;
            border: 1px solid #e2e8f0;
        }

        #quickSendMessageForm input {
            flex: 1; border: none; background: none; padding: 10px; font-size: 14px; outline: none; color: #1e293b;
        }

        #quickSendMessageForm button {
            width: 40px; height: 40px; border-radius: 12px; background: #2563eb; color: white; border: none; cursor: pointer; transition: 0.3s;
        }

        #quickSendMessageForm button:hover { background: #1d4ed8; transform: scale(1.05); }

        .loader-container { display: flex; height: 100%; align-items: center; justify-content: center; flex-direction: column; gap: 10px; }
        .chat-loader { width: 30px; height: 30px; border: 3px solid #f1f5f9; border-top-color: #2563eb; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .q-conv-item {
            display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 15px; cursor: pointer; transition: 0.3s; border: 1px solid #f1f5f9;
        }
        .q-conv-item:hover { transform: translateY(-2px); border-color: #2563eb; }
        .q-avatar { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }
        .q-avatar-placeholder { width: 40px; height: 40px; border-radius: 10px; background: #e0f2fe; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #0369a1; }
        .q-info { flex: 1; min-width: 0; }
        .q-name { font-weight: 700; font-size: 13px; margin-bottom: 2px; display: flex; justify-content: space-between; }
        .q-msg { font-size: 12px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .q-badge { width: 18px; height: 18px; background: #2563eb; color: white; border-radius: 50%; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; }

        .q-msg-bubble { max-width: 85%; padding: 10px 14px; border-radius: 15px; font-size: 13px; line-height: 1.5; }
        .q-msg-bubble.sent { align-self: flex-end; background: #2563eb; color: white; border-bottom-right-radius: 4px; }
        .q-msg-bubble.received { align-self: flex-start; background: white; color: #1e293b; border-bottom-left-radius: 4px; border: 1px solid #f1f5f9; }
        .q-msg-time { font-size: 9px; opacity: 0.7; margin-top: 4px; display: block; text-align: right; }

        @media (max-width: 480px) {
            .chat-popup { width: calc(100vw - 40px); right: -10px; bottom: 80px; }
        }
    </style>

    @yield('scripts')
    
    <script>
        // Sidebar toggle logic
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const isMobile = window.innerWidth <= 991;
            
            if (sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                if (isMobile) gsap.to(overlay, { opacity: 0, display: 'none', duration: 0.3 });
            } else {
                sidebar.classList.add('active');
                if (isMobile) {
                    gsap.set(overlay, { display: 'block', opacity: 0 });
                    gsap.to(overlay, { opacity: 1, duration: 0.3 });
                }
            }
        }

        // Global Quick Chat Function
        window.openQuickChat = function(id, name, avatar, initial) {
            const launcher = document.getElementById('chatLauncher');
            const popup = document.getElementById('chatPopup');
            
            if (popup.style.display !== 'flex') {
                launcher.click();
            }
            
            // Wait for internal logic to initialize
            setTimeout(() => {
                if (window.triggerOpenConv) {
                    window.triggerOpenConv(id, name, avatar, initial);
                }
            }, 100);
        };

        // Quick Chat Logic
        document.addEventListener('DOMContentLoaded', () => {
            const chatLauncher = document.getElementById('chatLauncher');
            const chatPopup = document.getElementById('chatPopup');
            const closeChat = document.getElementById('closeChat');
            const backBtn = document.getElementById('backToConversations');
            const chatBody = document.getElementById('quickChatBody');
            const chatFooter = document.getElementById('chatPopupFooter');
            const sendForm = document.getElementById('quickSendMessageForm');
            let currentConvId = null;
            let pollingInterval = null;

            window.triggerOpenConv = openConversation;

            chatLauncher.addEventListener('click', () => {
                const isActive = chatPopup.style.display === 'flex';
                if (!isActive) {
                    gsap.set(chatPopup, { display: 'flex', opacity: 0, scale: 0.8, y: 50 });
                    gsap.to(chatPopup, { opacity: 1, scale: 1, y: 0, duration: 0.5, ease: "back.out(1.7)" });
                    loadConversations();
                } else {
                    closeChatAction();
                }
            });

            closeChat.addEventListener('click', closeChatAction);

            function closeChatAction() {
                gsap.to(chatPopup, { opacity: 0, scale: 0.8, y: 50, duration: 0.3, onComplete: () => {
                    chatPopup.style.display = 'none';
                    clearInterval(pollingInterval);
                }});
            }

            function loadConversations() {
                currentConvId = null;
                clearInterval(pollingInterval);
                chatBody.innerHTML = '<div class="loader-container"><div class="chat-loader"></div></div>';
                chatFooter.style.display = 'none';
                backBtn.style.display = 'none';
                document.getElementById('chatPopupTitle').textContent = 'Live Support';
                document.getElementById('chatPopupSub').textContent = 'Active Now';
                document.getElementById('quickChatAvatar').innerHTML = '<i class="fa-solid fa-headset"></i>';

                fetch('{{ route("admin.messages.index") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const conversations = doc.querySelectorAll('.conv-item');
                    
                    chatBody.innerHTML = '';
                    if (conversations.length === 0) {
                        chatBody.innerHTML = '<div class="loader-container"><p style="font-size: 13px; color: #94a3b8;">No active conversations</p></div>';
                        return;
                    }

                    conversations.forEach(conv => {
                        const name = conv.querySelector('.conv-name')?.textContent || 'User';
                        const lastMsg = conv.querySelector('.conv-last-msg')?.textContent || '';
                        const time = conv.querySelector('.conv-time')?.textContent || '';
                        const unread = conv.querySelector('.conv-unread-badge')?.textContent || '';
                        const avatar = conv.querySelector('img')?.src || '';
                        const initial = name.charAt(0).toUpperCase();
                        const href = conv.getAttribute('href');
                        const id = href.substring(href.lastIndexOf('/') + 1);

                        const item = document.createElement('div');
                        item.className = 'q-conv-item';
                        item.innerHTML = `
                            ${avatar ? `<img src="${avatar}" class="q-avatar">` : `<div class="q-avatar-placeholder">${initial}</div>`}
                            <div class="q-info">
                                <div class="q-name"><span>${name}</span><span style="font-size:10px; opacity:0.6;">${time}</span></div>
                                <div class="q-msg">${lastMsg}</div>
                            </div>
                            ${unread ? `<div class="q-badge">${unread}</div>` : ''}
                        `;
                        item.onclick = () => openConversation(id, name, avatar, initial);
                        chatBody.appendChild(item);
                    });
                });
            }

            function openConversation(id, name, avatar, initial) {
                currentConvId = id;
                chatBody.innerHTML = '<div class="loader-container"><div class="chat-loader"></div></div>';
                chatFooter.style.display = 'block';
                backBtn.style.display = 'block';
                document.getElementById('chatPopupTitle').textContent = name;
                document.getElementById('quickConvId').value = id;
                
                if (avatar) {
                    document.getElementById('quickChatAvatar').innerHTML = `<img src="${avatar}">`;
                } else {
                    document.getElementById('quickChatAvatar').innerHTML = `<div class="q-avatar-placeholder" style="width:100%; height:100%; border-radius:0;">${initial}</div>`;
                }

                loadMessages(id);
                pollingInterval = setInterval(() => pollMessages(id), 4000);
            }

            backBtn.addEventListener('click', loadConversations);

            function loadMessages(id) {
                fetch(`{{ url('admin/messages') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const messages = doc.querySelectorAll('.msg-avatar-group');
                    chatBody.innerHTML = '';
                    messages.forEach(msg => {
                        const isSent = msg.classList.contains('sent');
                        const text = msg.querySelector('.msg-bubble div:not(.msg-time)')?.textContent || '';
                        const time = msg.querySelector('.msg-time')?.textContent || '';
                        const bubble = document.createElement('div');
                        bubble.className = `q-msg-bubble ${isSent ? 'sent' : 'received'}`;
                        bubble.innerHTML = `<div>${text}</div><span class="q-msg-time">${time}</span>`;
                        chatBody.appendChild(bubble);
                    });
                    chatBody.scrollTop = chatBody.scrollHeight;
                });
            }

            function pollMessages(id) {
                fetch(`{{ url('admin/messages') }}/${id}/poll`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            const isSent = msg.user_id == {{ auth()->id() }};
                            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            const bubble = document.createElement('div');
                            bubble.className = `q-msg-bubble ${isSent ? 'sent' : 'received'}`;
                            bubble.innerHTML = `<div>${msg.message}</div><span class="q-msg-time">${time}</span>`;
                            chatBody.appendChild(bubble);
                        });
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }
                });
            }

            sendForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const input = document.getElementById('quickMsgInput');
                if (!input.value.trim()) return;
                const msg = input.value;
                input.value = '';

                fetch('{{ route("admin.messages.send") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: new FormData(sendForm)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        const bubble = document.createElement('div');
                        bubble.className = 'q-msg-bubble sent';
                        bubble.innerHTML = `<div>${msg}</div><span class="q-msg-time">${time}</span>`;
                        chatBody.appendChild(bubble);
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }
                });
            });
        });
    </script>
</body>
</html>
