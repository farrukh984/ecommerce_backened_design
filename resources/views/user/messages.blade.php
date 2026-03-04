@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<style>
    /* ═══════════════════════════════════════════════════════════
       WHATSAPP DESKTOP DARK THEME — USER PANEL
       ═══════════════════════════════════════════════════════════ */

    :root {
        --wa-bg-deep: #0b141a;
        --wa-bg-panel: #111b21;
        --wa-bg-header: #202c33;
        --wa-bg-input: #2a3942;
        --wa-bg-hover: #202c33;
        --wa-bg-active: #2a3942;
        --wa-bg-sent: #005c4b;
        --wa-bg-received: #202c33;
        --wa-green: #00a884;
        --wa-green-light: #25d366;
        --wa-text-primary: #e9edef;
        --wa-text-secondary: #8696a0;
        --wa-text-msg: #e9edef;
        --wa-border: #222d34;
        --wa-unread: #00a884;
        --wa-chat-bg: #0b141a;
    }

    .dashboard-main { padding: 0 !important; }

    .chat-container {
        display: grid;
        grid-template-columns: 320px 1fr;
        height: calc(100vh - 60px);
        background: var(--wa-bg-deep);
        overflow: hidden;
    }

    /* ─── Mobile ─────────────────────────────────────────────── */
    @media (max-width: 991px) {
        .chat-container {
            grid-template-columns: 1fr;
            height: calc(100vh - 56px);
        }
        .conv-sidebar {
            position: fixed;
            left: 0; top: 0;
            width: 300px; height: 100vh;
            z-index: 1060;
            transform: translateX(-100%);
            transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
            box-shadow: 5px 0 30px rgba(0,0,0,0.5);
        }
        .conv-sidebar.mobile-open { transform: translateX(0); }
        .chat-window { display: flex !important; }
        .mobile-back-btn { display: flex !important; }
        .chat-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1055;
            display: none;
        }
        .chat-overlay.active { display: block; }

        @if(isset($conversation))
        @else
            .conv-sidebar {
                position: relative;
                transform: translateX(0);
                width: 100% !important;
                box-shadow: none;
                height: auto;
            }
            .chat-window { display: none !important; }
            .chat-container { grid-template-columns: 1fr; }
        @endif

        .desktop-only { display: none !important; }
    }
    @media (min-width: 992px) {
        .mobile-only { display: none !important; }
    }
    .mobile-back-btn { display: none; }

    /* ═══ CONVERSATION SIDEBAR (LEFT) ═══════════════════════════ */
    .conv-sidebar {
        background: var(--wa-bg-panel);
        display: flex;
        flex-direction: column;
        height: 100%;
        border-right: 1px solid var(--wa-border);
    }
    .conv-header {
        padding: 14px 16px;
        display: flex; align-items: center; justify-content: space-between;
        background: var(--wa-bg-header);
        min-height: 56px;
    }
    .conv-header h3 {
        font-size: 18px; font-weight: 700;
        color: var(--wa-text-primary); margin: 0;
    }
    .conv-header-actions { display: flex; gap: 6px; }
    .conv-header-actions button, .conv-header-actions a {
        width: 34px; height: 34px;
        border: none; background: transparent;
        color: var(--wa-text-secondary);
        border-radius: 50%;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 15px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .conv-header-actions button:hover, .conv-header-actions a:hover {
        background: rgba(255,255,255,0.06);
        color: var(--wa-text-primary);
    }
    .new-chat-btn-green {
        background: var(--wa-green) !important;
        color: var(--wa-bg-panel) !important;
        width: auto !important;
        padding: 6px 14px !important;
        border-radius: 20px !important;
        font-size: 12px !important;
        font-weight: 700;
        gap: 5px;
    }
    .new-chat-btn-green:hover { background: var(--wa-green-light) !important; }

    .conv-search { padding: 8px 12px; background: var(--wa-bg-panel); }
    .conv-search-box {
        display: flex; align-items: center;
        background: var(--wa-bg-input);
        border-radius: 8px;
        padding: 0 12px; gap: 12px;
    }
    .conv-search-box i { color: var(--wa-text-secondary); font-size: 14px; }
    .conv-search-box input {
        flex: 1; border: none; background: transparent;
        padding: 9px 0;
        font-size: 13px; color: var(--wa-text-primary);
        outline: none; font-family: inherit;
    }
    .conv-search-box input::placeholder { color: var(--wa-text-secondary); }

    .conv-list { flex: 1; overflow-y: auto; min-height: 0; }
    .conv-list::-webkit-scrollbar { width: 6px; }
    .conv-list::-webkit-scrollbar-track { background: transparent; }
    .conv-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

    .conv-item {
        display: flex; align-items: center; gap: 13px;
        padding: 12px 16px;
        cursor: pointer; transition: background 0.15s;
        text-decoration: none; color: inherit;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        position: relative;
    }
    .conv-item:hover { background: var(--wa-bg-hover); }
    .conv-item.active { background: var(--wa-bg-active); }
    .conv-item.active::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; background: var(--wa-green);
    }
    .conv-avatar {
        width: 49px; height: 49px; border-radius: 50%;
        object-fit: cover; flex-shrink: 0;
    }
    .conv-avatar-placeholder {
        width: 49px; height: 49px; border-radius: 50%;
        background: var(--wa-bg-input);
        color: var(--wa-text-secondary);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 18px; flex-shrink: 0;
    }
    .conv-info { flex: 1; min-width: 0; }
    .conv-name-row {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 3px;
    }
    .conv-name {
        font-weight: 600; font-size: 15px; color: var(--wa-text-primary);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        display: flex; align-items: center; gap: 5px;
    }
    .conv-time { font-size: 11px; color: var(--wa-text-secondary); flex-shrink: 0; margin-left: 8px; }
    .conv-item.has-unread .conv-time { color: var(--wa-green); }
    .conv-last-row { display: flex; align-items: center; justify-content: space-between; }
    .conv-last-msg {
        font-size: 13px; color: var(--wa-text-secondary);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1;
    }
    .conv-unread {
        min-width: 20px; height: 20px; border-radius: 50%;
        background: var(--wa-unread); color: #111b21;
        font-size: 11px; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
        padding: 0 5px; margin-left: 8px; flex-shrink: 0;
    }

    /* ═══ CHAT WINDOW (RIGHT) ══════════════════════════════════ */
    .chat-window {
        display: flex; flex-direction: column;
        background: var(--wa-chat-bg);
        height: 100%; min-height: 0;
    }
    .chat-top {
        padding: 10px 16px;
        display: flex; align-items: center; gap: 12px;
        background: var(--wa-bg-header);
        min-height: 56px; z-index: 5;
    }
    .chat-top-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        object-fit: cover; flex-shrink: 0;
    }
    .chat-top-avatar-ph {
        width: 40px; height: 40px; border-radius: 50%;
        background: var(--wa-bg-input);
        color: var(--wa-text-secondary);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 15px; flex-shrink: 0;
    }
    .chat-top-info { flex: 1; min-width: 0; }
    .chat-top-info h4 {
        margin: 0; font-size: 15px; font-weight: 600;
        color: var(--wa-text-primary); line-height: 1.2;
        display: flex; align-items: center; gap: 6px;
    }
    .chat-top-info .status-text {
        font-size: 12px; color: var(--wa-text-secondary);
        display: flex; align-items: center; gap: 5px;
    }
    .verified-badge { color: #53bdeb; font-size: 13px; }
    .online-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
    .online-dot.online { background: var(--wa-green); box-shadow: 0 0 6px rgba(0,168,132,0.5); }
    .online-dot.offline { background: var(--wa-text-secondary); }

    .chat-top-actions { display: flex; gap: 4px; }
    .chat-top-actions button {
        width: 38px; height: 38px; border: none; background: transparent;
        color: var(--wa-text-secondary); border-radius: 50%; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; transition: all 0.2s;
    }
    .chat-top-actions button:hover { background: rgba(255,255,255,0.06); color: var(--wa-text-primary); }

    /* Messages */
    .chat-messages {
        flex: 1; overflow-y: auto;
        padding: 20px 50px;
        display: flex; flex-direction: column;
        gap: 2px; min-height: 0;
        scroll-behavior: smooth;
        background-color: var(--wa-chat-bg);
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Ccircle r='2' cx='10' cy='10'/%3E%3Ccircle r='1.5' cx='40' cy='30'/%3E%3Ccircle r='1' cx='20' cy='50'/%3E%3C/g%3E%3C/svg%3E");
    }
    @media (max-width: 767px) { .chat-messages { padding: 16px 12px; } }
    .chat-messages::-webkit-scrollbar { width: 6px; }
    .chat-messages::-webkit-scrollbar-track { background: transparent; }
    .chat-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 10px; }

    .msg-row { display: flex; margin-bottom: 1px; }
    .msg-row.sent { justify-content: flex-end; }
    .msg-row.received { justify-content: flex-start; }
    .msg-bubble {
        max-width: 65%; padding: 7px 10px 4px;
        border-radius: 8px; font-size: 13.5px;
        line-height: 1.5; word-wrap: break-word;
        position: relative; animation: msgIn 0.2s ease;
    }
    @keyframes msgIn { from { opacity:0;transform:scale(0.95); } to { opacity:1;transform:scale(1); } }
    .msg-bubble.sent { background: var(--wa-bg-sent); color: var(--wa-text-msg); border-top-right-radius: 0; }
    .msg-bubble.received { background: var(--wa-bg-received); color: var(--wa-text-msg); border-top-left-radius: 0; }
    .msg-bubble .msg-text { margin-right: 50px; }
    .msg-meta {
        float: right; margin-top: 4px; margin-left: 10px;
        display: flex; align-items: center; gap: 3px;
        font-size: 11px; color: rgba(255,255,255,0.55); white-space: nowrap;
    }
    .msg-meta i { font-size: 14px; }
    .msg-meta .fa-check-double.read { color: #53bdeb; }
    .msg-image { max-width: 300px; border-radius: 6px; margin-bottom: 4px; cursor: pointer; }
    @media (max-width: 600px) { .msg-bubble { max-width: 85%; } .msg-image { max-width: 220px; } }

    /* Typing */
    .typing-indicator { display: none; justify-content: flex-start; margin-bottom: 2px; animation: msgIn 0.2s ease; }
    .typing-indicator.active { display: flex; }
    .typing-bubble {
        background: var(--wa-bg-received); padding: 10px 16px;
        border-radius: 8px; border-top-left-radius: 0;
        display: flex; align-items: center; gap: 4px;
    }
    .typing-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--wa-text-secondary); animation: tBounce 1.4s infinite ease-in-out; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes tBounce { 0%,60%,100%{transform:translateY(0);opacity:0.4;} 30%{transform:translateY(-5px);opacity:1;} }

    /* Input Bar */
    .chat-input-area {
        padding: 8px 10px; background: var(--wa-bg-header);
        display: flex; align-items: center; gap: 8px;
    }
    .chat-input-area .input-icon-btn {
        width: 38px; height: 38px; border: none; background: transparent;
        color: var(--wa-text-secondary); border-radius: 50%; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; transition: all 0.15s; flex-shrink: 0;
    }
    .chat-input-area .input-icon-btn:hover { color: var(--wa-text-primary); }
    .chat-input-area label.input-icon-btn {
        width: 38px; height: 38px; border: none; background: transparent;
        color: var(--wa-text-secondary); border-radius: 50%; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 18px; transition: all 0.15s; flex-shrink: 0;
    }
    .chat-input-area label.input-icon-btn:hover { color: var(--wa-text-primary); }
    .chat-input-box {
        flex: 1; background: var(--wa-bg-input); border: none;
        border-radius: 8px; padding: 10px 14px; font-size: 14px;
        color: var(--wa-text-primary); outline: none; font-family: inherit;
        min-width: 0;
    }
    .chat-input-box::placeholder { color: var(--wa-text-secondary); }
    .send-btn {
        width: 42px; height: 42px; border: none; border-radius: 50%;
        background: var(--wa-green); color: var(--wa-bg-panel); cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; transition: all 0.2s; flex-shrink: 0;
    }
    .send-btn:hover { background: var(--wa-green-light); }
    .send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

    /* Image Preview */
    .img-preview-strip {
        display: none; padding: 6px 16px; background: var(--wa-bg-header);
        border-top: 1px solid var(--wa-border); align-items: center; gap: 10px;
    }
    .img-preview-strip.active { display: flex; }
    .img-preview-strip img { max-height: 55px; border-radius: 6px; border: 1px solid var(--wa-border); }
    .img-preview-strip .remove-btn {
        border: none; background: rgba(255,80,80,0.2);
        color: #ff5050; padding: 4px 10px; border-radius: 6px;
        cursor: pointer; font-size: 12px;
    }

    /* Emoji */
    .emoji-picker-wrap { position: relative; }
    .emoji-panel {
        display: none; position: absolute; bottom: 52px; left: -5px;
        width: 320px; max-height: 340px; background: var(--wa-bg-header);
        border-radius: 12px; box-shadow: 0 8px 30px rgba(0,0,0,0.4);
        border: 1px solid var(--wa-border); z-index: 100;
        flex-direction: column; overflow: hidden; animation: emojiUp 0.2s ease;
    }
    .emoji-panel.active { display: flex; }
    @keyframes emojiUp { from{opacity:0;transform:translateY(8px);} to{opacity:1;transform:translateY(0);} }
    .emoji-search { padding: 10px; border-bottom: 1px solid var(--wa-border); }
    .emoji-search input {
        width: 100%; padding: 8px 12px; background: var(--wa-bg-input);
        border: none; border-radius: 6px; font-size: 13px;
        color: var(--wa-text-primary); outline: none; font-family: inherit;
    }
    .emoji-search input::placeholder { color: var(--wa-text-secondary); }
    .emoji-cats { display: flex; gap: 2px; padding: 6px 10px; border-bottom: 1px solid rgba(255,255,255,0.04); }
    .emoji-cat-btn {
        padding: 4px 7px; border: none; background: transparent;
        border-radius: 6px; cursor: pointer; font-size: 15px;
        transition: all 0.15s; flex-shrink: 0;
    }
    .emoji-cat-btn:hover, .emoji-cat-btn.active { background: var(--wa-bg-input); }
    .emoji-grid {
        flex: 1; overflow-y: auto; padding: 8px;
        display: grid; grid-template-columns: repeat(8, 1fr); gap: 1px;
    }
    .emoji-grid::-webkit-scrollbar { width: 4px; }
    .emoji-grid::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .emoji-btn {
        width: 36px; height: 36px; border: none; background: transparent;
        border-radius: 6px; cursor: pointer; font-size: 20px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.1s;
    }
    .emoji-btn:hover { background: var(--wa-bg-input); transform: scale(1.15); }
    @media (max-width: 600px) {
        .emoji-panel {
            position: fixed; bottom: 0; left: 0; right: 0;
            width: 100%; max-height: 45vh; border-radius: 14px 14px 0 0;
        }
    }

    /* Empty */
    .empty-chat {
        flex: 1; display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        color: var(--wa-text-secondary); gap: 12px; padding: 40px; text-align: center;
    }
    .empty-chat h3 { color: var(--wa-text-primary); font-size: 28px; font-weight: 300; margin: 8px 0 4px; }
    .empty-chat p { color: var(--wa-text-secondary); font-size: 13px; max-width: 400px; line-height: 1.6; }
    .empty-chat .lock-text { font-size: 12px; color: var(--wa-text-secondary); display: flex; align-items: center; gap: 6px; margin-top: 20px; }
</style>

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'messages'])

    <main class="dashboard-main">
        <div class="chat-container">
            {{-- ═══ Conversations Sidebar (LEFT) ═══ --}}
            <div class="conv-sidebar">
                <div class="conv-header">
                    <h3>Chats</h3>
                    <div class="conv-header-actions">
                        <a href="#" class="new-chat-btn-green" onclick="event.preventDefault(); startNewAdminChat();">
                            <i class="fa-solid fa-plus"></i> New
                        </a>
                        <button class="mobile-only" onclick="toggleChatSidebar()" title="Close"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                </div>
                <div class="conv-search">
                    <div class="conv-search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="convSearchInput" placeholder="Search or start a new chat">
                    </div>
                </div>
                <div class="conv-list" id="convList">
                    @forelse($conversations as $conv)
                        @php
                            $otherUser = $conv->sender_id === auth()->id() ? $conv->receiver : $conv->sender;
                            $lastMsg = $conv->messages->sortByDesc('created_at')->first();
                            $unread = $conv->messages->where('user_id', '!=', auth()->id())->where('is_read', false)->count();
                        @endphp
                        <a href="{{ route('user.messages.chat', $conv->id) }}"
                           class="conv-item {{ isset($conversation) && $conversation->id === $conv->id ? 'active' : '' }} {{ $unread > 0 ? 'has-unread' : '' }}"
                           data-name="{{ strtolower($otherUser->name) }}">
                            @if($otherUser->profile_image)
                                <img src="{{ display_image($otherUser->profile_image) }}" class="conv-avatar">
                            @else
                                <div class="conv-avatar-placeholder">{{ strtoupper(substr($otherUser->name, 0, 1)) }}</div>
                            @endif
                            <div class="conv-info">
                                <div class="conv-name-row">
                                    <span class="conv-name">
                                        {{ $otherUser->name }}
                                        @if($otherUser->role === 'admin')
                                            <i class="fa-solid fa-circle-check verified-badge"></i>
                                        @endif
                                    </span>
                                    @if($lastMsg)
                                        <span class="conv-time">{{ $lastMsg->created_at->format('g:i a') }}</span>
                                    @endif
                                </div>
                                <div class="conv-last-row">
                                    <span class="conv-last-msg">
                                        @if($lastMsg)
                                            @if($lastMsg->user_id === auth()->id())<i class="fa-solid fa-check-double" style="font-size:13px;color:{{ $lastMsg->is_read ? '#53bdeb' : 'var(--wa-text-secondary)' }};margin-right:3px;"></i>@endif{{ $lastMsg->type === 'image' ? '📷 Photo' : Str::limit($lastMsg->message, 35) }}
                                        @else
                                            Start a conversation
                                        @endif
                                    </span>
                                    @if($unread > 0)
                                        <span class="conv-unread">{{ $unread }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div style="padding:40px 20px;text-align:center;color:var(--wa-text-secondary);">
                            <i class="fa-solid fa-comment-dots" style="font-size:30px;opacity:0.3;display:block;margin-bottom:12px;"></i>
                            <p style="font-size:13px;">No conversations yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Mobile Overlay --}}
            <div class="chat-overlay" id="chatOverlay" onclick="toggleChatSidebar()"></div>

            {{-- ═══ Chat Window (RIGHT) ═══ --}}
            @if(isset($conversation))
            @php
                $chatUser = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
            @endphp
            <div class="chat-window">
                <div class="chat-top">
                    <button class="mobile-back-btn" onclick="toggleChatSidebar()" style="width:34px;height:34px;border:none;background:transparent;color:var(--wa-text-secondary);border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;">
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                    @if($chatUser->profile_image)
                        <img src="{{ display_image($chatUser->profile_image) }}" class="chat-top-avatar">
                    @else
                        <div class="chat-top-avatar-ph">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</div>
                    @endif
                    <div class="chat-top-info">
                        <h4>
                            {{ $chatUser->name }}
                            @if($chatUser->role === 'admin')
                                <i class="fa-solid fa-circle-check verified-badge"></i>
                            @endif
                        </h4>
                        <div class="status-text" id="onlineStatus">
                            <span class="online-dot offline" id="onlineDot"></span>
                            <span id="onlineText">checking...</span>
                        </div>
                    </div>
                    <div class="chat-top-actions desktop-only">
                        <button title="Search"><i class="fa-solid fa-magnifying-glass"></i></button>
                        <button title="More"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    </div>
                </div>

                <div class="chat-messages" id="chatMessages">
                    @foreach($messages as $msg)
                        <div class="msg-row {{ $msg->user_id === auth()->id() ? 'sent' : 'received' }}">
                            <div id="msg-{{ $msg->id }}" class="msg-bubble {{ $msg->user_id === auth()->id() ? 'sent' : 'received' }}">
                                @if($msg->type === 'image' && $msg->file_path)
                                    <img src="{{ display_image($msg->file_path) }}" class="msg-image" onclick="window.open(this.src)">
                                @endif
                                @if($msg->message)
                                    <span class="msg-text">{{ $msg->message }}</span>
                                @endif
                                <span class="msg-meta">
                                    {{ $msg->created_at->format('g:i a') }}
                                    @if($msg->user_id === auth()->id())
                                        <i class="fa-solid fa-check-double {{ $msg->is_read ? 'read' : '' }}"></i>
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <div class="typing-indicator" id="typingIndicator">
                        <div class="typing-bubble">
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                        </div>
                    </div>
                </div>

                <div class="img-preview-strip" id="imagePreview">
                    <img id="previewImg" src="#" alt="">
                    <button class="remove-btn" onclick="clearImagePreview()"><i class="fa-solid fa-xmark"></i> Remove</button>
                </div>

                <form id="messageForm" enctype="multipart/form-data" style="margin:0;">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                    <input type="hidden" name="receiver_id" value="{{ $chatUser->id }}">
                    <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;" onchange="previewImage(this)">
                    <div class="chat-input-area">
                        <div class="emoji-picker-wrap">
                            <button type="button" class="input-icon-btn" id="emojiToggle" title="Emoji"><i class="fa-regular fa-face-smile"></i></button>
                            <div class="emoji-panel" id="emojiPanel">
                                <div class="emoji-search"><input type="text" id="emojiSearchInput" placeholder="Search emoji..."></div>
                                <div class="emoji-cats" id="emojiCategories"></div>
                                <div class="emoji-grid" id="emojiGrid"></div>
                            </div>
                        </div>
                        <label for="imageInput" class="input-icon-btn" title="Attach"><i class="fa-solid fa-paperclip"></i></label>
                        <input type="text" name="message" id="messageInput" class="chat-input-box" placeholder="Type a message" autocomplete="off">
                        <button type="submit" class="send-btn" id="sendBtn" title="Send"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>

            @else
            <div class="chat-window">
                <div class="empty-chat">
                    <svg width="70" height="70" viewBox="0 0 303 172" fill="none" style="opacity:0.3;">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M229.565 160.229C262.212 149.245 286.931 118.241 283.39 73.4194C278.009 5.31929 210.289 -7.09173 152.04 2.28699C93.7917 11.6657 29.5851 11.8446 6.22836 67.8679C-17.1283 123.891 35.4793 162.322 84.291 172.757L229.565 160.229Z" fill="currentColor"/>
                    </svg>
                    <h3>Your Messages</h3>
                    <p>Select a conversation or start a new chat with our support team</p>
                    <button class="new-chat-btn-green" onclick="startNewAdminChat()" style="padding:10px 22px;font-size:13px;margin-top:10px;border:none;cursor:pointer;">
                        <i class="fa-solid fa-plus"></i> Start Chat with Support
                    </button>
                    <div class="lock-text"><i class="fa-solid fa-lock" style="font-size:10px;"></i> Your messages are private</div>
                </div>
            </div>
            @endif
        </div>
    </main>
</div>

<script>
    function toggleChatSidebar() {
        document.querySelector('.conv-sidebar').classList.toggle('mobile-open');
        document.getElementById('chatOverlay').classList.toggle('active');
    }

    document.getElementById('convSearchInput')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.conv-item').forEach(item => {
            item.style.display = (item.dataset.name || '').includes(q) ? '' : 'none';
        });
    });

    function startNewAdminChat() {
        fetch('{{ route("user.messages.send") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ receiver_id: {{ $admin ? $admin->id : 1 }}, message: 'Hello! I need help.' })
        }).then(r=>r.json()).then(d=>{
            if(d.status==='success') window.location.href = "{{ route('user.messages.chat', ':id') }}".replace(':id', d.message.conversation_id);
        });
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { document.getElementById('previewImg').src = e.target.result; document.getElementById('imagePreview').classList.add('active'); };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function clearImagePreview() {
        document.getElementById('imageInput').value = '';
        document.getElementById('imagePreview').classList.remove('active');
    }

    // Emoji Picker
    const emojiData = {
        '😊':['Smileys','smile'],'😂':['Smileys','laugh'],'❤️':['Smileys','heart'],'😍':['Smileys','love'],
        '😘':['Smileys','kiss'],'🥰':['Smileys','love'],'😎':['Smileys','cool'],'🤣':['Smileys','laugh'],
        '😅':['Smileys','sweat'],'😭':['Smileys','cry'],'🥺':['Smileys','plead'],'😤':['Smileys','angry'],
        '🤔':['Smileys','think'],'😱':['Smileys','shock'],'😴':['Smileys','sleep'],'🤗':['Smileys','hug'],
        '🤩':['Smileys','star'],'😜':['Smileys','wink'],'🙄':['Smileys','roll'],'😏':['Smileys','smirk'],
        '😇':['Smileys','angel'],'🤯':['Smileys','mind'],'🥳':['Smileys','party'],
        '👍':['Gestures','thumbs up'],'👎':['Gestures','thumbs down'],'👏':['Gestures','clap'],
        '🙏':['Gestures','pray'],'👋':['Gestures','wave'],'✌️':['Gestures','peace'],
        '🤝':['Gestures','handshake'],'💪':['Gestures','strong'],'👌':['Gestures','ok'],
        '🤞':['Gestures','luck'],'✋':['Gestures','stop'],
        '🔥':['Objects','fire'],'⭐':['Objects','star'],'💯':['Objects','hundred'],
        '🎉':['Objects','party'],'🎁':['Objects','gift'],'💰':['Objects','money'],
        '📦':['Objects','package'],'🛒':['Objects','cart'],'💳':['Objects','card'],
        '📱':['Objects','phone'],'💻':['Objects','laptop'],'📧':['Objects','email'],
        '✅':['Symbols','check'],'❌':['Symbols','cross'],'⚠️':['Symbols','warning'],
        '❓':['Symbols','question'],'💬':['Symbols','chat'],'🔔':['Symbols','bell'],
        '📍':['Symbols','location'],'💡':['Symbols','idea'],'🚚':['Travel','truck'],
        '✈️':['Travel','plane'],'🏠':['Travel','home'],'🌍':['Travel','world'],
    };
    const cats = {'😊':'Smileys','👍':'Gestures','🔥':'Objects','✅':'Symbols','🚚':'Travel'};
    let curCat = null;

    function initEmojiPicker() {
        const toggle = document.getElementById('emojiToggle');
        const panel = document.getElementById('emojiPanel');
        const catC = document.getElementById('emojiCategories');
        if (!toggle || !panel) return;
        const allB = document.createElement('button');
        allB.className = 'emoji-cat-btn active'; allB.textContent = '🔤'; allB.title = 'All';
        allB.onclick = () => { curCat=null; document.querySelectorAll('.emoji-cat-btn').forEach(b=>b.classList.remove('active')); allB.classList.add('active'); renderEmojis(); };
        catC.appendChild(allB);
        Object.entries(cats).forEach(([e,n]) => {
            const b = document.createElement('button'); b.className='emoji-cat-btn'; b.textContent=e; b.title=n;
            b.onclick = () => { curCat=n; document.querySelectorAll('.emoji-cat-btn').forEach(x=>x.classList.remove('active')); b.classList.add('active'); renderEmojis(); };
            catC.appendChild(b);
        });
        renderEmojis();
        toggle.onclick = e => { e.stopPropagation(); panel.classList.toggle('active'); };
        document.getElementById('emojiSearchInput').addEventListener('input', () => renderEmojis());
        document.addEventListener('click', e => { if(!panel.contains(e.target)&&e.target!==toggle&&!toggle.contains(e.target)) panel.classList.remove('active'); });
    }
    function renderEmojis() {
        const grid = document.getElementById('emojiGrid');
        const s = (document.getElementById('emojiSearchInput')?.value||'').toLowerCase();
        grid.innerHTML = '';
        Object.entries(emojiData).forEach(([e,[c,k]]) => {
            if(curCat&&c!==curCat) return;
            if(s&&!k.includes(s)) return;
            const b = document.createElement('button'); b.className='emoji-btn'; b.textContent=e;
            b.onclick = () => { const inp=document.getElementById('messageInput'); if(inp){ const st=inp.selectionStart,en=inp.selectionEnd; inp.value=inp.value.substring(0,st)+e+inp.value.substring(en); inp.focus(); inp.setSelectionRange(st+e.length,st+e.length); }};
            grid.appendChild(b);
        });
    }

    @if(isset($conversation))
    const chatBox = document.getElementById('chatMessages');
    chatBox.scrollTop = chatBox.scrollHeight;
    let lastMsgId = {{ $messages->last() ? $messages->last()->id : 0 }};
    let typingTimeout = null;

    document.getElementById('messageInput')?.addEventListener('input', function() {
        clearTimeout(typingTimeout);
        fetch('{{ route("user.messages.typing") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ conversation_id: {{ $conversation->id }} })
        }).catch(()=>{});
        typingTimeout = setTimeout(()=>{}, 3000);
    });

    setInterval(() => {
        fetch(`{{ route('user.messages.poll', $conversation->id) }}?last_id=${lastMsgId}`)
        .then(r=>r.json()).then(data => {
            if (data.messages?.length > 0) {
                data.messages.forEach(msg => {
                    if (document.getElementById(`msg-${msg.id}`)) return;
                    const isSent = msg.user_id === {{ auth()->id() }};
                    let content = '';
                    if (msg.type==='image'&&msg.file_path) { const u=msg.file_path.includes('http')?msg.file_path:'/storage/'+msg.file_path; content+=`<img src="${u}" class="msg-image" onclick="window.open(this.src)">`; }
                    if (msg.message) content += `<span class="msg-text">${msg.message}</span>`;
                    const t = new Date(msg.created_at).toLocaleTimeString([],{hour:'numeric',minute:'2-digit',hour12:true}).toLowerCase();
                    const ri = isSent ? `<i class="fa-solid fa-check-double ${msg.is_read?'read':''}"></i>` : '';
                    const html = `<div class="msg-row ${isSent?'sent':'received'}"><div id="msg-${msg.id}" class="msg-bubble ${isSent?'sent':'received'}">${content}<span class="msg-meta">${t} ${ri}</span></div></div>`;
                    document.getElementById('typingIndicator').insertAdjacentHTML('beforebegin', html);
                    if (msg.id > lastMsgId) lastMsgId = msg.id;
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            }
            const dot=document.getElementById('onlineDot'), txt=document.getElementById('onlineText');
            if(data.isOnline){ dot.className='online-dot online'; txt.textContent='online'; }
            else { dot.className='online-dot offline'; txt.textContent=data.lastSeen!=='Never'?'last seen '+data.lastSeen:'offline'; }
        });
        fetch(`{{ route('user.messages.typing.status', $conversation->id) }}`)
        .then(r=>r.json()).then(d=>{ const i=document.getElementById('typingIndicator'); if(d.isTyping){i.classList.add('active');chatBox.scrollTop=chatBox.scrollHeight;}else{i.classList.remove('active');} }).catch(()=>{});
    }, 2500);

    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const fd = new FormData(this), mi = document.getElementById('messageInput'), sb = document.getElementById('sendBtn');
        if (!mi.value.trim() && !document.getElementById('imageInput').files.length) return;
        const orig = mi.value; mi.value = ''; document.getElementById('imageInput').value = ''; clearImagePreview();
        sb.disabled = true;
        fetch('{{ route("user.messages.send") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            body: fd
        }).then(r=>r.json()).then(d=>{ if(d.status==='success') mi.focus(); else { mi.value=orig; alert('Error'); }
        }).catch(()=>{ mi.value=orig; }).finally(()=>{ sb.disabled=false; });
    });
    @endif

    document.addEventListener('DOMContentLoaded', () => initEmojiPicker());
</script>
@endsection
