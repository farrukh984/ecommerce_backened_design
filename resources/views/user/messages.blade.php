@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<style>
    .chat-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        height: calc(100vh - 60px);
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #f1f5f9;
    }

    /* Conversation List */
    .conv-sidebar {
        border-right: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        background: #fafbfc;
        min-height: 0;
    }
    .conv-sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .conv-sidebar-header h3 {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .conv-list {
        flex: 1;
        overflow-y: auto;
        min-height: 0;
    }
    .conv-list::-webkit-scrollbar {
        width: 4px;
    }
    .conv-list::-webkit-scrollbar-track {
        background: transparent;
    }
    .conv-list::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
    .conv-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f8fafc;
        text-decoration: none;
        color: inherit;
    }
    .conv-item:hover { background: #f1f5f9; }
    .conv-item.active { background: #eff6ff; border-left: 3px solid #3b82f6; }
    .conv-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e2e8f0;
    }
    .conv-avatar-placeholder {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
    }
    .conv-info {
        flex: 1;
        min-width: 0;
    }
    .conv-name {
        font-weight: 700;
        font-size: 14px;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .conv-last-msg {
        font-size: 12px;
        color: #94a3b8;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .conv-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 4px;
    }
    .conv-time {
        font-size: 11px;
        color: #94a3b8;
    }
    .conv-unread-badge {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6, #06b6d4);
        color: white;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Chat Window */
    .chat-window {
        display: flex;
        flex-direction: column;
        background: #fff;
        min-height: 0;
    }
    .chat-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 14px;
        background: #fff;
    }
    .chat-header-info h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
    }
    .chat-header-info .online-status {
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .online-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .online-dot.online { background: #22c55e; box-shadow: 0 0 6px rgba(34,197,94,0.4); }
    .online-dot.offline { background: #94a3b8; }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: linear-gradient(180deg, #f8fafc, #ffffff);
        min-height: 0;
    }
    .chat-messages::-webkit-scrollbar {
        width: 5px;
    }
    .chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }
    .chat-messages::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 10px;
    }
    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
    .msg-bubble {
        max-width: 65%;
        padding: 12px 18px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.6;
        word-wrap: break-word;
        position: relative;
        animation: fadeInMsg 0.3s ease;
    }
    .msg-bubble.sent {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
    .msg-bubble.received {
        background: #f1f5f9;
        color: #1e293b;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
    }
    .msg-time {
        font-size: 10px;
        opacity: 0.7;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .msg-bubble.sent .msg-time { justify-content: flex-end; }
    .msg-image {
        max-width: 280px;
        border-radius: 12px;
        margin-bottom: 6px;
    }
    .msg-avatar-group {
        display: flex;
        align-items: flex-end;
        gap: 10px;
    }
    .msg-avatar-group.sent {
        flex-direction: row-reverse;
    }
    .msg-mini-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #e2e8f0;
    }
    .msg-mini-avatar-placeholder {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        flex-shrink: 0;
    }

    /* Chat Input */
    .chat-input-area {
        padding: 16px 24px;
        border-top: 1px solid #f1f5f9;
        background: #fff;
    }
    .chat-input-form {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8fafc;
        border-radius: 16px;
        padding: 6px 6px 6px 18px;
        border: 1px solid #f1f5f9;
        transition: border-color 0.2s;
    }
    .chat-input-form:focus-within {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
    }
    .chat-input-form input[type="text"] {
        flex: 1;
        border: none;
        background: transparent;
        font-size: 14px;
        outline: none;
        color: #1e293b;
    }
    .chat-input-form input[type="text"]::placeholder { color: #94a3b8; }
    .chat-input-form label {
        cursor: pointer;
        padding: 8px;
        color: #94a3b8;
        transition: color 0.2s;
    }
    .chat-input-form label:hover { color: #3b82f6; }
    .chat-send-btn {
        padding: 10px 20px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .chat-send-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,0.3); }

    .empty-chat {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        gap: 12px;
    }
    .empty-chat i { font-size: 60px; opacity: 0.2; }
    .empty-chat h3 { color: #64748b; margin: 0; }
    .empty-chat p { color: #94a3b8; font-size: 14px; margin: 0; }

    .new-chat-btn {
        padding: 8px 14px;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .new-chat-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59,130,246,0.3);
    }

    .image-preview-container {
        display: none;
        padding: 8px 18px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
    }
    .image-preview-container img {
        max-height: 80px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    @keyframes fadeInMsg {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 900px) {
        .chat-container {
            grid-template-columns: 1fr;
            height: calc(100vh - 120px);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            width: 100% !important;
            margin: 0 !important;
            border-radius: 0;
        }
        
        .conv-sidebar {
            display: flex !important;
            height: {{ isset($conversation) ? '80px' : '100%' }} !important;
            width: 100% !important;
            border-right: none;
            overflow: hidden !important;
            background: #fff;
            flex-shrink: 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            z-index: 10;
        }

        .conv-sidebar-header {
            display: {{ isset($conversation) ? 'none' : 'flex' }} !important;
        }

        .conv-list {
            display: flex !important;
            flex-direction: {{ isset($conversation) ? 'row' : 'column' }} !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            width: 100%;
            height: 100%;
            padding: {{ isset($conversation) ? '10px 5px' : '0' }};
        }

        .conv-item {
            min-width: {{ isset($conversation) ? '160px' : '100%' }} !important;
            max-width: {{ isset($conversation) ? '200px' : '100%' }} !important;
            padding: {{ isset($conversation) ? '8px 12px' : '14px 20px' }} !important;
            border-bottom: {{ isset($conversation) ? 'none' : '1px solid #f1f5f9' }};
            border-right: {{ isset($conversation) ? '1px solid #f8fafc' : 'none' }};
            border-radius: {{ isset($conversation) ? '12px' : '0' }};
            margin: {{ isset($conversation) ? '0 5px' : '0' }};
            justify-content: {{ isset($conversation) ? 'center' : 'flex-start' }};
        }

        .conv-item.active {
            background: #eff6ff !important;
            border-left: {{ isset($conversation) ? 'none' : '3px solid #3b82f6' }} !important;
            border: {{ isset($conversation) ? '1px solid #3b82f6' : 'none' }} !important;
        }

        .conv-last-msg, .conv-meta {
            display: {{ isset($conversation) ? 'none' : 'flex' }} !important;
        }

        .conv-name {
            font-size: {{ isset($conversation) ? '13px' : '14px' }} !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chat-window {
            display: flex !important;
            height: {{ isset($conversation) ? 'calc(100% - 80px)' : '100%' }} !important;
            width: 100% !important;
        }

        .chat-input-area {
            padding: 8px 10px;
        }

        .chat-input-form {
            padding: 5px 5px 5px 15px;
            gap: 8px;
            border-radius: 20px;
        }

        .chat-send-btn {
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            min-width: 40px;
        }

        .chat-send-btn span {
            display: none !important;
        }

        .chat-send-btn i {
            margin-right: -2px;
            font-size: 16px;
        }

        .dashboard-main {
            padding: 0 !important;
        }

        .chat-header {
            padding: 10px 15px;
        }

        .mobile-back-btn {
            display: flex !important;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: #f1f5f9;
            border-radius: 10px;
            color: #475569;
            text-decoration: none;
            margin-right: 5px;
        }
    }
    .mobile-back-btn { display: none; }
</style>

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'messages'])

    <main class="dashboard-main" style="padding: 24px;">
        <div class="chat-container">
            <!-- Conversations List -->
            <div class="conv-sidebar">
                <div class="conv-sidebar-header">
                    <h3>Messages</h3>
                    <a href="{{ route('user.messages') }}" class="new-chat-btn" onclick="event.preventDefault(); startNewAdminChat();">
                        <i class="fa-solid fa-plus"></i> New
                    </a>
                </div>
                <div class="conv-list">
                    @forelse($conversations as $conv)
                        @php
                            $otherUser = $conv->sender_id === auth()->id() ? $conv->receiver : $conv->sender;
                            $lastMsg = $conv->messages->sortByDesc('created_at')->first();
                            $unread = $conv->messages->where('user_id', '!=', auth()->id())->where('is_read', false)->count();
                        @endphp
                        <a href="{{ route('user.messages.chat', $conv->id) }}" class="conv-item {{ isset($conversation) && $conversation->id === $conv->id ? 'active' : '' }}">
                            @if($otherUser->profile_image)
                                <img src="{{ display_image($otherUser->profile_image) }}" class="conv-avatar">
                            @else
                                <div class="conv-avatar-placeholder">{{ strtoupper(substr($otherUser->name, 0, 1)) }}</div>
                            @endif
                            <div class="conv-info">
                                <div class="conv-name">{{ $otherUser->name }} @if($otherUser->role === 'admin')<i class="fa-solid fa-circle-check" style="color: #3b82f6; font-size: 12px;"></i>@endif</div>
                                <div class="conv-last-msg">
                                    @if($lastMsg)
                                        {{ $lastMsg->user_id === auth()->id() ? 'You: ' : '' }}{{ $lastMsg->type === 'image' ? '📷 Image' : Str::limit($lastMsg->message, 35) }}
                                    @else
                                        Start a conversation
                                    @endif
                                </div>
                            </div>
                            <div class="conv-meta">
                                @if($lastMsg)
                                    <span class="conv-time">{{ $lastMsg->created_at->diffForHumans(null, true, true) }}</span>
                                @endif
                                @if($unread > 0)
                                    <span class="conv-unread-badge">{{ $unread }}</span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div style="padding: 40px; text-align: center; color: #94a3b8;">
                            <i class="fa-solid fa-comment-dots" style="font-size: 32px; opacity: 0.3; display: block; margin-bottom: 12px;"></i>
                            <p style="font-size: 13px;">No conversations yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Chat Window -->
            @if(isset($conversation))
            @php
                $chatUser = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
            @endphp
            <div class="chat-window">
                <div class="chat-header">
                    <a href="{{ route('user.messages') }}" class="mobile-back-btn">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    @if($chatUser->profile_image)
                        <img src="{{ display_image($chatUser->profile_image) }}" class="conv-avatar" style="width: 40px; height: 40px;">
                    @else
                        <div class="conv-avatar-placeholder" style="width: 40px; height: 40px; font-size: 14px;">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</div>
                    @endif
                    <div class="chat-header-info">
                        <h4>{{ $chatUser->name }} @if($chatUser->role === 'admin')<i class="fa-solid fa-circle-check" style="color: #3b82f6; font-size: 12px;"></i>@endif</h4>
                        <div class="online-status" id="onlineStatus">
                            <span class="online-dot offline" id="onlineDot"></span>
                            <span id="onlineText" style="color: #94a3b8;">Checking...</span>
                        </div>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessages">
                    @foreach($messages as $msg)
                        <div class="msg-avatar-group {{ $msg->user_id === auth()->id() ? 'sent' : '' }}">
                            @if($msg->user_id !== auth()->id())
                                @if($msg->user->profile_image)
                                    <img src="{{ display_image($msg->user->profile_image) }}" class="msg-mini-avatar">
                                @else
                                    <div class="msg-mini-avatar-placeholder">{{ strtoupper(substr($msg->user->name, 0, 1)) }}</div>
                                @endif
                            @endif
                            <div id="msg-{{ $msg->id }}" class="msg-bubble {{ $msg->user_id === auth()->id() ? 'sent' : 'received' }}">
                                @if($msg->type === 'image' && $msg->file_path)
                                    <img src="{{ display_image($msg->file_path) }}" class="msg-image" onclick="window.open(this.src)">
                                @endif
                                @if($msg->message)
                                    <div>{{ $msg->message }}</div>
                                @endif
                                <div class="msg-time">
                                    {{ $msg->created_at->format('h:i A') }}
                                    @if($msg->user_id === auth()->id())
                                        <i class="fa-solid {{ $msg->is_read ? 'fa-check-double' : 'fa-check' }}" style="font-size: 10px;"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="image-preview-container" id="imagePreview">
                    <img id="previewImg" src="#" alt="Preview">
                    <button onclick="clearImagePreview()" style="border: none; background: #fee2e2; color: #dc2626; padding: 4px 8px; border-radius: 6px; cursor: pointer; margin-left: 8px; font-size: 12px;">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>

                <div class="chat-input-area">
                    <form class="chat-input-form" id="messageForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                        <input type="hidden" name="receiver_id" value="{{ $chatUser->id }}">
                        <input type="text" name="message" id="messageInput" placeholder="Type your message..." autocomplete="off">
                        <input type="file" name="image" id="imageInput" accept="image/*" style="display: none;" onchange="previewImage(this)">
                        <label for="imageInput"><i class="fa-solid fa-image" style="font-size: 18px;"></i></label>
                        <button type="submit" class="chat-send-btn">
                            <i class="fa-solid fa-paper-plane"></i> <span>Send</span>
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="chat-window">
                <div class="empty-chat">
                    <i class="fa-solid fa-comments"></i>
                    <h3>Welcome to Messages</h3>
                    <p>Select a conversation or start a new one with our support team</p>
                    <button class="new-chat-btn" onclick="startNewAdminChat()" style="margin-top: 12px;">
                        <i class="fa-solid fa-plus"></i> Start New Chat
                    </button>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>

<script>
    // Start new admin chat
    function startNewAdminChat() {
        fetch('{{ route("user.messages.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                receiver_id: {{ $admin ? $admin->id : 1 }},
                message: 'Hello! I need help.'
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = "{{ route('user.messages.chat', ':id') }}".replace(':id', data.message.conversation_id);
            }
        });
    }

    // Image preview
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'flex';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImagePreview() {
        document.getElementById('imageInput').value = '';
        document.getElementById('imagePreview').style.display = 'none';
    }

    @if(isset($conversation))
    // Scroll to bottom
    const chatBox = document.getElementById('chatMessages');
    chatBox.scrollTop = chatBox.scrollHeight;

    let lastMsgId = {{ $messages->last() ? $messages->last()->id : 0 }};

    // Poll for new messages
    setInterval(() => {
        fetch(`{{ route('user.messages.poll', $conversation->id) }}?last_id=${lastMsgId}`)
        .then(r => r.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    // Prevent duplicates
                    if (document.getElementById(`msg-${msg.id}`)) {
                        return;
                    }

                    const isSent = msg.user_id === {{ auth()->id() }};
                    const initial = msg.user.name.charAt(0).toUpperCase();
                    const avatar = msg.user.profile_image
                        ? `<img src="${msg.user.profile_image.startsWith('http') ? msg.user.profile_image : '/storage/' + msg.user.profile_image}" class="msg-mini-avatar">`
                        : `<div class="msg-mini-avatar-placeholder">${initial}</div>`;

                    let content = '';
                    if (msg.type === 'image' && msg.file_path) {
                        content += `<img src="${msg.file_path.startsWith('http') ? msg.file_path : '/storage/' + msg.file_path}" class="msg-image" onclick="window.open(this.src)">`;
                    }
                    if (msg.message) {
                        content += `<div>${msg.message}</div>`;
                    }

                    const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    const readIcon = isSent ? `<i class="fa-solid ${msg.is_read ? 'fa-check-double' : 'fa-check'}" style="font-size: 10px;"></i>` : '';

                    const html = `
                        <div class="msg-avatar-group ${isSent ? 'sent' : ''}">
                            ${!isSent ? avatar : ''}
                            <div id="msg-${msg.id}" class="msg-bubble ${isSent ? 'sent' : 'received'}">
                                ${content}
                                <div class="msg-time">${time} ${readIcon}</div>
                            </div>
                        </div>
                    `;

                    chatBox.insertAdjacentHTML('beforeend', html);
                    if (msg.id > lastMsgId) lastMsgId = msg.id;
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            // Update online status
            const dot = document.getElementById('onlineDot');
            const text = document.getElementById('onlineText');
            if (data.isOnline) {
                dot.className = 'online-dot online';
                text.textContent = 'Online';
                text.style.color = '#22c55e';
            } else {
                dot.className = 'online-dot offline';
                text.textContent = 'Offline';
                text.style.color = '#94a3b8';
            }
        });
    }, 3000);

    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const msgInput = document.getElementById('messageInput');
        const sendBtn = this.querySelector('.chat-send-btn');

        if (!msgInput.value.trim() && !document.getElementById('imageInput').files.length) return;

        // Optimized instant clearing
        const originalMsg = msgInput.value;
        msgInput.value = '';
        document.getElementById('imageInput').value = '';
        clearImagePreview();

        sendBtn.disabled = true;
        sendBtn.style.opacity = '0.6';

        fetch('{{ route("user.messages.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.status === 'success') {
                msgInput.focus();
            } else {
                msgInput.value = originalMsg;
                alert('Error: ' + (data.message || 'Could not send message'));
            }
        })
        .catch(err => {
            console.error(err);
            msgInput.value = originalMsg;
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.style.opacity = '1';
        });
    });
    @endif
</script>
@endsection
