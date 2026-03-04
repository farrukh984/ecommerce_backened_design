@extends('layouts.app')

@section('hide_chrome', true)

@section('content')
<link rel="stylesheet" href="{{ asset('css/user_dashboard.css') }}">
<style>
/* ═══════════════════════════════════════════════════════════
   OVERRIDE DASHBOARD CSS FOR MESSAGES PAGE
   ═══════════════════════════════════════════════════════════ */
.dashboard-container {
    padding: 0 !important;
    gap: 0 !important;
    max-width: 100% !important;
    height: 100vh !important;
    min-height: 100vh !important;
    overflow: hidden !important;
}
.dashboard-main {
    flex: 1 !important;
    min-width: 0 !important;
    padding: 0 !important;
    gap: 0 !important;
    overflow: hidden !important;
    animation: none !important;
    min-height: 0 !important;
}
/* Mobile override too */
@media (max-width: 992px) {
    .dashboard-container {
        padding: 0 !important;
        flex-direction: row !important;
    }
}

/* ═══════════════════════════════════════════════════════════
   WHATSAPP DESKTOP DARK THEME — USER PANEL
   ═══════════════════════════════════════════════════════════ */
:root {
    --wa-bg-deep:     #0b141a;
    --wa-bg-panel:    #111b21;
    --wa-bg-header:   #202c33;
    --wa-bg-input:    #2a3942;
    --wa-bg-hover:    #202c33;
    --wa-bg-active:   #2a3942;
    --wa-bg-sent:     #005c4b;
    --wa-bg-received: #202c33;
    --wa-green:       #00a884;
    --wa-green-light: #25d366;
    --wa-text:        #e9edef;
    --wa-sub:         #8696a0;
    --wa-border:      #222d34;
    --wa-chat-bg:     #0b141a;
}

/* ═══ CHAT CONTAINER ══════════════════════════════════════ */
.wa-chat-wrap {
    display: grid;
    grid-template-columns: 320px 1fr;
    width: 100%;
    height: 100%;
    background: var(--wa-bg-deep);
    overflow: hidden;
}

/* ═══ LEFT — CONVERSATIONS SIDEBAR ═══════════════════════ */
.wa-sidebar {
    display: flex;
    flex-direction: column;
    background: var(--wa-bg-panel);
    border-right: 1px solid var(--wa-border);
    height: 100%;
    overflow: hidden;
    /* on mobile, shown as full-screen, hidden when chat is open */
}

.wa-sidebar-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 16px;
    background: var(--wa-bg-header);
    min-height: 56px;
    flex-shrink: 0;
}
.wa-sidebar-head h3 {
    margin: 0;
    font-size: 19px;
    font-weight: 700;
    color: var(--wa-text);
}
.wa-sidebar-head-actions {
    display: flex;
    gap: 4px;
}
.wa-icon-btn {
    width: 36px; height: 36px;
    border: none; background: transparent;
    color: var(--wa-sub);
    border-radius: 50%;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    transition: background 0.15s, color 0.15s;
    text-decoration: none;
    flex-shrink: 0;
}
.wa-icon-btn:hover {
    background: rgba(255,255,255,0.07);
    color: var(--wa-text);
}
.wa-icon-btn.green {
    background: var(--wa-green);
    color: #111b21;
    border-radius: 20px;
    padding: 0 14px;
    width: auto;
    font-size: 13px;
    font-weight: 700;
    gap: 5px;
}
.wa-icon-btn.green:hover { background: var(--wa-green-light); }

.wa-search {
    padding: 8px 12px;
    flex-shrink: 0;
    background: var(--wa-bg-panel);
}
.wa-search-box {
    display: flex;
    align-items: center;
    background: var(--wa-bg-input);
    border-radius: 8px;
    padding: 0 14px;
    gap: 10px;
}
.wa-search-box i { color: var(--wa-sub); font-size: 14px; flex-shrink: 0; }
.wa-search-box input {
    flex: 1; border: none; background: transparent;
    padding: 9px 0;
    font-size: 14px; color: var(--wa-text);
    outline: none; font-family: inherit;
}
.wa-search-box input::placeholder { color: var(--wa-sub); }

.wa-conv-list {
    flex: 1;
    overflow-y: auto;
    min-height: 0;
}
.wa-conv-list::-webkit-scrollbar { width: 6px; }
.wa-conv-list::-webkit-scrollbar-track { background: transparent; }
.wa-conv-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 10px; }

.wa-conv-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    cursor: pointer;
    transition: background 0.15s;
    text-decoration: none;
    color: inherit;
    border-bottom: 1px solid rgba(255,255,255,0.03);
    position: relative;
}
.wa-conv-item:hover { background: var(--wa-bg-hover); }
.wa-conv-item.active {
    background: var(--wa-bg-active);
}
.wa-conv-item.active::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--wa-green);
}
.wa-avatar {
    width: 49px; height: 49px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.wa-avatar-ph {
    width: 49px; height: 49px;
    border-radius: 50%;
    background: var(--wa-bg-input);
    color: var(--wa-sub);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 19px;
    flex-shrink: 0;
}
.wa-conv-info { flex: 1; min-width: 0; }
.wa-conv-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 3px;
}
.wa-conv-name {
    font-size: 15px;
    font-weight: 600;
    color: var(--wa-text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    align-items: center;
    gap: 5px;
}
.wa-conv-time {
    font-size: 11px;
    color: var(--wa-sub);
    flex-shrink: 0;
    margin-left: 8px;
}
.wa-conv-item.has-unread .wa-conv-time { color: var(--wa-green); }
.wa-conv-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.wa-conv-last {
    font-size: 13px;
    color: var(--wa-sub);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}
.wa-conv-badge {
    min-width: 20px; height: 20px;
    border-radius: 50%;
    background: var(--wa-green);
    color: #111b21;
    font-size: 11px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    padding: 0 5px;
    margin-left: 8px;
    flex-shrink: 0;
}
.wa-verified { color: #53bdeb; font-size: 13px; }

/* ═══ RIGHT — CHAT WINDOW ════════════════════════════════ */
.wa-chat-win {
    display: flex;
    flex-direction: column;
    background: var(--wa-chat-bg);
    height: 100%;
    min-height: 0;
    overflow: hidden;
}

.wa-chat-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 16px;
    background: var(--wa-bg-header);
    min-height: 56px;
    flex-shrink: 0;
}
.wa-chat-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.wa-chat-avatar-ph {
    width: 40px; height: 40px; border-radius: 50%;
    background: var(--wa-bg-input); color: var(--wa-sub);
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px; flex-shrink: 0;
}
.wa-chat-info { flex: 1; min-width: 0; }
.wa-chat-info h4 {
    margin: 0; font-size: 15px; font-weight: 600;
    color: var(--wa-text); line-height: 1.2;
    display: flex; align-items: center; gap: 6px;
}
.wa-chat-status {
    font-size: 12px; color: var(--wa-sub);
    display: flex; align-items: center; gap: 5px;
    margin-top: 1px;
}
.online-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; }
.online-dot.on  { background: var(--wa-green); box-shadow: 0 0 6px rgba(0,168,132,0.5); }
.online-dot.off { background: var(--wa-sub); }

/* Messages */
.wa-msgs {
    flex: 1;
    overflow-y: auto;
    padding: 20px 40px;
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-height: 0;
    scroll-behavior: smooth;
    background-color: var(--wa-chat-bg);
    background-image: url("data:image/svg+xml,%3Csvg width='400' height='400' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.015'%3E%3Ccircle r='3' cx='50' cy='50'/%3E%3Ccircle r='2' cx='150' cy='120'/%3E%3Ccircle r='2.5' cx='300' cy='80'/%3E%3Ccircle r='1.5' cx='80' cy='250'/%3E%3Ccircle r='3' cx='350' cy='300'/%3E%3Ccircle r='2' cx='200' cy='350'/%3E%3C/g%3E%3C/svg%3E");
}
.wa-msgs::-webkit-scrollbar { width: 6px; }
.wa-msgs::-webkit-scrollbar-track { background: transparent; }
.wa-msgs::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.07); border-radius: 10px; }

.wa-msg-row { display: flex; }
.wa-msg-row.sent     { justify-content: flex-end; }
.wa-msg-row.received { justify-content: flex-start; }

.wa-bubble {
    max-width: 65%;
    padding: 7px 10px 4px;
    border-radius: 8px;
    font-size: 14px;
    line-height: 1.5;
    word-wrap: break-word;
    position: relative;
    animation: waIn 0.18s ease;
}
@keyframes waIn { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
.wa-bubble.sent     { background: var(--wa-bg-sent);     color: var(--wa-text); border-top-right-radius: 0; }
.wa-bubble.received { background: var(--wa-bg-received); color: var(--wa-text); border-top-left-radius: 0;  }
.wa-msg-text { display: block; margin-right: 52px; }
.wa-msg-img  { max-width: 280px; border-radius: 6px; margin-bottom: 4px; cursor: pointer; display: block; }
.wa-msg-meta {
    float: right;
    margin-top: 2px; margin-left: 8px;
    display: flex; align-items: center; gap: 3px;
    font-size: 11px; color: rgba(255,255,255,0.5);
    white-space: nowrap;
}
.wa-msg-meta .fa-check-double.read { color: #53bdeb; }

/* Typing dots */
.wa-typing        { display: none; justify-content: flex-start; }
.wa-typing.active { display: flex; }
.wa-typing-bub {
    background: var(--wa-bg-received);
    padding: 10px 16px;
    border-radius: 8px; border-top-left-radius: 0;
    display: flex; align-items: center; gap: 4px;
}
.wa-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: var(--wa-sub);
    animation: waDot 1.4s infinite ease-in-out;
}
.wa-dot:nth-child(2) { animation-delay: 0.2s; }
.wa-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes waDot { 0%,60%,100%{transform:translateY(0);opacity:0.4;} 30%{transform:translateY(-6px);opacity:1;} }

/* ─── Input Bar ──────────────────────────────────────────── */
.wa-input-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    background: var(--wa-bg-header);
    flex-shrink: 0;
}
.wa-img-preview {
    display: none;
    align-items: center;
    gap: 10px;
    padding: 6px 16px;
    background: var(--wa-bg-header);
    border-top: 1px solid var(--wa-border);
    flex-shrink: 0;
}
.wa-img-preview.on { display: flex; }
.wa-img-preview img { max-height: 55px; border-radius: 6px; border: 1px solid var(--wa-border); }
.wa-img-remove {
    border: none; background: rgba(255,80,80,0.2); color: #ff6060;
    padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 12px;
}

.wa-text-input {
    flex: 1;
    background: var(--wa-bg-input);
    border: none;
    border-radius: 8px;
    padding: 10px 16px;
    font-size: 14px;
    color: var(--wa-text);
    outline: none;
    font-family: inherit;
    min-width: 0;
}
.wa-text-input::placeholder { color: var(--wa-sub); }

.wa-send-btn {
    width: 44px; height: 44px;
    border: none; border-radius: 50%;
    background: var(--wa-green);
    color: #111b21;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    transition: background 0.2s;
    flex-shrink: 0;
}
.wa-send-btn:hover   { background: var(--wa-green-light); }
.wa-send-btn:disabled { opacity: 0.5; cursor: not-allowed; }

/* ─── Emoji Picker ───────────────────────────────────────── */
.wa-emoji-wrap { position: relative; flex-shrink: 0; }
.wa-emoji-panel {
    display: none;
    position: absolute;
    bottom: 54px; left: 0;
    width: 320px; max-height: 340px;
    background: var(--wa-bg-header);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.45);
    border: 1px solid var(--wa-border);
    z-index: 999;
    flex-direction: column;
    overflow: hidden;
    animation: waEmojiUp 0.2s ease;
}
.wa-emoji-panel.open { display: flex; }
@keyframes waEmojiUp { from{opacity:0;transform:translateY(10px);} to{opacity:1;transform:translateY(0);} }
.wa-emoji-search { padding: 10px; border-bottom: 1px solid var(--wa-border); }
.wa-emoji-search input {
    width: 100%; padding: 8px 12px;
    background: var(--wa-bg-input); border: none;
    border-radius: 6px; font-size: 13px;
    color: var(--wa-text); outline: none; font-family: inherit;
}
.wa-emoji-search input::placeholder { color: var(--wa-sub); }
.wa-emoji-cats {
    display: flex; gap: 2px; padding: 6px 10px;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    overflow-x: auto;
}
.wa-cat-btn {
    padding: 4px 8px; border: none; background: transparent;
    border-radius: 6px; cursor: pointer; font-size: 15px;
    transition: background 0.15s; flex-shrink: 0;
}
.wa-cat-btn:hover, .wa-cat-btn.on { background: var(--wa-bg-input); }
.wa-emoji-grid {
    flex: 1; overflow-y: auto; padding: 8px;
    display: grid; grid-template-columns: repeat(8,1fr); gap: 1px;
}
.wa-emoji-grid::-webkit-scrollbar { width: 4px; }
.wa-emoji-grid::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
.wa-emoji-btn {
    width: 36px; height: 36px; border: none; background: transparent;
    border-radius: 6px; cursor: pointer; font-size: 20px;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.1s, transform 0.1s;
}
.wa-emoji-btn:hover { background: var(--wa-bg-input); transform: scale(1.15); }

/* ─── Empty State ────────────────────────────────────────── */
.wa-empty {
    flex: 1;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    text-align: center; padding: 40px;
    gap: 10px;
}
.wa-empty svg { color: var(--wa-sub); opacity: 0.2; }
.wa-empty h3  { color: var(--wa-text); font-size: 28px; font-weight: 300; margin: 8px 0 4px; }
.wa-empty p   { color: var(--wa-sub); font-size: 13px; max-width: 360px; line-height: 1.6; margin: 0; }
.wa-empty-lock { font-size: 12px; color: var(--wa-sub); margin-top: 20px; display: flex; align-items: center; gap: 6px; }

/* ═══ MOBILE ═════════════════════════════════════════════ */
@media (max-width: 991px) {
    .wa-chat-wrap {
        grid-template-columns: 1fr;
        position: relative;
    }

    /* On mobile: show sidebar as full-screen when no conv, hide when conv open */
    @if(isset($conversation))
    .wa-sidebar {
        position: fixed;
        left: -100%; top: 0;
        width: 300px; height: 100%;
        z-index: 1060;
        transition: left 0.3s ease;
    }
    .wa-sidebar.open { left: 0; }
    .wa-chat-win { display: flex; }
    @else
    .wa-chat-win { display: none; }
    .wa-sidebar { position: relative; height: 100%; }
    @endif

    .wa-overlay {
        position: fixed; inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 1059;
        display: none;
    }
    .wa-overlay.on { display: block; }

    .wa-msgs { padding: 16px 12px; }
    .wa-bubble { max-width: 82%; }
    .wa-emoji-panel {
        position: fixed; bottom: 0; left: 0; right: 0; width: 100%;
        max-height: 45vh; border-radius: 14px 14px 0 0;
    }
    .mob-back { display: flex !important; }
    .desk-only { display: none !important; }
}
@media (min-width: 992px) {
    .mob-back { display: none !important; }
    .wa-overlay { display: none !important; }
}
</style>

<div class="dashboard-container">
    @include('user.partials.sidebar', ['active' => 'messages'])

    <main class="dashboard-main">
        <div class="wa-chat-wrap">

            {{-- ████ LEFT — CONVERSATIONS LIST ████ --}}
            <div class="wa-sidebar" id="waSidebar">
                {{-- Header --}}
                <div class="wa-sidebar-head">
                    <h3>Chats</h3>
                    <div class="wa-sidebar-head-actions">
                        <a href="#" class="wa-icon-btn green" onclick="event.preventDefault(); startNewChat();">
                            <i class="fa-solid fa-plus"></i> New
                        </a>
                        <button class="wa-icon-btn mob-back" onclick="closeSidebar()" title="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                {{-- Search --}}
                <div class="wa-search">
                    <div class="wa-search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="convSearch" placeholder="Search conversations...">
                    </div>
                </div>

                {{-- List --}}
                <div class="wa-conv-list" id="convList">
                    @forelse($conversations as $conv)
                        @php
                            $other   = $conv->sender_id === auth()->id() ? $conv->receiver : $conv->sender;
                            $last    = $conv->messages->sortByDesc('created_at')->first();
                            $unread  = $conv->messages->where('user_id','!=',auth()->id())->where('is_read',false)->count();
                        @endphp
                        <a href="{{ route('user.messages.chat', $conv->id) }}"
                           class="wa-conv-item {{ isset($conversation) && $conversation->id === $conv->id ? 'active' : '' }} {{ $unread > 0 ? 'has-unread' : '' }}"
                           data-name="{{ strtolower($other->name) }}">
                            @if($other->profile_image)
                                <img src="{{ display_image($other->profile_image) }}" class="wa-avatar">
                            @else
                                <div class="wa-avatar-ph">{{ strtoupper(substr($other->name,0,1)) }}</div>
                            @endif
                            <div class="wa-conv-info">
                                <div class="wa-conv-top">
                                    <span class="wa-conv-name">
                                        {{ $other->name }}
                                        @if($other->role==='admin')<i class="fa-solid fa-circle-check wa-verified"></i>@endif
                                    </span>
                                    @if($last)<span class="wa-conv-time">{{ $last->created_at->format('g:i a') }}</span>@endif
                                </div>
                                <div class="wa-conv-bottom">
                                    <span class="wa-conv-last">
                                        @if($last)
                                            @if($last->user_id===auth()->id())
                                                <i class="fa-solid fa-check-double" style="font-size:13px;color:{{ $last->is_read ? '#53bdeb' : 'var(--wa-sub)' }};margin-right:2px;"></i>
                                            @endif
                                            {{ $last->type==='image' ? '📷 Photo' : Str::limit($last->message, 35) }}
                                        @else
                                            Start a conversation
                                        @endif
                                    </span>
                                    @if($unread > 0)<span class="wa-conv-badge">{{ $unread }}</span>@endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div style="padding:40px 20px;text-align:center;color:var(--wa-sub);">
                            <i class="fa-solid fa-comment-dots" style="font-size:32px;opacity:0.25;display:block;margin-bottom:12px;"></i>
                            <p style="font-size:13px;margin:0;">No conversations yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Mobile overlay --}}
            <div class="wa-overlay" id="waOverlay" onclick="closeSidebar()"></div>

            {{-- ████ RIGHT — CHAT WINDOW ████ --}}
            @if(isset($conversation))
                @php $cu = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender; @endphp
                <div class="wa-chat-win">
                    {{-- Chat Header --}}
                    <div class="wa-chat-head">
                        <button class="wa-icon-btn mob-back" onclick="openSidebar()" title="Chats">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                        @if($cu->profile_image)
                            <img src="{{ display_image($cu->profile_image) }}" class="wa-chat-avatar">
                        @else
                            <div class="wa-chat-avatar-ph">{{ strtoupper(substr($cu->name,0,1)) }}</div>
                        @endif
                        <div class="wa-chat-info">
                            <h4>
                                {{ $cu->name }}
                                @if($cu->role==='admin')<i class="fa-solid fa-circle-check wa-verified"></i>@endif
                            </h4>
                            <div class="wa-chat-status">
                                <span class="online-dot off" id="onDot"></span>
                                <span id="onText">checking...</span>
                            </div>
                        </div>
                        <div class="desk-only" style="display:flex;gap:4px;">
                            <button class="wa-icon-btn" title="Search in chat"><i class="fa-solid fa-magnifying-glass"></i></button>
                            <button class="wa-icon-btn" title="More options"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                        </div>
                    </div>

                    {{-- Messages --}}
                    <div class="wa-msgs" id="waMsgs">
                        @foreach($messages as $msg)
                            <div class="wa-msg-row {{ $msg->user_id===auth()->id() ? 'sent' : 'received' }}">
                                <div id="msg-{{ $msg->id }}" class="wa-bubble {{ $msg->user_id===auth()->id() ? 'sent' : 'received' }}">
                                    @if($msg->type==='image' && $msg->file_path)
                                        <img src="{{ display_image($msg->file_path) }}" class="wa-msg-img" onclick="window.open(this.src)">
                                    @endif
                                    @if($msg->message)
                                        <span class="wa-msg-text">{{ $msg->message }}</span>
                                    @endif
                                    <span class="wa-msg-meta">
                                        {{ $msg->created_at->format('g:i a') }}
                                        @if($msg->user_id===auth()->id())
                                            <i class="fa-solid fa-check-double {{ $msg->is_read ? 'read' : '' }}"></i>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                        {{-- Typing indicator --}}
                        <div class="wa-typing" id="waTyping">
                            <div class="wa-typing-bub">
                                <div class="wa-dot"></div>
                                <div class="wa-dot"></div>
                                <div class="wa-dot"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Image preview strip --}}
                    <div class="wa-img-preview" id="imgPreview">
                        <img id="previewImg" src="#" alt="">
                        <button class="wa-img-remove" onclick="clearImg()">
                            <i class="fa-solid fa-xmark"></i> Remove
                        </button>
                    </div>

                    {{-- Input form --}}
                    <form id="msgForm" enctype="multipart/form-data" style="margin:0;flex-shrink:0;">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                        <input type="hidden" name="receiver_id"     value="{{ $cu->id }}">
                        <input type="file"   name="image" id="imgInput" accept="image/*" style="display:none;" onchange="previewImg(this)">
                        <div class="wa-input-bar">
                            <div class="wa-emoji-wrap">
                                <button type="button" class="wa-icon-btn" id="emojiBtn" title="Emoji">
                                    <i class="fa-regular fa-face-smile" style="font-size:22px;"></i>
                                </button>
                                <div class="wa-emoji-panel" id="emojiPanel">
                                    <div class="wa-emoji-search">
                                        <input type="text" id="emojiSearch" placeholder="Search emoji...">
                                    </div>
                                    <div class="wa-emoji-cats" id="emojiCats"></div>
                                    <div class="wa-emoji-grid" id="emojiGrid"></div>
                                </div>
                            </div>
                            <label for="imgInput" class="wa-icon-btn" title="Attach photo">
                                <i class="fa-solid fa-paperclip" style="font-size:20px;"></i>
                            </label>
                            <input type="text" name="message" id="msgInput"
                                   class="wa-text-input" placeholder="Type a message" autocomplete="off">
                            <button type="submit" class="wa-send-btn" id="sendBtn">
                                <i class="fa-solid fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>

            @else
                <div class="wa-chat-win">
                    <div class="wa-empty">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H5.17L4 17.17V4h16v12z"/>
                        </svg>
                        <h3>Your Messages</h3>
                        <p>Select a conversation from the left panel, or start a new chat with our support team</p>
                        <button onclick="startNewChat()" style="background:var(--wa-green);border:none;color:#111b21;padding:10px 24px;border-radius:22px;font-size:14px;font-weight:700;cursor:pointer;margin-top:10px;">
                            <i class="fa-solid fa-plus"></i> Start Chat
                        </button>
                        <div class="wa-empty-lock">
                            <i class="fa-solid fa-lock" style="font-size:10px;"></i> Your messages are private
                        </div>
                    </div>
                </div>
            @endif

        </div>{{-- end wa-chat-wrap --}}
    </main>
</div>

<script>
// ─── Sidebar toggle (mobile) ──────────────────────────────
function openSidebar() {
    document.getElementById('waSidebar').classList.add('open');
    document.getElementById('waOverlay').classList.add('on');
}
function closeSidebar() {
    document.getElementById('waSidebar').classList.remove('open');
    document.getElementById('waOverlay').classList.remove('on');
}

// ─── Conversation search ──────────────────────────────────
document.getElementById('convSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.wa-conv-item').forEach(el => {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
});

// ─── Start new admin chat ─────────────────────────────────
function startNewChat() {
    fetch('{{ route("user.messages.send") }}', {
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json','Accept':'application/json'},
        body: JSON.stringify({ receiver_id: {{ $admin ? $admin->id : 1 }}, message: 'Hello! I need help.' })
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success')
            window.location.href = "{{ route('user.messages.chat', ':id') }}".replace(':id', d.message.conversation_id);
    });
}

// ─── Image preview ────────────────────────────────────────
function previewImg(input) {
    if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imgPreview').classList.add('on');
        };
        r.readAsDataURL(input.files[0]);
    }
}
function clearImg() {
    document.getElementById('imgInput').value = '';
    document.getElementById('imgPreview').classList.remove('on');
}

// ─── Emoji picker ─────────────────────────────────────────
const EMOJIS = {
    '😊':['Smileys','smile happy'],'😂':['Smileys','laugh cry'],'❤️':['Smileys','heart love'],
    '😍':['Smileys','love eyes'],'😘':['Smileys','kiss'],'🥰':['Smileys','love'],
    '😎':['Smileys','cool'],'🤣':['Smileys','laugh'],'😅':['Smileys','sweat smile'],
    '😭':['Smileys','cry'],'🥺':['Smileys','plead'],'😤':['Smileys','angry'],
    '🤔':['Smileys','think'],'😱':['Smileys','shock'],'😴':['Smileys','sleep'],
    '🤗':['Smileys','hug'],'🤩':['Smileys','star eyes'],'😜':['Smileys','wink'],
    '🙄':['Smileys','eye roll'],'😏':['Smileys','smirk'],'😇':['Smileys','angel'],
    '🤯':['Smileys','mind blown'],'🥳':['Smileys','party'],
    '👍':['Gestures','thumbs up'],'👎':['Gestures','thumbs down'],
    '👏':['Gestures','clap'],'🙏':['Gestures','pray thanks'],
    '👋':['Gestures','wave hello'],'✌️':['Gestures','peace'],
    '🤝':['Gestures','handshake deal'],'💪':['Gestures','strong muscle'],
    '👌':['Gestures','ok perfect'],'🤞':['Gestures','fingers crossed'],
    '✋':['Gestures','stop hand'],
    '🔥':['Objects','fire hot'],'⭐':['Objects','star'],'💯':['Objects','hundred'],
    '🎉':['Objects','party celebrate'],'🎁':['Objects','gift present'],
    '💰':['Objects','money'],'📦':['Objects','package box'],
    '🛒':['Objects','cart shopping'],'💳':['Objects','card payment'],
    '📱':['Objects','phone mobile'],'💻':['Objects','laptop'],
    '📧':['Objects','email'],'📸':['Objects','camera photo'],
    '✅':['Symbols','check done'],'❌':['Symbols','cross no'],
    '⚠️':['Symbols','warning'],'❓':['Symbols','question'],
    '💬':['Symbols','chat message'],'🔔':['Symbols','bell notification'],
    '📍':['Symbols','location pin'],'💡':['Symbols','idea bulb'],
    '🚚':['Travel','truck delivery'],'✈️':['Travel','plane flight'],
    '🏠':['Travel','home house'],'🌍':['Travel','world earth'],
};
const CATS = { '😊':'Smileys','👍':'Gestures','🔥':'Objects','✅':'Symbols','🚚':'Travel' };
let curCat = null;

(function initEmoji() {
    const btn   = document.getElementById('emojiBtn');
    const panel = document.getElementById('emojiPanel');
    const cats  = document.getElementById('emojiCats');
    const grid  = document.getElementById('emojiGrid');
    const srch  = document.getElementById('emojiSearch');
    if (!btn || !panel) return;

    // All button
    const allB = mkCatBtn('🔤','All',null);
    allB.classList.add('on');
    cats.appendChild(allB);
    Object.entries(CATS).forEach(([e,n]) => cats.appendChild(mkCatBtn(e,n,n)));

    renderEmoji();

    btn.addEventListener('click', e => { e.stopPropagation(); panel.classList.toggle('open'); });
    srch.addEventListener('input', renderEmoji);
    document.addEventListener('click', e => {
        if (!panel.contains(e.target) && e.target !== btn && !btn.contains(e.target))
            panel.classList.remove('open');
    });

    function mkCatBtn(emoji, title, cat) {
        const b = document.createElement('button');
        b.className = 'wa-cat-btn'; b.textContent = emoji; b.title = title;
        b.onclick = () => {
            curCat = cat;
            document.querySelectorAll('.wa-cat-btn').forEach(x => x.classList.remove('on'));
            b.classList.add('on');
            renderEmoji();
        };
        return b;
    }
    function renderEmoji() {
        grid.innerHTML = '';
        const s = (srch.value || '').toLowerCase();
        Object.entries(EMOJIS).forEach(([e,[c,k]]) => {
            if (curCat && c !== curCat) return;
            if (s && !k.includes(s)) return;
            const b = document.createElement('button');
            b.className = 'wa-emoji-btn'; b.textContent = e;
            b.onclick = () => {
                const inp = document.getElementById('msgInput');
                const st = inp.selectionStart, en = inp.selectionEnd;
                inp.value = inp.value.slice(0,st) + e + inp.value.slice(en);
                inp.focus();
                inp.setSelectionRange(st + e.length, st + e.length);
            };
            grid.appendChild(b);
        });
    }
})();

// ─── Conversation polling + typing ────────────────────────
@if(isset($conversation))
const msgBox = document.getElementById('waMsgs');
msgBox.scrollTop = msgBox.scrollHeight;

let lastId = {{ $messages->last() ? $messages->last()->id : 0 }};
let typingTmr = null;

// Typing: send event on keypress
document.getElementById('msgInput')?.addEventListener('input', () => {
    clearTimeout(typingTmr);
    fetch('{{ route("user.messages.typing") }}', {
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json','Accept':'application/json'},
        body: JSON.stringify({ conversation_id: {{ $conversation->id }} })
    }).catch(()=>{});
    typingTmr = setTimeout(()=>{}, 3000);
});

setInterval(() => {
    // New messages
    fetch(`{{ route('user.messages.poll', $conversation->id) }}?last_id=${lastId}`)
    .then(r=>r.json()).then(data => {
        (data.messages||[]).forEach(msg => {
            if (document.getElementById('msg-'+msg.id)) return;
            const sent = msg.user_id === {{ auth()->id() }};
            let c = '';
            if (msg.type==='image' && msg.file_path) {
                const u = msg.file_path.includes('http') ? msg.file_path : '/storage/'+msg.file_path;
                c += `<img src="${u}" class="wa-msg-img" onclick="window.open(this.src)">`;
            }
            if (msg.message) c += `<span class="wa-msg-text">${msg.message}</span>`;
            const t = new Date(msg.created_at).toLocaleTimeString([],{hour:'numeric',minute:'2-digit',hour12:true});
            const ri = sent ? `<i class="fa-solid fa-check-double ${msg.is_read?'read':''}"></i>` : '';
            const html = `<div class="wa-msg-row ${sent?'sent':'received'}"><div id="msg-${msg.id}" class="wa-bubble ${sent?'sent':'received'}">${c}<span class="wa-msg-meta">${t} ${ri}</span></div></div>`;
            document.getElementById('waTyping').insertAdjacentHTML('beforebegin', html);
            if (msg.id > lastId) lastId = msg.id;
        });
        if ((data.messages||[]).length) msgBox.scrollTop = msgBox.scrollHeight;

        // Online dot
        const dot = document.getElementById('onDot'), txt = document.getElementById('onText');
        if (data.isOnline) { dot.className='online-dot on'; txt.textContent='online'; }
        else { dot.className='online-dot off'; txt.textContent = data.lastSeen!=='Never' ? 'last seen '+data.lastSeen : 'offline'; }
    });

    // Typing status
    fetch(`{{ route('user.messages.typing.status', $conversation->id) }}`)
    .then(r=>r.json()).then(d=>{
        const t = document.getElementById('waTyping');
        if (d.isTyping) { t.classList.add('active'); msgBox.scrollTop = msgBox.scrollHeight; }
        else t.classList.remove('active');
    }).catch(()=>{});
}, 2500);

// Send message
document.getElementById('msgForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const fd  = new FormData(this);
    const inp = document.getElementById('msgInput');
    const sb  = document.getElementById('sendBtn');
    if (!inp.value.trim() && !document.getElementById('imgInput').files.length) return;
    const orig = inp.value;
    inp.value = ''; document.getElementById('imgInput').value = ''; clearImg();
    sb.disabled = true;
    fetch('{{ route("user.messages.send") }}', {
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest','Accept':'application/json'},
        body: fd
    }).then(r=>r.json()).then(d=>{
        if (d.status==='success') inp.focus();
        else { inp.value=orig; alert('Could not send message'); }
    }).catch(()=>{ inp.value=orig; }).finally(()=>{ sb.disabled=false; });
});
@endif
</script>
@endsection
