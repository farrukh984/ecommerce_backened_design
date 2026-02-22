@extends('layouts.admin')

@section('title', 'Messages')
@section('header_title', 'Customer Support Messages')

@section('admin_content')
<style>
    .chat-container {
        display: flex;
        height: calc(100vh - 160px);
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border: 1px solid var(--admin-border);
    }

    .chat-sidebar {
        width: 350px;
        border-right: 1px solid #f1f5f9;
        display: flex; flex-direction: column;
        background: #fcfcfc;
    }

    .conversation-list { flex: 1; overflow-y: auto; }

    .conversation-item {
        padding: 16px 20px;
        display: flex; align-items: center; gap: 12px;
        cursor: pointer; transition: all 0.2s;
        border-bottom: 1px solid #f1f5f9;
        text-decoration: none; color: inherit;
    }

    .conversation-item:hover { background: #f8fafc; }
    .conversation-item.active {
        background: #eff6ff;
        border-left: 4px solid var(--admin-primary);
    }

    .user-avatar-small {
        width: 44px; height: 44px; border-radius: 10px;
        background: var(--admin-primary); color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; flex-shrink: 0;
    }

    .conv-info { flex: 1; min-width: 0; }
    .conv-info h4 { margin: 0; font-size: 14px; color: #1e293b; }
    .conv-info p { margin: 4px 0 0; font-size: 12px; color: #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    .conv-meta { text-align: right; }
    .conv-time { font-size: 10px; color: #94a3b8; }
    .unread-dot { width: 8px; height: 8px; background: var(--admin-primary); border-radius: 50%; margin-top: 4px; display: inline-block; }

    .chat-main { flex: 1; display: flex; flex-direction: column; background: #fff; }

    .messages-window {
        flex: 1; padding: 24px; overflow-y: auto;
        display: flex; flex-direction: column; gap: 16px;
    }

    .message-bubble {
        padding: 10px 14px; border-radius: 12px;
        font-size: 14px; line-height: 1.5; position: relative;
    }

    .message-wrapper {
        display: flex; gap: 10px; margin-bottom: 12px; max-width: 85%;
    }
    .message-wrapper.sent { flex-direction: row-reverse; align-self: flex-end; }
    .message-wrapper.received { align-self: flex-start; }
    
    .bubble-container { display: flex; flex-direction: column; }
    .message-sender-name { font-size: 11px; font-weight: 600; margin-bottom: 2px; color: #64748b; }
    .sent .message-sender-name { text-align: right; }

    .avatar-circle {
        width: 32px; height: 32px; border-radius: 50%;
        background: var(--admin-primary); color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 700; flex-shrink: 0; overflow: hidden;
    }

    .message-received { background: #f1f5f9; color: #1e293b; border-bottom-left-radius: 2px; }
    .message-sent { background: var(--admin-primary); color: white; border-bottom-right-radius: 2px; }
    .message-time { font-size: 10px; margin-top: 4px; display: flex; align-items: center; gap: 4px; opacity: 0.8; }

    .chat-footer { padding: 20px; border-top: 1px solid #f1f5f9; background: #fff; }
    .message-form { display: flex; gap: 12px; }
    .message-input {
        flex: 1; padding: 10px 16px; border: 1px solid #e2e8f0;
        border-radius: 8px; outline: none;
    }
    .send-btn {
        background: var(--admin-primary); color: white; border: none;
        padding: 0 20px; border-radius: 8px; cursor: pointer;
        flex-shrink: 0; min-height: 40px; display: flex; align-items: center; justify-content: center;
    }
</style>

<div class="chat-container">
    <div class="chat-sidebar">
        <div class="conversation-list">
            @forelse($conversations as $conv)
                @php
                    $otherUser = $conv->sender_id === auth()->id() ? $conv->receiver : $conv->sender;
                    $lastMsg = $conv->messages->last();
                    $hasUnread = $conv->messages->where('is_read', false)->where('user_id', '!=', auth()->id())->count() > 0;
                @endphp
                <a href="{{ route('admin.messages.chat', $conv->id) }}" class="conversation-item {{ isset($currentChat) && $currentChat->id === $conv->id ? 'active' : '' }}">
                    <div class="user-avatar-small">
                        @if($otherUser->profile_image)
                            <img src="{{ asset('storage/' . $otherUser->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
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
                <div style="padding: 40px; text-align: center; color: #94a3b8;">No messages yet.</div>
            @endforelse
        </div>
    </div>

    <div class="chat-main">
        @if(isset($currentChat))
            @php 
                $otherUser = $currentChat->sender_id === auth()->id() ? $currentChat->receiver : $currentChat->sender; 
                $isOnline = $otherUser->last_seen_at && $otherUser->last_seen_at->diffInMinutes(now()) < 5;
            @endphp
            <div style="padding: 15px 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 12px;">
                <div class="user-avatar-small" style="width: 40px; height: 40px;">
                    @if($otherUser->profile_image)
                        <img src="{{ asset('storage/' . $otherUser->profile_image) }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                    @else
                        {{ substr($otherUser->name, 0, 1) }}
                    @endif
                </div>
                <div>
                    <span style="font-weight: 600; display: block;">{{ $otherUser->name }}</span>
                    <div id="statusIndicator" style="font-size: 11px; color: {{ $isOnline ? '#10b981' : '#94a3b8' }};">
                        {{ $isOnline ? 'Online' : 'Last seen ' . ($otherUser->last_seen_at ? $otherUser->last_seen_at->diffForHumans() : 'Never') }}
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
                    
                    <label for="imageUpload" style="cursor: pointer; padding: 10px; color: #64748b;">
                        <i class="fa-solid fa-paperclip"></i>
                        <input type="file" id="imageUpload" name="image" style="display: none;" accept="image/*">
                    </label>

                    <input type="text" name="message" class="message-input" placeholder="Reply to customer..." autocomplete="off">
                    <button type="submit" class="send-btn">Send</button>
                </form>
                <div id="imagePreviewContainer" style="display: none; padding: 10px; margin-top: 10px; border-top: 1px solid #f1f5f9;">
                    <img id="imagePreview" src="" style="height: 50px; border-radius: 5px;">
                    <button type="button" onclick="clearImage()" style="background: #ef4444; color: white; border: none; border-radius: 50%; cursor: pointer;">X</button>
                </div>
            </div>
        @else
            <div style="flex: 1; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                Select a customer conversation to respond.
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const win = document.getElementById('messagesWindow');
        const imgInput = document.getElementById('imageUpload');
        const imgPrev = document.getElementById('imagePreview');
        const imgCont = document.getElementById('imagePreviewContainer');

        if(win) {
            win.scrollTop = win.scrollHeight;
            startPolling();
        }

        if(imgInput) {
            imgInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => { imgPrev.src = e.target.result; imgCont.style.display = 'block'; }
                    reader.readAsDataURL(file);
                }
            });
        }

        const form = document.getElementById('chatForm');
        if(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(this);
                const input = this.querySelector('input[name="message"]');
                const msg = input.value;
                
                if(!msg.trim() && !imgInput.files[0]) return;

                if (msg.trim() && !imgInput.files[0]) {
                    const tempId = 'temp-' + Date.now();
                    appendMsg({ 
                        id: tempId,
                        message: msg, 
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

                fetch("{{ route('admin.messages.send') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: fd
                })
                .then(r => r.json())
                .then(data => {
                    if(data.status === 'success') {
                        if (imgInput.files[0]) {
                            appendMsg(data.message);
                            clearImage();
                        }
                        input.value = '';
                    }
                });
            });
        }

        function clearImage() {
            imgInput.value = '';
            imgPrev.src = '';
            imgCont.style.display = 'none';
        }

        function appendMsg(data) {
            if (document.querySelector(`[data-id="${data.id}"]`)) return;

            const isMe = data.user_id == {{ auth()->id() }};
            const wrapper = document.createElement('div');
            wrapper.className = `message-wrapper ${isMe ? 'sent' : 'received'}`;
            wrapper.setAttribute('data-id', data.id);
            
            const avatarHtml = data.user.profile_image 
                ? `<img src="/storage/${data.user.profile_image}" style="width: 100%; height: 100%; object-fit: cover;">`
                : data.user.name.charAt(0);

            let content = '';
            if(data.type === 'image') content += `<img src="/storage/${data.file_path}" style="max-width: 100%; border-radius: 8px; margin-bottom: 5px;">`;
            if(data.message) content += `<div>${data.message}</div>`;
            
            const date = new Date(data.created_at);
            const timeStr = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
            const tickHtml = isMe ? `<i class="fa-solid fa-check-double status-tick" style="color: ${data.is_read ? '#34b7f1' : 'rgba(255,255,255,0.6)'}; margin-left: 4px;"></i>` : '';

            wrapper.innerHTML = `
                <div class="avatar-circle">${avatarHtml}</div>
                <div class="bubble-container">
                    <span class="message-sender-name">${data.user.name}</span>
                    <div class="message-bubble ${isMe ? 'message-sent' : 'message-received'}">
                        ${content}
                        <span class="message-time" style="justify-content: flex-end;">
                            ${timeStr}
                            ${tickHtml}
                        </span>
                    </div>
                </div>
            `;
            win.appendChild(wrapper);
            win.scrollTop = win.scrollHeight;
        }

        function startPolling() {
            setInterval(() => {
                const last = document.querySelector('.message-wrapper:last-child');
                const lastId = last ? last.getAttribute('data-id') : 0;
                fetch("{{ route('admin.messages.poll', $currentChat->id ?? 0) }}?last_id=" + lastId)
                .then(r => r.json())
                .then(data => {
                    if (data.messages) data.messages.forEach(m => appendMsg(m));
                    const status = document.getElementById('statusIndicator');
                    if (status) {
                        status.style.color = data.isOnline ? '#10b981' : '#94a3b8';
                        status.innerText = data.isOnline ? 'Online' : 'Last seen ' + data.lastSeen;
                    }
                });
            }, 3000);
        }
        window.clearImage = clearImage;
    });
</script>
@endsection
