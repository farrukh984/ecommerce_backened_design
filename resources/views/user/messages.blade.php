@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<style>
    .chat-container {
        display: flex;
        height: calc(100vh - 40px);
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        margin: 20px;
    }

    .chat-sidebar {
        width: 350px;
        border-right: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        background: #f8fafc;
    }

    .chat-sidebar-header {
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
    }

    .chat-sidebar-header h2 {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .conversation-list {
        flex: 1;
        overflow-y: auto;
    }

    .conversation-item {
        padding: 16px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none;
        color: inherit;
    }

    .conversation-item:hover {
        background: #f1f5f9;
    }

    .conversation-item.active {
        background: #eff6ff;
        border-left: 4px solid var(--primary-color);
    }

    .user-avatar-small {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
    }

    .conv-info {
        flex: 1;
        min-width: 0;
    }

    .conv-info h4 {
        margin: 0;
        font-size: 15px;
        color: #1e293b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-info p {
        margin: 4px 0 0;
        font-size: 13px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .conv-meta {
        text-align: right;
    }

    .conv-time {
        font-size: 11px;
        color: #94a3b8;
    }

    .unread-dot {
        width: 8px;
        height: 8px;
        background: var(--primary-color);
        border-radius: 50%;
        margin-top: 4px;
        display: inline-block;
    }

    .chat-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .chat-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-user-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .messages-window {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: #fdfdfd;
    }

    .message-bubble {
        max-width: 70%;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
    }

    .message-received {
        align-self: flex-start;
        background: #f1f5f9;
        color: #1e293b;
        border-bottom-left-radius: 2px;
    }

    .message-sent {
        align-self: flex-end;
        background: var(--primary-color);
    
        border-bottom-right-radius: 2px;
    }

    .message-wrapper {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
        max-width: 85%;
    }

    .message-wrapper.sent {
        flex-direction: row-reverse;
        align-self: flex-end;
    }

    .message-wrapper.received {
        align-self: flex-start;
    }

    .bubble-container {
        display: flex;
        flex-direction: column;
    }

    .message-sender-name {
        font-size: 11px;
        font-weight: 600;
        margin-bottom: 2px;
        color: #64748b;
    }

    .sent .message-sender-name {
        text-align: right;
    }

    .avatar-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
        overflow: hidden;
    }

    .message-time {
        font-size: 10px;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
        opacity: 0.8;
    }

    .chat-footer {
        padding: 20px 24px;
        border-top: 1px solid #f1f5f9;
    }

    .message-form {
        display: flex;
        gap: 12px;
    }

    .message-input {
        flex: 1;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        outline: none;
        transition: border-color 0.2s;
    }

    .message-input:focus {
        border-color: var(--primary-color);
    }

    .send-btn {
        background: var(--primary-color);
        /* color: white; */
        border: none;
        padding: 0 20px;
        border-radius: 10px;
        cursor: pointer;
        transition: opacity 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
        min-height: 44px;
    }

    .send-btn:hover {
        opacity: 0.9;
    }

    .empty-chat {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        gap: 16px;
    }

    .empty-chat i {
        font-size: 64px;
    }
</style>

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'messages'])

    <main class="dashboard-main" style="padding: 0;">
        <div class="chat-container">
            <!-- Sidebar -->
            <div class="chat-sidebar">
                <div class="chat-sidebar-header">
                    <h2>Messages</h2>
                </div>
                <div class="conversation-list">
                    @if(!$hasAdminChat && isset($admin) && auth()->user()->role !== 'admin')
                        <a href="javascript:void(0)" onclick="startNewChat()" class="conversation-item" style="border-left: 4px solid #34b7f1; background: #f0f9ff;">
                            <div class="user-avatar-small" style="background: #34b7f1; color: white;">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div class="conv-info">
                                <h4>Admin Support</h4>
                                <p style="color: #34b7f1; font-weight: 600;">Start a conversation</p>
                            </div>
                        </a>
                    @endif
                    @forelse($conversations as $conv)
                        @php
                            $otherUser = $conv->sender_id === auth()->id() ? $conv->receiver : $conv->sender;
                            $lastMsg = $conv->messages->last();
                            $hasUnread = $conv->messages->where('is_read', false)->where('user_id', '!=', auth()->id())->count() > 0;
                        @endphp
                        <a href="{{ route('user.messages.chat', $conv->id) }}" class="conversation-item {{ isset($currentChat) && $currentChat->id === $conv->id ? 'active' : '' }}">
                            <div class="user-avatar-small">
                                @if($otherUser->profile_image)
                                    <img src="{{ asset('storage/' . $otherUser->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                                @else
                                    {{ substr($otherUser->name, 0, 1) }}
                                @endif
                            </div>
                            <div class="conv-info">
                                <h4>{{ $otherUser->name }}</h4>
                                <p>{{ $lastMsg ? $lastMsg->message : 'No messages yet' }}</p>
                            </div>
                            <div class="conv-meta">
                                <span class="conv-time">{{ $conv->last_message_at ? $conv->last_message_at->diffForHumans(null, true) : '' }}</span>
                                @if($hasUnread)
                                    <span class="unread-dot"></span>
                                @endif
                            </div>
                        </a>
                    @empty
                        <div style="padding: 40px 24px; text-align: center; color: #94a3b8;">
                            <p>No conversations found.</p>
                            <button class="action-btn" onclick="startNewChat()">Contact Support</button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Main Chat Area -->
            <div class="chat-main">
                @if(isset($currentChat))
                    @php
                        $otherUser = $currentChat->sender_id === auth()->id() ? $currentChat->receiver : $currentChat->sender;
                        $isOnline = $otherUser->last_seen_at && $otherUser->last_seen_at->diffInMinutes(now()) < 5;
                    @endphp
                    <div class="chat-header">
                        <div class="chat-user-info">
                            <div class="user-avatar-small" style="width: 40px; height: 40px; font-size: 14px;">
                                @if($otherUser->profile_image)
                                    <img src="{{ asset('storage/' . $otherUser->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                                @else
                                    {{ substr($otherUser->name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">{{ $otherUser->name }}</h4>
                                <span id="statusIndicator" style="font-size: 12px; color: {{ $isOnline ? '#10b981' : '#94a3b8' }};">
                                    {{ $isOnline ? 'Online' : 'Last seen ' . ($otherUser->last_seen_at ? $otherUser->last_seen_at->diffForHumans() : 'Never') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="messages-window" id="messagesWindow">
                        @foreach($currentChat->messages as $msg)
                            @php $isMe = $msg->user_id == auth()->id(); @endphp
                            <div class="message-wrapper {{ $isMe ? 'sent' : 'received' }}" data-id="{{ $msg->id }}">
                                <div class="avatar-circle">
                                    @if($msg->user->profile_image)
                                        <img src="{{ asset('storage/' . $msg->user->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        {{ substr($msg->user->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="bubble-container">
                                    <span class="message-sender-name">{{ $msg->user->name }}</span>
                                    <div class="message-bubble {{ $isMe ? 'message-sent' : 'message-received' }}">
                                        @if($msg->type === 'image')
                                            <img src="{{ asset('storage/' . $msg->file_path) }}" style="max-width: 100%; border-radius: 8px; margin-bottom: 5px;">
                                        @endif
                                        @if($msg->message)
                                            <div>{{ $msg->message }}</div>
                                        @endif
                                        <span class="message-time" style="justify-content: flex-end;">
                                            {{ $msg->created_at->format('H:i') }}
                                            @if($isMe)
                                                <i class="fa-solid fa-check-double status-tick" style="color: {{ $msg->is_read ? '#34b7f1' : 'rgba(255,255,255,0.7)' }}; transition: color 0.3s; margin-left: 4px;"></i>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="chat-footer">
                        <form id="chatForm" class="message-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="conversation_id" value="{{ $currentChat->id }}">
                            
                            <label for="imageUpload" style="cursor: pointer; padding: 10px; color: #64748b; display: flex; align-items: center;">
                                <i class="fa-solid fa-paperclip" style="font-size: 20px;"></i>
                                <input type="file" id="imageUpload" name="image" style="display: none;" accept="image/*">
                            </label>

                            <input type="text" name="message" class="message-input" placeholder="Type your message..." autocomplete="off">
                            <button type="submit" class="send-btn">
                                <i class="fa-solid fa-paper-plane"></i> Send
                            </button>
                        </form>
                        <div id="imagePreviewContainer" style="display: none; padding: 10px; margin-top: 10px; border-top: 1px solid #f1f5f9;">
                            <img id="imagePreview" src="" style="height: 60px; border-radius: 8px;">
                            <button type="button" onclick="clearImage()" style="background: #ef4444; color: white; border: none; border-radius: 50%; width: 20px; height: 20px; font-size: 10px; cursor: pointer;">X</button>
                        </div>
                    </div>
                @else
                    <div class="empty-chat">
                        <i class="fa-solid fa-comments"></i>
                        <h3>Select a conversation to start chatting</h3>
                        <p>Your messages with our support team will appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messagesWindow = document.getElementById('messagesWindow');
        const imageUpload = document.getElementById('imageUpload');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        
        if(messagesWindow) {
            messagesWindow.scrollTop = messagesWindow.scrollHeight;
            startPolling();
        }

        if(imageUpload) {
            imageUpload.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreviewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        const chatForm = document.getElementById('chatForm');
        if(chatForm) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const input = this.querySelector('input[name="message"]');
                const messageText = input.value;
                
                if(!messageText.trim() && !imageUpload.files[0]) return;

                // Optimistic UI for text
                if (messageText.trim() && !imageUpload.files[0]) {
                    const tempId = 'temp-' + Date.now();
                    appendMessage({ 
                        id: tempId,
                        message: messageText, 
                        type: 'text', 
                        user_id: {{ auth()->id() }}, 
                        created_at: new Date().toISOString(),
                        user: {
                            name: "{{ auth()->user()->name }}",
                            profile_image: "{{ auth()->user()->profile_image }}"
                        }
                    });
                    input.value = '';
                }

                fetch("{{ route('user.messages.send') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        if (imageUpload.files[0]) {
                            appendMessage(data.message);
                            clearImage();
                        }
                        input.value = '';
                    }
                });
            });
        }

        function clearImage() {
            imageUpload.value = '';
            imagePreview.src = '';
            imagePreviewContainer.style.display = 'none';
        }

        function appendMessage(data) {
            if (document.querySelector(`[data-id="${data.id}"]`)) return;

            const isMe = data.user_id == {{ auth()->id() }};
            const wrapper = document.createElement('div');
            wrapper.className = `message-wrapper ${isMe ? 'sent' : 'received'}`;
            wrapper.setAttribute('data-id', data.id);
            
            const avatarHtml = data.user.profile_image 
                ? `<img src="/storage/${data.user.profile_image}" style="width: 100%; height: 100%; object-fit: cover;">`
                : data.user.name.charAt(0);

            let messageContent = '';
            if (data.type === 'image') {
                messageContent += `<img src="/storage/${data.file_path}" style="max-width: 100%; border-radius: 8px; margin-bottom: 5px;">`;
            }
            if (data.message) {
                messageContent += `<div>${data.message}</div>`;
            }
            
            // Local time conversion
            const date = new Date(data.created_at);
            const timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
            
            const tickHtml = isMe ? `<i class="fa-solid fa-check-double status-tick" style="color: ${data.is_read ? '#34b7f1' : 'rgba(255,255,255,0.6)'}; margin-left: 4px;"></i>` : '';

            wrapper.innerHTML = `
                <div class="avatar-circle">${avatarHtml}</div>
                <div class="bubble-container">
                    <span class="message-sender-name">${data.user.name}</span>
                    <div class="message-bubble ${isMe ? 'message-sent' : 'message-received'}">
                        ${messageContent}
                        <span class="message-time" style="justify-content: flex-end;">
                            ${timeStr}
                            ${tickHtml}
                        </span>
                    </div>
                </div>
            `;
            
            messagesWindow.appendChild(wrapper);
            messagesWindow.scrollTop = messagesWindow.scrollHeight;
        }

        function startPolling() {
            setInterval(() => {
                const lastMsg = document.querySelector('.message-wrapper:last-child');
                const lastId = lastMsg ? lastMsg.getAttribute('data-id') : 0;
                
                fetch("{{ route('user.messages.poll', $currentChat->id ?? 0) }}?last_id=" + lastId)
                .then(r => r.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(msg => appendMessage(msg));
                    }
                    // Update status
                    const status = document.getElementById('statusIndicator');
                    if (status) {
                        status.style.color = data.isOnline ? '#10b981' : '#94a3b8';
                        status.innerText = data.isOnline ? 'Online' : 'Last seen ' + data.lastSeen;
                    }
                });
            }, 3000); // Poll every 3 seconds
        }

        window.clearImage = clearImage;
    });

    function startNewChat() {
        const adminId = {{ $admin->id ?? 1 }};
        const msg = prompt("Enter your message to Admin:", "Hello, I need some assistance.");
        if (msg === null || msg.trim() === "") return;

        fetch("{{ route('user.messages.send') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ message: msg, receiver_id: adminId })
        })
        .then(response => response.json())
        .then(data => { window.location.reload(); });
    }
</script>
@endsection
