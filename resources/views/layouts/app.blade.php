<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brand - Ecommerce</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Brand Ecommerce - Shop the latest trending electronic items, deals and offers">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS (order matters) -->
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebars.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    @yield('styles')

    <!-- Theme JS (runs before body to prevent flash) -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Loader CSS -->
    <style>
        /* ══════════════════════════════════════════════════════════
           GLOBAL PAGE LOADER (PREMIUM)
           ══════════════════════════════════════════════════════════ */
        #global-loader {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 999999;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.6s;
        }
        [data-theme="dark"] #global-loader {
            background: #0f172a; /* MATCH var(--bg-body) */
        }
        
        .loader-boxes {
            position: relative;
            width: 60px; height: 60px;
        }
        .loader-boxes .l-box {
            position: absolute;
            width: 24px; height: 24px;
            background: linear-gradient(135deg, #0ea5e9, #4f46e5);
            border-radius: 6px;
            animation: l-box-move 1.5s infinite ease-in-out;
            box-shadow: 0 4px 15px rgba(14, 165, 233, 0.4);
        }
        .loader-boxes .l-box:nth-child(1) { top: 0; left: 0; animation-delay: 0s; }
        .loader-boxes .l-box:nth-child(2) { top: 0; right: 0; animation-delay: -0.375s; }
        .loader-boxes .l-box:nth-child(3) { bottom: 0; right: 0; animation-delay: -0.75s; }
        .loader-boxes .l-box:nth-child(4) { bottom: 0; left: 0; animation-delay: -1.125s; }

        @keyframes l-box-move {
            0%, 100% { transform: scale(1) rotate(0deg); opacity:1; }
            50% { transform: scale(0.5) rotate(90deg); opacity:0.5; }
        }

        .loader-text {
            margin-top: 24px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
            letter-spacing: 3px;
            text-transform: uppercase;
            animation: loader-pulse 1.5s ease-in-out infinite;
            font-family: 'Outfit', sans-serif;
        }
        [data-theme="dark"] .loader-text {
            color: #94a3b8;
        }
        @keyframes loader-pulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 1; }
        }
        body.is-loading {
            overflow: hidden !important;
        }
        #global-loader.hide {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    </style>
</head>
<body class="@yield('body_class') is-loading">

    <!-- Global Preloader -->
    <div id="global-loader">
        <div class="loader-boxes">
            <div class="l-box"></div>
            <div class="l-box"></div>
            <div class="l-box"></div>
            <div class="l-box"></div>
        </div>
        <div class="loader-text">ShopBrand</div>
    </div>

    <script>
        // Once everything is loaded (CSS, Images, etc.), hide the loader
        window.addEventListener('load', function() {
            hideLoader();
        });

        // Failsafe: Hide loader after 3 seconds anyway
        setTimeout(hideLoader, 3000);

        function hideLoader() {
            const loader = document.getElementById('global-loader');
            if (loader && !loader.classList.contains('hide')) {
                loader.classList.add('hide');
                document.body.classList.remove('is-loading');
            }
        }

        // ──────── IMMEDIATE TRANSITION LOGIC ────────
        // Show loader IMMEDIATELY when a link is clicked or form submitted
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && 
                link.href && 
                !link.href.startsWith('#') && 
                !link.href.includes('javascript:') &&
                !link.getAttribute('target') && 
                !e.ctrlKey && !e.shiftKey && !e.metaKey &&
                link.hostname === window.location.hostname) {
                
                const loader = document.getElementById('global-loader');
                if(loader) {
                    loader.classList.remove('hide');
                    document.body.classList.add('is-loading');
                }
            }
        });

        // Show on form submit
        document.addEventListener('submit', function(e) {
            // Stay hidden if standard submission is prevented (e.g., AJAX handling)
            if (e.defaultPrevented) return;

            const loader = document.getElementById('global-loader');
            if(loader) {
                loader.classList.remove('hide');
                document.body.classList.add('is-loading');
            }
        });

        // Hide when navigating back (BFcache)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                const loader = document.getElementById('global-loader');
                if(loader) {
                    loader.classList.add('hide');
                    document.body.classList.remove('is-loading');
                }
            }
        });
    </script>

    @hasSection('hide_chrome')
    @else
        @include('partials.navbar')
    @endif

    <main>
        @yield('content')
    </main>

    @hasSection('hide_chrome')
    @else
        @include('partials.footer')
        @include('partials.sidebars')
    @endif

    @hasSection('hide_chrome')
    @else
        <script src="{{ asset('js/sidebars.js') }}" defer></script>
    @endif

    @auth
    <!-- Premium Floating Chat Widget -->
    <div class="admin-chat-widget">
        <!-- Floating Toggle Button -->
        <button class="chat-launcher" id="chatLauncher">
            <div class="launcher-icon">
                <i class="fa-solid fa-comment-dots"></i>
            </div>
            @if(isset($unreadUserCount) && $unreadUserCount > 0)
                <span class="unread-badge pulse" id="quickUnreadBadge">{{ $unreadUserCount }}</span>
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
                        <h4 id="chatPopupTitle">Support Chat</h4>
                        <p><span class="online-indicator"></span><span id="chatPopupSub">Active Now</span></p>
                    </div>
                </div>
                <div class="chat-actions">
                    <button id="backToConversations" style="display: none;"><i class="fa-solid fa-chevron-left"></i></button>
                    <a href="{{ route('user.messages') }}" title="Open Full Inbox"><i class="fa-solid fa-expand"></i></a>
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
    <link rel="stylesheet" href="{{ asset('css/admin_chat_widget.css') }}">
    @endauth

    <!-- GSAP for Smooth Animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js" defer></script>

    @auth
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const chatLauncher = document.getElementById('chatLauncher');
            const chatPopup = document.getElementById('chatPopup');
            const closeChat = document.getElementById('closeChat');
            const backBtn = document.getElementById('backToConversations');
            const chatBody = document.getElementById('quickChatBody');
            const chatFooter = document.getElementById('chatPopupFooter');
            const sendForm = document.getElementById('quickSendMessageForm');
            let currentConvId = null;
            let currentCuAvatar = '';
            let currentCuInitial = '';
            let pollingInterval = null;

            const myAvatar = "{{ auth()->user()->profile_image ? display_image(auth()->user()->profile_image) : '' }}";
            const myInitial = "{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}";

            chatLauncher?.addEventListener('click', () => {
                const isActive = chatPopup.style.display === 'flex';
                if (!isActive) {
                    gsap.set(chatPopup, { display: 'flex', opacity: 0, scale: 0.8, y: 50 });
                    gsap.to(chatPopup, { opacity: 1, scale: 1, y: 0, duration: 0.5, ease: "back.out(1.7)" });
                    loadConversations();
                } else {
                    closeChatAction();
                }
            });

            closeChat?.addEventListener('click', closeChatAction);

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
                document.getElementById('chatPopupTitle').textContent = 'Support Chat';
                document.getElementById('chatPopupSub').textContent = 'Active Now';
                document.getElementById('quickChatAvatar').innerHTML = '<i class="fa-solid fa-headset"></i>';

                fetch('{{ route("user.messages") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const conversations = doc.querySelectorAll('.conv-item');
                    
                    chatBody.innerHTML = '';
                    if (conversations.length === 0) {
                        chatBody.innerHTML = '<div class="loader-container"><p style="font-size: 13px; color: #94a3b8;">No messages yet</p></div>';
                        return;
                    }

                    conversations.forEach(conv => {
                        const name = conv.querySelector('.c-name')?.textContent.trim() || 'Admin';
                        const lastMsg = conv.querySelector('.c-last')?.textContent.trim() || '';
                        const time = conv.querySelector('.c-time')?.textContent.trim() || '';
                        const unread = conv.querySelector('.c-badge')?.textContent.trim() || '';
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
                currentCuAvatar = avatar;
                currentCuInitial = initial;
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

            backBtn?.addEventListener('click', loadConversations);

            function loadMessages(id) {
                fetch(`{{ url('dashboard/messages') }}/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const rows = doc.querySelectorAll('.msg-row');
                    chatBody.innerHTML = '';
                    rows.forEach(row => {
                        const isSent = row.classList.contains('sent');
                        const text = row.querySelector('.b-text')?.textContent || row.querySelector('.bubble')?.textContent || '';
                        const time = row.querySelector('.b-meta')?.textContent || '';
                        
                        const avHtml = isSent 
                            ? (myAvatar ? `<img src="${myAvatar}" class="q-sub-avatar">` : `<div class="q-sub-avatar-ph">${myInitial}</div>`)
                            : (currentCuAvatar ? `<img src="${currentCuAvatar}" class="q-sub-avatar">` : `<div class="q-sub-avatar-ph">${currentCuInitial}</div>`);

                        const msgRow = document.createElement('div');
                        msgRow.className = `q-msg-row ${isSent ? 'sent' : 'received'}`;
                        
                        let inner = '';
                        if(!isSent) inner += avHtml;
                        inner += `<div class="q-msg-bubble ${isSent ? 'sent' : 'received'}"><div>${text}</div><span class="q-msg-time">${time}</span></div>`;
                        if(isSent) inner += avHtml;
                        
                        msgRow.innerHTML = inner;
                        chatBody.appendChild(msgRow);
                    });
                    chatBody.scrollTop = chatBody.scrollHeight;
                });
            }

            function pollMessages(id) {
                fetch(`{{ url('dashboard/messages') }}/${id}/poll`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            const isSent = msg.user_id == {{ auth()->id() }};
                            const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                            
                            const avHtml = isSent 
                                ? (myAvatar ? `<img src="${myAvatar}" class="q-sub-avatar">` : `<div class="q-sub-avatar-ph">${myInitial}</div>`)
                                : (currentCuAvatar ? `<img src="${currentCuAvatar}" class="q-sub-avatar">` : `<div class="q-sub-avatar-ph">${currentCuInitial}</div>`);

                            const msgRow = document.createElement('div');
                            msgRow.className = `q-msg-row ${isSent ? 'sent' : 'received'}`;
                            
                            let inner = '';
                            if(!isSent) inner += avHtml;
                            inner += `<div class="q-msg-bubble ${isSent ? 'sent' : 'received'}"><div>${msg.message}</div><span class="q-msg-time">${time}</span></div>`;
                            if(isSent) inner += avHtml;
                            
                            msgRow.innerHTML = inner;
                            chatBody.appendChild(msgRow);
                        });
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }
                });
            }

            sendForm?.addEventListener('submit', (e) => {
                e.preventDefault();
                const input = document.getElementById('quickMsgInput');
                if (!input.value.trim()) return;
                const msg = input.value;
                input.value = '';

                fetch('{{ route("user.messages.send") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: new FormData(sendForm)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        const avHtml = myAvatar ? `<img src="${myAvatar}" class="q-sub-avatar">` : `<div class="q-sub-avatar-ph">${myInitial}</div>`;
                        const msgRow = document.createElement('div');
                        msgRow.className = 'q-msg-row sent';
                        msgRow.innerHTML = `<div class="q-msg-bubble sent"><div>${msg}</div><span class="q-msg-time">${time}</span></div>${avHtml}`;
                        chatBody.appendChild(msgRow);
                        chatBody.scrollTop = chatBody.scrollHeight;
                    }
                });
            });
        });
    </script>
    @endauth

    <!-- Global SweetAlert2 Handling -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const theme = document.documentElement.getAttribute('data-theme') || 'light';
            const swalConfig = {
                background: theme === 'dark' ? '#1f2937' : '#fff',
                color: theme === 'dark' ? '#f3f4f6' : '#1f2937'
            };

            @if(session('success'))
                Swal.fire({
                    ...swalConfig,
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    timer: 4000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    ...swalConfig,
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}"
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    ...swalConfig,
                    icon: 'error',
                    title: 'Validation Error',
                    text: "{{ $errors->first() }}"
                });
            @endif

            window.confirmAction = function(e, options = {}) {
                e.preventDefault();
                const form = e.target.closest('form');
                
                Swal.fire({
                    title: options.title || 'Are you sure?',
                    text: options.text || "This action cannot be undone!",
                    icon: options.icon || 'warning',
                    showCancelButton: true,
                    confirmButtonColor: options.confirmColor || '#0ea5e9',
                    cancelButtonColor: options.cancelColor || '#64748b',
                    confirmButtonText: options.confirmText || 'Yes, proceed!',
                    ...swalConfig
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            };
        });
    </script>

    @yield('scripts')

</body>
</html>
