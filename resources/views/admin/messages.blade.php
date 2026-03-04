@extends('layouts.admin')

@section('title', 'Messages')
@section('header_title', 'Customer Messages')

@section('styles')
<style>
    /* ═══════════════════════════════════════════════════════════
       PREMIUM WHATSAPP-STYLE CHAT — ADMIN PANEL
       ═══════════════════════════════════════════════════════════ */

    @media (min-width: 992px) {
        .admin-topbar { margin-bottom: 12px !important; }
        .admin-main { padding: 15px 28px 15px !important; }
    }

    /* ─── Main Container ─────────────────────────────────────── */
    .chat-container {
        display: grid;
        grid-template-columns: 1fr 340px;
        height: calc(100vh - 120px);
        min-height: 550px;
        background: #ffffff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(226,232,240,0.8);
        box-shadow: 0 20px 60px -15px rgba(0,0,0,0.08), 0 0 0 1px rgba(226,232,240,0.3);
    }

    /* ─── Mobile Responsive ──────────────────────────────────── */
    @media (max-width: 991px) {
        .chat-container {
            grid-template-columns: 1fr;
            height: calc(100vh - 80px);
            min-height: unset;
            border-radius: 0;
            border: none;
            box-shadow: none;
            margin: -24px -15px;
            width: calc(100% + 30px);
        }
        .conv-sidebar {
            position: fixed;
            right: 0; top: 0;
            width: 320px; height: 100vh;
            z-index: 1060;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.4,0,0.2,1);
            box-shadow: -25px 0 60px rgba(0,0,0,0.15);
        }
        .conv-sidebar.mobile-open { transform: translateX(0); }
        .chat-window { width: 100% !important; display: flex !important; }
        .mobile-back-btn { display: flex !important; }
        .chat-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15,23,42,0.55);
            z-index: 1055;
            display: none;
            backdrop-filter: blur(6px);
        }
        .chat-overlay.active { display: block; }

        @if(isset($conversation))
            .conv-sidebar { /* hidden by default on mobile */ }
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

    /* ─── Chat Window (Left) ─────────────────────────────────── */
    .chat-window {
        display: flex;
        flex-direction: column;
        background: #fff;
        min-height: 0;
        height: 100%;
        position: relative;
    }

    /* Header */
    .chat-header {
        padding: 14px 20px;
        border-bottom: 1px solid var(--admin-border);
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(20px);
        z-index: 5;
    }
    .chat-header-avatar {
        width: 44px; height: 44px;
        border-radius: 14px;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #f1f5f9;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .chat-header-avatar-placeholder {
        width: 44px; height: 44px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--admin-primary), #7c3aed);
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 16px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px var(--admin-primary-glow);
    }
    .chat-header-info { flex: 1; min-width: 0; }
    .chat-header-info h4 {
        margin: 0; font-size: 15px; font-weight: 700;
        color: #0f172a; line-height: 1.2;
    }
    .chat-header-info .online-status {
        font-size: 12px; display: flex; align-items: center; gap: 6px; margin-top: 2px;
    }
    .online-dot {
        width: 8px; height: 8px; border-radius: 50%; display: inline-block;
        transition: all 0.3s;
    }
    .online-dot.online {
        background: #22c55e;
        box-shadow: 0 0 0 3px rgba(34,197,94,0.2), 0 0 12px rgba(34,197,94,0.4);
        animation: pulse-green 2s infinite;
    }
    .online-dot.offline { background: #94a3b8; }
    @keyframes pulse-green {
        0%, 100% { box-shadow: 0 0 0 3px rgba(34,197,94,0.2); }
        50% { box-shadow: 0 0 0 6px rgba(34,197,94,0.1); }
    }

    .chat-header-actions {
        display: flex; align-items: center; gap: 6px;
    }
    .chat-header-actions button {
        width: 38px; height: 38px;
        border-radius: 12px;
        border: 1px solid var(--admin-border);
        background: #f8fafc;
        color: #64748b;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
        font-size: 14px;
    }
    .chat-header-actions button:hover {
        background: var(--admin-primary);
        color: white;
        border-color: var(--admin-primary);
        transform: scale(1.05);
    }

    /* ─── Messages Area ──────────────────────────────────────── */
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 24px 20px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        background: #f0f2f5;
        background-image:
            radial-gradient(circle at 20% 80%, rgba(79,70,229,0.03) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(14,165,233,0.03) 0%, transparent 50%);
        min-height: 0;
        scroll-behavior: smooth;
    }
    .chat-messages::-webkit-scrollbar { width: 5px; }
    .chat-messages::-webkit-scrollbar-track { background: transparent; }
    .chat-messages::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    .chat-messages::-webkit-scrollbar-thumb:hover { background: rgba(0,0,0,0.2); }

    /* Date Separator */
    .msg-date-separator {
        text-align: center;
        margin: 16px 0 10px;
    }
    .msg-date-separator span {
        background: rgba(255,255,255,0.9);
        backdrop-filter: blur(10px);
        padding: 6px 16px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        letter-spacing: 0.3px;
    }

    /* Message Group */
    .msg-group { display: flex; align-items: flex-end; gap: 8px; max-width: 80%; }
    .msg-group.sent { flex-direction: row-reverse; margin-left: auto; }
    .msg-group.received { margin-right: auto; }

    .msg-mini-avatar {
        width: 30px; height: 30px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .msg-mini-avatar-placeholder {
        width: 30px; height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #64748b;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800;
        flex-shrink: 0;
    }

    .msg-bubble {
        padding: 10px 14px;
        border-radius: 18px;
        font-size: 14px;
        line-height: 1.55;
        word-wrap: break-word;
        position: relative;
        transition: all 0.2s;
        animation: msgSlideIn 0.3s cubic-bezier(0.4,0,0.2,1);
    }
    @keyframes msgSlideIn {
        from { opacity: 0; transform: translateY(8px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .msg-bubble.sent {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        color: white;
        border-bottom-right-radius: 6px;
        box-shadow: 0 4px 15px rgba(79,70,229,0.2);
    }
    .msg-bubble.received {
        background: #ffffff;
        color: #1e293b;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .msg-bubble:hover { transform: scale(1.01); }

    .msg-text { font-weight: 450; }
    .msg-time {
        font-size: 10px; opacity: 0.65;
        margin-top: 4px;
        display: flex; align-items: center; gap: 4px;
    }
    .msg-bubble.sent .msg-time { justify-content: flex-end; }
    .msg-image {
        max-width: 260px; border-radius: 12px;
        margin-bottom: 4px; cursor: pointer;
        transition: all 0.2s;
    }
    .msg-image:hover { transform: scale(1.02); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }

    /* ─── Typing Indicator ───────────────────────────────────── */
    .typing-indicator {
        display: none;
        align-items: center; gap: 10px;
        padding: 4px 0;
        max-width: 80%;
        animation: msgSlideIn 0.3s ease;
    }
    .typing-indicator.active { display: flex; }
    .typing-bubble {
        background: #ffffff;
        padding: 12px 18px;
        border-radius: 18px;
        border-bottom-left-radius: 6px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        display: flex; align-items: center; gap: 4px;
    }
    .typing-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #94a3b8;
        animation: typingBounce 1.4s infinite ease-in-out;
    }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typingBounce {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
        30% { transform: translateY(-6px); opacity: 1; }
    }

    /* ─── Chat Input Area ────────────────────────────────────── */
    .chat-input-area {
        padding: 12px 16px;
        background: #ffffff;
        border-top: 1px solid var(--admin-border);
    }
    .image-preview-bar {
        display: none;
        padding: 8px 0 6px;
        align-items: center; gap: 10px;
    }
    .image-preview-bar.active { display: flex; }
    .image-preview-bar img {
        max-height: 60px; border-radius: 10px;
        border: 1px solid var(--admin-border);
    }
    .image-preview-bar .remove-preview {
        border: none; background: rgba(239,68,68,0.1);
        color: #dc2626; padding: 6px 10px; border-radius: 8px;
        cursor: pointer; font-size: 12px; font-weight: 600;
        transition: all 0.2s;
    }
    .image-preview-bar .remove-preview:hover { background: rgba(239,68,68,0.15); }

    .chat-input-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .input-actions {
        display: flex; align-items: center; gap: 2px;
    }
    .input-actions button, .input-actions label {
        width: 38px; height: 38px;
        border-radius: 12px;
        border: none;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
        font-size: 17px;
    }
    .input-actions button:hover, .input-actions label:hover {
        background: #f1f5f9;
        color: var(--admin-primary);
    }
    .chat-input-field {
        flex: 1;
        border: 1px solid var(--admin-border);
        background: #f8fafc;
        border-radius: 14px;
        padding: 11px 16px;
        font-size: 14px;
        outline: none;
        color: #1e293b;
        font-family: inherit;
        transition: all 0.2s;
    }
    .chat-input-field:focus {
        border-color: var(--admin-primary);
        box-shadow: 0 0 0 3px rgba(79,70,229,0.08);
        background: #fff;
    }
    .chat-input-field::placeholder { color: #94a3b8; }

    .send-btn {
        width: 44px; height: 44px;
        border-radius: 14px;
        border: none;
        background: linear-gradient(135deg, var(--admin-primary), #6366f1);
        color: white;
        cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px;
        transition: all 0.3s;
        box-shadow: 0 4px 12px var(--admin-primary-glow);
        flex-shrink: 0;
    }
    .send-btn:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 20px var(--admin-primary-glow);
    }
    .send-btn:active { transform: scale(0.95); }
    .send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }

    /* ─── Emoji Picker ───────────────────────────────────────── */
    .emoji-picker-container {
        position: relative;
    }
    .emoji-panel {
        display: none;
        position: absolute;
        bottom: 55px; left: -10px;
        width: 320px;
        max-height: 350px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.04);
        z-index: 100;
        flex-direction: column;
        overflow: hidden;
        animation: emojiSlide 0.25s cubic-bezier(0.4,0,0.2,1);
    }
    .emoji-panel.active { display: flex; }
    @keyframes emojiSlide {
        from { opacity: 0; transform: translateY(10px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .emoji-search {
        padding: 12px;
        border-bottom: 1px solid var(--admin-border);
    }
    .emoji-search input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        font-size: 13px;
        outline: none;
        background: #f8fafc;
        font-family: inherit;
    }
    .emoji-search input:focus {
        border-color: var(--admin-primary);
        background: #fff;
    }
    .emoji-categories {
        display: flex; gap: 2px;
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        overflow-x: auto;
    }
    .emoji-cat-btn {
        padding: 6px 10px;
        border: none;
        background: transparent;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .emoji-cat-btn:hover, .emoji-cat-btn.active {
        background: #f1f5f9;
    }
    .emoji-grid {
        flex: 1;
        overflow-y: auto;
        padding: 10px;
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 2px;
    }
    .emoji-grid::-webkit-scrollbar { width: 4px; }
    .emoji-grid::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .emoji-btn {
        width: 36px; height: 36px;
        border: none; background: transparent;
        border-radius: 8px;
        cursor: pointer;
        font-size: 20px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.15s;
    }
    .emoji-btn:hover {
        background: #f1f5f9;
        transform: scale(1.2);
    }

    @media (max-width: 600px) {
        .emoji-panel {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            width: 100%;
            max-height: 45vh;
            border-radius: 20px 20px 0 0;
        }
    }

    /* ─── Conversations Sidebar (Right) ──────────────────────── */
    .conv-sidebar {
        border-left: 1px solid var(--admin-border);
        display: flex;
        flex-direction: column;
        background: #fcfdfe;
        min-height: 0;
        height: 100%;
    }
    .conv-sidebar-header {
        padding: 18px 20px;
        border-bottom: 1px solid var(--admin-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
    }
    .conv-sidebar-header h3 {
        font-size: 16px; font-weight: 800;
        color: #0f172a; margin: 0;
        font-family: 'Outfit', sans-serif;
    }
    .conv-sidebar-header .conv-count {
        font-size: 11px; font-weight: 700;
        color: #64748b;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .conv-search {
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .conv-search input {
        width: 100%; padding: 10px 14px 10px 36px;
        border: 1px solid var(--admin-border);
        border-radius: 12px;
        font-size: 13px; outline: none;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'/%3E%3C/svg%3E") no-repeat 12px center;
        background-size: 15px;
        font-family: inherit;
    }
    .conv-search input:focus {
        border-color: var(--admin-primary);
        background-color: #fff;
    }
    .conv-list {
        flex: 1; overflow-y: auto; min-height: 0;
    }
    .conv-list::-webkit-scrollbar { width: 4px; }
    .conv-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    .conv-item {
        display: flex; align-items: center; gap: 12px;
        padding: 14px 18px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f8fafc;
        text-decoration: none;
        color: inherit;
        position: relative;
    }
    .conv-item:hover { background: #f8fafc; }
    .conv-item.active {
        background: linear-gradient(135deg, rgba(79,70,229,0.05), rgba(99,102,241,0.04));
        border-left: 3px solid var(--admin-primary);
    }
    .conv-avatar {
        width: 46px; height: 46px;
        border-radius: 14px;
        object-fit: cover;
        flex-shrink: 0;
        border: 2px solid #fff;
        box-shadow: 0 3px 10px rgba(0,0,0,0.06);
    }
    .conv-avatar-placeholder {
        width: 46px; height: 46px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--admin-primary), #7c3aed);
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 17px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px var(--admin-primary-glow);
    }
    .conv-info { flex: 1; min-width: 0; }
    .conv-name {
        font-weight: 700; font-size: 13px;
        color: #0f172a; margin-bottom: 3px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .conv-name .conv-time { font-size: 10px; color: #94a3b8; font-weight: 500; }
    .conv-last-msg {
        font-size: 12px; color: #94a3b8;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        display: flex; align-items: center; justify-content: space-between;
    }
    .conv-unread-badge {
        width: 20px; height: 20px;
        border-radius: 50%;
        background: var(--admin-primary);
        color: white;
        font-size: 10px; font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 3px 8px var(--admin-primary-glow);
        flex-shrink: 0;
        margin-left: 6px;
    }

    /* ─── Empty State ────────────────────────────────────────── */
    .empty-chat {
        flex: 1;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        color: #94a3b8; gap: 16px;
        padding: 40px;
        text-align: center;
    }
    .empty-chat-icon {
        width: 90px; height: 90px;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(79,70,229,0.08), rgba(99,102,241,0.05));
        display: flex; align-items: center; justify-content: center;
        font-size: 36px;
        color: var(--admin-primary);
        margin-bottom: 4px;
    }
    .empty-chat h3 {
        color: #0f172a; margin: 0;
        font-family: 'Outfit', sans-serif;
        font-size: 20px; font-weight: 800;
    }
    .empty-chat p { color: #64748b; font-size: 13px; margin: 0; max-width: 280px; line-height: 1.6; }
</style>
@endsection

@section('admin_content')

<div class="chat-container">
    {{-- ═══ Chat Window (Left) ═══ --}}
    @if(isset($conversation))
    @php
        $chatUser = $conversation->sender_id === auth()->id() ? $conversation->receiver : $conversation->sender;
    @endphp
    <div class="chat-window">
        <div class="chat-header">
            <a href="{{ route('admin.messages.index') }}" class="mobile-back-btn" style="width:38px;height:38px;align-items:center;justify-content:center;background:#f1f5f9;border-radius:12px;color:#1e293b;text-decoration:none;">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <button class="mobile-back-btn mobile-only" onclick="toggleChatSidebar()" style="width:38px;height:38px;align-items:center;justify-content:center;background:#f1f5f9;border-radius:12px;color:#1e293b;border:none;cursor:pointer;">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            @if($chatUser->profile_image)
                <img src="{{ display_image($chatUser->profile_image) }}" class="chat-header-avatar">
            @else
                <div class="chat-header-avatar-placeholder">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</div>
            @endif
            <div class="chat-header-info">
                <h4>{{ $chatUser->name }}</h4>
                <div class="online-status" id="onlineStatus">
                    <span class="online-dot offline" id="onlineDot"></span>
                    <span id="onlineText" style="color: #94a3b8; font-size: 12px;">Checking...</span>
                </div>
            </div>
            <div class="chat-header-actions desktop-only">
                @if($chatUser->email)
                    <span style="font-size:11px; color:var(--admin-text-sub); background:#f8fafc; padding:5px 14px; border:1px solid var(--admin-border); border-radius:20px; font-weight:600; white-space:nowrap;">{{ $chatUser->email }}</span>
                @endif
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-messages" id="chatMessages">
            @forelse($messages as $msg)
                <div class="msg-group {{ $msg->user_id === auth()->id() ? 'sent' : 'received' }}">
                    @if($msg->user_id !== auth()->id())
                        @if($msg->user->profile_image)
                            <img src="{{ display_image($msg->user->profile_image) }}" class="msg-mini-avatar">
                        @else
                            <div class="msg-mini-avatar-placeholder">{{ strtoupper(substr($msg->user->name, 0, 1)) }}</div>
                        @endif
                    @endif
                    <div id="msg-{{ $msg->id }}" class="msg-bubble {{ $msg->user_id === auth()->id() ? 'sent' : 'received' }}">
                        @if($msg->type === 'image' && $msg->file_path)
                            <div style="margin-bottom:6px;">
                                <img src="{{ display_image($msg->file_path) }}" class="msg-image" onclick="window.open(this.src)">
                            </div>
                        @endif
                        @if($msg->message)
                            <div class="msg-text">{{ $msg->message }}</div>
                        @endif
                        <div class="msg-time">
                            {{ $msg->created_at->format('h:i A') }}
                            @if($msg->user_id === auth()->id())
                                <i class="fa-solid {{ $msg->is_read ? 'fa-check-double' : 'fa-check' }}" style="font-size:10px; margin-left:3px;{{ $msg->is_read ? 'color:rgba(255,255,255,0.9);' : '' }}"></i>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-chat" style="height:100%;">
                    <div class="empty-chat-icon"><i class="fa-solid fa-comments"></i></div>
                    <h3>Start Chatting</h3>
                    <p>Send your first message to {{ $chatUser->name }}</p>
                </div>
            @endforelse

            {{-- Typing Indicator --}}
            <div class="typing-indicator" id="typingIndicator">
                @if($chatUser->profile_image)
                    <img src="{{ display_image($chatUser->profile_image) }}" class="msg-mini-avatar">
                @else
                    <div class="msg-mini-avatar-placeholder">{{ strtoupper(substr($chatUser->name, 0, 1)) }}</div>
                @endif
                <div class="typing-bubble">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="chat-input-area">
            <div class="image-preview-bar" id="imagePreview">
                <img id="previewImg" src="#" alt="Preview">
                <button class="remove-preview" onclick="clearImagePreview()">
                    <i class="fa-solid fa-xmark"></i> Remove
                </button>
            </div>
            <div class="chat-input-row">
                <div class="input-actions">
                    <div class="emoji-picker-container">
                        <button type="button" id="emojiToggle" title="Emoji"><i class="fa-regular fa-face-smile"></i></button>
                        <div class="emoji-panel" id="emojiPanel">
                            <div class="emoji-search">
                                <input type="text" id="emojiSearchInput" placeholder="Search emoji...">
                            </div>
                            <div class="emoji-categories" id="emojiCategories"></div>
                            <div class="emoji-grid" id="emojiGrid"></div>
                        </div>
                    </div>
                    <label for="imageInput" title="Attach Image"><i class="fa-solid fa-image"></i></label>
                </div>
                <form id="messageForm" enctype="multipart/form-data" style="display:contents;">
                    @csrf
                    <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                    <input type="hidden" name="receiver_id" value="{{ $chatUser->id }}">
                    <input type="file" name="image" id="imageInput" accept="image/*" style="display:none;" onchange="previewImage(this)">
                    <input type="text" name="message" id="messageInput" class="chat-input-field" placeholder="Type a message..." autocomplete="off">
                    <button type="submit" class="send-btn" id="sendBtn" title="Send">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @else
    <div class="chat-window">
        <div class="empty-chat">
            <div class="empty-chat-icon"><i class="fa-solid fa-headset"></i></div>
            <h3>Customer Support</h3>
            <p>Select a conversation from the right panel to start replying to your customers</p>
        </div>
    </div>
    @endif

    {{-- Mobile Overlay --}}
    <div class="chat-overlay" id="chatOverlay" onclick="toggleChatSidebar()"></div>

    {{-- ═══ Conversations Sidebar (Right) ═══ --}}
    <div class="conv-sidebar">
        <div class="conv-sidebar-header">
            <h3>Inbox</h3>
            <span class="conv-count">{{ $conversations->count() }} chats</span>
        </div>
        <div class="conv-search">
            <input type="text" id="convSearchInput" placeholder="Search conversations...">
        </div>
        <div class="conv-list" id="convList">
            @forelse($conversations as $conv)
                @php
                    $otherUser = $conv->sender_id === auth()->id() ? $conv->receiver : $conv->sender;
                    $lastMsg = $conv->messages->sortByDesc('created_at')->first();
                    $unread = $conv->messages->where('user_id', '!=', auth()->id())->where('is_read', false)->count();
                @endphp
                <a href="{{ route('admin.messages.chat', $conv->id) }}" class="conv-item {{ isset($conversation) && $conversation->id === $conv->id ? 'active' : '' }}" data-name="{{ strtolower($otherUser->name) }}">
                    @if($otherUser->profile_image)
                        <img src="{{ display_image($otherUser->profile_image) }}" class="conv-avatar">
                    @else
                        <div class="conv-avatar-placeholder">{{ strtoupper(substr($otherUser->name, 0, 1)) }}</div>
                    @endif
                    <div class="conv-info">
                        <div class="conv-name">
                            <span>{{ $otherUser->name }}</span>
                            @if($lastMsg)
                                <span class="conv-time">{{ $lastMsg->created_at->diffForHumans(null, true, true) }}</span>
                            @endif
                        </div>
                        <div class="conv-last-msg">
                            <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                @if($lastMsg)
                                    {{ $lastMsg->user_id === auth()->id() ? 'You: ' : '' }}{{ $lastMsg->type === 'image' ? '📷 Image' : Str::limit($lastMsg->message, 30) }}
                                @else
                                    Start a conversation
                                @endif
                            </span>
                            @if($unread > 0)
                                <span class="conv-unread-badge">{{ $unread }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="empty-chat" style="padding:30px;">
                    <div class="empty-chat-icon" style="width:60px;height:60px;font-size:24px;border-radius:16px;"><i class="fa-solid fa-inbox"></i></div>
                    <h3 style="font-size:16px;">No Conversations</h3>
                    <p>Customer inquiries will appear here</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // ═══════════════════════════════════════════════════════════
    // CHAT FUNCTIONALITY
    // ═══════════════════════════════════════════════════════════

    // ─── Mobile Sidebar Toggle ──────────────────────────────
    function toggleChatSidebar() {
        document.querySelector('.conv-sidebar').classList.toggle('mobile-open');
        document.getElementById('chatOverlay').classList.toggle('active');
    }

    // ─── Conversation Search Filter ─────────────────────────
    document.getElementById('convSearchInput')?.addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.conv-item').forEach(item => {
            const name = item.dataset.name || '';
            item.style.display = name.includes(q) ? '' : 'none';
        });
    });

    // ─── Image Preview ──────────────────────────────────────
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.add('active');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function clearImagePreview() {
        document.getElementById('imageInput').value = '';
        document.getElementById('imagePreview').classList.remove('active');
    }

    // ═══════════════════════════════════════════════════════════
    // EMOJI PICKER
    // ═══════════════════════════════════════════════════════════
    const emojiData = {
        '😊': ['Smileys', 'smile happy'],
        '😂': ['Smileys', 'laugh cry'],
        '❤️': ['Smileys', 'heart love'],
        '😍': ['Smileys', 'love eyes heart'],
        '😘': ['Smileys', 'kiss'],
        '🥰': ['Smileys', 'love hearts'],
        '😊': ['Smileys', 'blush smile'],
        '😎': ['Smileys', 'cool sunglasses'],
        '🤣': ['Smileys', 'rolling laugh'],
        '😅': ['Smileys', 'sweat smile'],
        '😭': ['Smileys', 'crying'],
        '🥺': ['Smileys', 'pleading'],
        '😤': ['Smileys', 'angry huff'],
        '🤔': ['Smileys', 'thinking'],
        '😱': ['Smileys', 'shocked scared'],
        '😴': ['Smileys', 'sleeping'],
        '🤗': ['Smileys', 'hug'],
        '🤩': ['Smileys', 'starry eyes excited'],
        '😜': ['Smileys', 'wink tongue'],
        '🙄': ['Smileys', 'eye roll'],
        '😏': ['Smileys', 'smirk'],
        '😇': ['Smileys', 'angel innocent'],
        '🤯': ['Smileys', 'mind blown'],
        '🥳': ['Smileys', 'party celebrate'],
        '👍': ['Gestures', 'thumbs up good'],
        '👎': ['Gestures', 'thumbs down bad'],
        '👏': ['Gestures', 'clap'],
        '🙏': ['Gestures', 'pray please thanks'],
        '👋': ['Gestures', 'wave hello bye'],
        '✌️': ['Gestures', 'peace victory'],
        '🤝': ['Gestures', 'handshake deal'],
        '💪': ['Gestures', 'strong muscle'],
        '☝️': ['Gestures', 'point up'],
        '👌': ['Gestures', 'ok perfect'],
        '🤞': ['Gestures', 'fingers crossed luck'],
        '✋': ['Gestures', 'stop hand'],
        '🔥': ['Objects', 'fire hot'],
        '⭐': ['Objects', 'star'],
        '💯': ['Objects', 'hundred perfect'],
        '🎉': ['Objects', 'party tada celebrate'],
        '🎁': ['Objects', 'gift present'],
        '💰': ['Objects', 'money'],
        '📦': ['Objects', 'package box'],
        '🛒': ['Objects', 'cart shopping'],
        '💳': ['Objects', 'card payment'],
        '📱': ['Objects', 'phone mobile'],
        '💻': ['Objects', 'laptop computer'],
        '📧': ['Objects', 'email'],
        '🔗': ['Objects', 'link'],
        '📸': ['Objects', 'camera photo'],
        '🎶': ['Objects', 'music notes'],
        '✅': ['Symbols', 'check done'],
        '❌': ['Symbols', 'cross wrong no'],
        '⚠️': ['Symbols', 'warning alert'],
        '❓': ['Symbols', 'question'],
        '💬': ['Symbols', 'speech chat'],
        '🔔': ['Symbols', 'bell notification'],
        '⏰': ['Symbols', 'clock time alarm'],
        '📍': ['Symbols', 'location pin'],
        '🏷️': ['Symbols', 'tag label'],
        '💡': ['Symbols', 'idea bulb light'],
        '🚚': ['Travel', 'truck delivery shipping'],
        '✈️': ['Travel', 'plane flight'],
        '🏠': ['Travel', 'house home'],
        '🌍': ['Travel', 'world globe earth'],
        '🌟': ['Travel', 'star glow'],
    };

    const categories = { '😊': 'Smileys', '👍': 'Gestures', '🔥': 'Objects', '✅': 'Symbols', '🚚': 'Travel' };
    let currentCategory = null;

    function initEmojiPicker() {
        const toggle = document.getElementById('emojiToggle');
        const panel = document.getElementById('emojiPanel');
        const grid = document.getElementById('emojiGrid');
        const catContainer = document.getElementById('emojiCategories');
        const searchInput = document.getElementById('emojiSearchInput');
        if (!toggle || !panel) return;

        // Build category buttons
        Object.entries(categories).forEach(([emoji, name]) => {
            const btn = document.createElement('button');
            btn.className = 'emoji-cat-btn';
            btn.textContent = emoji;
            btn.title = name;
            btn.onclick = () => filterByCategory(name, btn);
            catContainer.appendChild(btn);
        });
        // All button
        const allBtn = document.createElement('button');
        allBtn.className = 'emoji-cat-btn active';
        allBtn.textContent = '🔤';
        allBtn.title = 'All';
        allBtn.onclick = () => filterByCategory(null, allBtn);
        catContainer.prepend(allBtn);

        renderEmojis();

        toggle.onclick = e => {
            e.stopPropagation();
            panel.classList.toggle('active');
        };

        searchInput.addEventListener('input', () => renderEmojis());

        document.addEventListener('click', e => {
            if (!panel.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)) {
                panel.classList.remove('active');
            }
        });
    }

    function filterByCategory(cat, btn) {
        currentCategory = cat;
        document.querySelectorAll('.emoji-cat-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderEmojis();
    }

    function renderEmojis() {
        const grid = document.getElementById('emojiGrid');
        const search = (document.getElementById('emojiSearchInput')?.value || '').toLowerCase();
        grid.innerHTML = '';

        Object.entries(emojiData).forEach(([emoji, [cat, keywords]]) => {
            if (currentCategory && cat !== currentCategory) return;
            if (search && !keywords.includes(search) && !emoji.includes(search)) return;

            const btn = document.createElement('button');
            btn.className = 'emoji-btn';
            btn.textContent = emoji;
            btn.onclick = () => {
                const input = document.getElementById('messageInput');
                if (input) {
                    const start = input.selectionStart;
                    const end = input.selectionEnd;
                    input.value = input.value.substring(0, start) + emoji + input.value.substring(end);
                    input.focus();
                    input.setSelectionRange(start + emoji.length, start + emoji.length);
                }
            };
            grid.appendChild(btn);
        });
    }

    // ═══════════════════════════════════════════════════════════
    // CHAT LOGIC (only when conversation is open)
    // ═══════════════════════════════════════════════════════════
    @if(isset($conversation))
    const chatBox = document.getElementById('chatMessages');
    chatBox.scrollTop = chatBox.scrollHeight;

    let lastMsgId = {{ $messages->last() ? $messages->last()->id : 0 }};
    let typingTimeout = null;

    // ─── Typing Indicator: send typing event ────────────────
    document.getElementById('messageInput')?.addEventListener('input', function() {
        clearTimeout(typingTimeout);
        fetch('{{ route("admin.messages.typing") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ conversation_id: {{ $conversation->id }} })
        }).catch(() => {});
        typingTimeout = setTimeout(() => {}, 3000);
    });

    // ─── Poll for messages + typing status ──────────────────
    setInterval(() => {
        // Poll messages
        fetch(`{{ route('admin.messages.poll', $conversation->id) }}?last_id=${lastMsgId}`)
        .then(r => r.json())
        .then(data => {
            if (data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    if (document.getElementById(`msg-${msg.id}`)) return;

                    const isSent = msg.user_id === {{ auth()->id() }};
                    const initial = msg.user.name.charAt(0).toUpperCase();
                    const avatar = msg.user.profile_image
                        ? `<img src="${msg.user.profile_image.includes('http') ? msg.user.profile_image : '/storage/' + msg.user.profile_image}" class="msg-mini-avatar">`
                        : `<div class="msg-mini-avatar-placeholder">${initial}</div>`;

                    let content = '';
                    if (msg.type === 'image' && msg.file_path) {
                        const imgUrl = msg.file_path.includes('http') ? msg.file_path : '/storage/' + msg.file_path;
                        content += `<div style="margin-bottom:6px;"><img src="${imgUrl}" class="msg-image" onclick="window.open(this.src)"></div>`;
                    }
                    if (msg.message) {
                        content += `<div class="msg-text">${msg.message}</div>`;
                    }

                    const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    const readIcon = isSent ? `<i class="fa-solid ${msg.is_read ? 'fa-check-double' : 'fa-check'}" style="font-size:10px;margin-left:3px;"></i>` : '';

                    const html = `
                        <div class="msg-group ${isSent ? 'sent' : 'received'}">
                            ${!isSent ? avatar : ''}
                            <div id="msg-${msg.id}" class="msg-bubble ${isSent ? 'sent' : 'received'}">
                                ${content}
                                <div class="msg-time">${time} ${readIcon}</div>
                            </div>
                        </div>
                    `;

                    // Insert before typing indicator
                    const typingEl = document.getElementById('typingIndicator');
                    typingEl.insertAdjacentHTML('beforebegin', html);
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
                text.textContent = data.lastSeen !== 'Never' ? 'Last seen ' + data.lastSeen : 'Offline';
                text.style.color = '#94a3b8';
            }
        });

        // Poll typing status
        fetch(`{{ route('admin.messages.typing.status', $conversation->id) }}`)
        .then(r => r.json())
        .then(data => {
            const indicator = document.getElementById('typingIndicator');
            if (data.isTyping) {
                indicator.classList.add('active');
                chatBox.scrollTop = chatBox.scrollHeight;
            } else {
                indicator.classList.remove('active');
            }
        }).catch(() => {});
    }, 2500);

    // ─── Send Message ───────────────────────────────────────
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const msgInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');

        if (!msgInput.value.trim() && !document.getElementById('imageInput').files.length) return;

        const originalMsg = msgInput.value;
        msgInput.value = '';
        document.getElementById('imageInput').value = '';
        clearImagePreview();

        sendBtn.disabled = true;

        fetch('{{ route("admin.messages.send") }}', {
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
                alert('Error: ' + (data.message || 'Could not send'));
            }
        })
        .catch(err => {
            console.error(err);
            msgInput.value = originalMsg;
        })
        .finally(() => {
            sendBtn.disabled = false;
        });
    });
    @endif

    // ─── Init ───────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        initEmojiPicker();
    });
</script>
@endsection
