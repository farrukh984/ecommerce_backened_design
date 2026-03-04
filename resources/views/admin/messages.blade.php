@extends('layouts.admin')

@section('title', 'Messages')
@section('header_title', 'Customer Messages')

@section('styles')
<style>
/* ══════════════════════════════════════════════════════════
   ADMIN CHAT — Dashboard Blue Theme + Dark/Light Mode
   ══════════════════════════════════════════════════════════ */
.admin-topbar { margin-bottom:0!important; }
.admin-main   { padding:0!important; overflow:hidden!important; height:calc(100vh - 56px)!important; }

:root {
    --c-bg:        #f0f4ff;
    --c-sidebar:   #ffffff;
    --c-hover:     #f1f5f9;
    --c-active:    #eff6ff;
    --c-active-bar:#0ea5e9;
    --c-conv-list: #f8fafc;
    --c-border:    #e2e8f0;
    --c-chat-bg:   #f0f4ff;
    --c-bubble-me: linear-gradient(135deg,#4f46e5,#0ea5e9);
    --c-bubble-them:#ffffff;
    --c-bubble-them-txt:#1e293b;
    --c-input-bg:  #f8fafc;
    --c-input-bdr: #e2e8f0;
    --c-txt:       #0f172a;
    --c-txt2:      #64748b;
    --c-txt3:      #94a3b8;
    --c-send:      #0ea5e9;
    --c-unread:    #0ea5e9;
    --c-time:      rgba(255,255,255,0.7);
}
[data-theme="dark"] {
    --c-bg:        #0f172a;
    --c-sidebar:   #1e293b;
    --c-hover:     #263348;
    --c-active:    #1d3557;
    --c-active-bar:#0ea5e9;
    --c-conv-list: #1e293b;
    --c-border:    #334155;
    --c-chat-bg:   #0f172a;
    --c-bubble-me: linear-gradient(135deg,#4f46e5,#0ea5e9);
    --c-bubble-them:#1e293b;
    --c-bubble-them-txt:#e2e8f0;
    --c-input-bg:  #1e293b;
    --c-input-bdr: #334155;
    --c-txt:       #f1f5f9;
    --c-txt2:      #94a3b8;
    --c-txt3:      #64748b;
    --c-send:      #0ea5e9;
    --c-unread:    #0ea5e9;
    --c-time:      rgba(255,255,255,0.55);
}

.chat-wrap {
    display:grid;
    grid-template-columns:320px 1fr;
    width:100%; height:100%;
    background:var(--c-bg);
    overflow:hidden; transition:background 0.3s;
}

/* ══ LEFT SIDEBAR ══════════════════════════════════════════ */
.chat-sidebar {
    display:flex; flex-direction:column;
    background:var(--c-sidebar);
    border-right:1px solid var(--c-border);
    height:100%; overflow:hidden;
    transition:background 0.3s, border-color 0.3s;
}
.sidebar-head {
    background:linear-gradient(135deg,#4f46e5,#0ea5e9);
    padding:14px 16px;
    display:flex; align-items:center;
    justify-content:space-between;
    min-height:60px; flex-shrink:0;
}
.sidebar-head h3 { margin:0; font-size:18px; font-weight:700; color:#fff; }
.sidebar-head-right { display:flex; gap:6px; align-items:center; }

.s-btn {
    width:34px; height:34px; border:none;
    background:rgba(255,255,255,0.15); color:#fff;
    border-radius:50%; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; transition:background 0.2s; flex-shrink:0;
}
.s-btn:hover { background:rgba(255,255,255,0.25); }

.chat-search { padding:10px 14px; background:var(--c-conv-list); flex-shrink:0; }
.search-inner {
    display:flex; align-items:center;
    background:var(--c-input-bg);
    border:1px solid var(--c-input-bdr);
    border-radius:10px; padding:0 12px; gap:8px;
    transition:border-color 0.2s;
}
.search-inner:focus-within { border-color:var(--c-send); }
.search-inner i { color:var(--c-txt3); font-size:13px; }
.search-inner input {
    flex:1; border:none; background:transparent;
    padding:9px 0; font-size:13px; color:var(--c-txt);
    outline:none; font-family:inherit;
}
.search-inner input::placeholder { color:var(--c-txt3); }

.conv-list { flex:1; overflow-y:auto; min-height:0; background:var(--c-conv-list); }
.conv-list::-webkit-scrollbar { width:5px; }
.conv-list::-webkit-scrollbar-thumb { background:var(--c-border); border-radius:10px; }

.conv-item {
    display:flex; align-items:center; gap:12px;
    padding:12px 16px; cursor:pointer;
    text-decoration:none; color:inherit;
    border-bottom:1px solid var(--c-border);
    transition:background 0.15s; position:relative;
}
.conv-item:hover { background:var(--c-hover); }
.conv-item.active { background:var(--c-active); }
.conv-item.active::before {
    content:''; position:absolute; left:0; top:0; bottom:0;
    width:3px; background:var(--c-active-bar); border-radius:0 3px 3px 0;
}
.c-avatar { width:46px; height:46px; border-radius:50%; object-fit:cover; flex-shrink:0; border:2px solid var(--c-border); }
.c-avatar-ph {
    width:46px; height:46px; border-radius:50%;
    background:linear-gradient(135deg,#4f46e5,#0ea5e9);
    color:#fff; display:flex; align-items:center;
    justify-content:center; font-weight:700; font-size:17px; flex-shrink:0;
}
.c-info { flex:1; min-width:0; }
.c-top { display:flex; align-items:center; justify-content:space-between; margin-bottom:3px; }
.c-name {
    font-size:14px; font-weight:700; color:var(--c-txt);
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.c-time { font-size:11px; color:var(--c-txt3); flex-shrink:0; margin-left:6px; }
.conv-item.has-unread .c-time { color:var(--c-send); }
.c-bot { display:flex; align-items:center; justify-content:space-between; }
.c-last { font-size:12px; color:var(--c-txt2); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; flex:1; }
.c-badge {
    min-width:19px; height:19px; border-radius:50%;
    background:var(--c-unread); color:#fff;
    font-size:10px; font-weight:700;
    display:flex; align-items:center; justify-content:center;
    padding:0 5px; margin-left:8px; flex-shrink:0;
}

/* ══ RIGHT — CHAT WINDOW ══════════════════════════════════ */
.chat-window {
    display:flex; flex-direction:column;
    background:var(--c-chat-bg);
    height:100%; min-height:0; overflow:hidden;
    transition:background 0.3s;
}
.chat-head {
    display:flex; align-items:center; gap:12px;
    padding:12px 16px; flex-shrink:0;
    background:linear-gradient(135deg,#4f46e5,#0ea5e9);
    min-height:60px;
}
.ch-avatar { width:38px; height:38px; border-radius:50%; object-fit:cover; flex-shrink:0; border:2px solid rgba(255,255,255,0.3); }
.ch-avatar-ph {
    width:38px; height:38px; border-radius:50%;
    background:rgba(255,255,255,0.2); color:#fff;
    display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:14px; flex-shrink:0;
}
.ch-info { flex:1; min-width:0; }
.ch-info h4 {
    margin:0; font-size:15px; font-weight:700; color:#fff;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.ch-status { font-size:12px; color:rgba(255,255,255,0.75); display:flex; align-items:center; gap:5px; margin-top:2px; }
.on-dot { width:7px; height:7px; border-radius:50%; display:inline-block; }
.on-dot.on  { background:#4ade80; box-shadow:0 0 6px rgba(74,222,128,0.6); }
.on-dot.off { background:rgba(255,255,255,0.4); }
.ch-back {
    width:36px; height:36px; border:none;
    background:rgba(255,255,255,0.15); color:#fff;
    border-radius:50%; cursor:pointer;
    display:none; align-items:center; justify-content:center;
    font-size:15px; transition:background 0.2s; flex-shrink:0;
}
.ch-back:hover { background:rgba(255,255,255,0.25); }
.ch-actions { display:flex; gap:4px; }
.ch-action-btn {
    width:36px; height:36px; border:none;
    background:rgba(255,255,255,0.12); color:#fff;
    border-radius:50%; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:14px; transition:background 0.2s; flex-shrink:0;
}
.ch-action-btn:hover { background:rgba(255,255,255,0.25); }

.msgs-area {
    flex:1; overflow-y:auto;
    padding:20px 40px;
    display:flex; flex-direction:column; gap:4px;
    min-height:0; scroll-behavior:smooth;
    background:var(--c-chat-bg);
    background-image:radial-gradient(circle at 20% 80%,rgba(79,70,229,0.04) 0%,transparent 50%),
                     radial-gradient(circle at 80% 20%,rgba(14,165,233,0.04) 0%,transparent 50%);
    transition:background 0.3s;
}
.msgs-area::-webkit-scrollbar { width:5px; }
.msgs-area::-webkit-scrollbar-thumb { background:var(--c-border); border-radius:10px; }

.msg-row { display:flex; }
.msg-row.sent     { justify-content:flex-end; }
.msg-row.received { justify-content:flex-start; }
.bubble {
    max-width:65%; padding:9px 12px 5px;
    border-radius:16px; font-size:14px;
    line-height:1.55; word-wrap:break-word;
    position:relative; animation:bubIn 0.2s ease;
}
@keyframes bubIn{from{opacity:0;transform:scale(0.94) translateY(6px);}to{opacity:1;transform:scale(1) translateY(0);}}
.bubble.sent {
    background:var(--c-bubble-me); color:#fff;
    border-bottom-right-radius:4px;
    box-shadow:0 3px 12px rgba(14,165,233,0.25);
}
.bubble.received {
    background:var(--c-bubble-them); color:var(--c-bubble-them-txt);
    border-bottom-left-radius:4px;
    box-shadow:0 2px 8px rgba(0,0,0,0.06);
    border:1px solid var(--c-border);
}
.b-text { display:block; margin-right:54px; }
.b-img  { max-width:260px; border-radius:10px; margin-bottom:5px; cursor:pointer; display:block; }
.b-meta {
    float:right; margin-top:3px; margin-left:10px;
    display:flex; align-items:center; gap:3px;
    font-size:10px; color:var(--c-time); white-space:nowrap;
}
.bubble.received .b-meta { color:var(--c-txt3); }
.b-meta .read { color:#60d0ff; }

.typing-row { display:none; justify-content:flex-start; }
.typing-row.on { display:flex; }
.typing-bub {
    background:var(--c-bubble-them); border:1px solid var(--c-border);
    padding:10px 16px; border-radius:16px; border-bottom-left-radius:4px;
    display:flex; align-items:center; gap:4px;
    box-shadow:0 2px 8px rgba(0,0,0,0.06);
}
.t-dot { width:7px; height:7px; border-radius:50%; background:var(--c-txt3); animation:tB 1.4s infinite ease-in-out; }
.t-dot:nth-child(2){animation-delay:0.2s;} .t-dot:nth-child(3){animation-delay:0.4s;}
@keyframes tB{0%,60%,100%{transform:translateY(0);opacity:0.4;}30%{transform:translateY(-7px);opacity:1;}}

/* Input */
.img-preview-bar { display:none; align-items:center; gap:10px; padding:6px 16px; background:var(--c-input-bg); border-top:1px solid var(--c-border); flex-shrink:0; }
.img-preview-bar.on { display:flex; }
.img-preview-bar img { max-height:55px; border-radius:8px; border:1px solid var(--c-border); }
.img-remove { border:none; background:rgba(239,68,68,0.1); color:#ef4444; padding:4px 10px; border-radius:8px; cursor:pointer; font-size:12px; }

.chat-input-bar {
    display:flex; align-items:center; gap:8px;
    padding:10px 14px; flex-shrink:0;
    background:var(--c-sidebar);
    border-top:1px solid var(--c-border);
    transition:background 0.3s;
}
.ci-btn {
    width:38px; height:38px; border:none;
    background:transparent; color:var(--c-txt3);
    border-radius:50%; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    font-size:18px; transition:all 0.2s; flex-shrink:0;
}
.ci-btn:hover { background:var(--c-hover); color:var(--c-send); }
label.ci-btn { cursor:pointer; }
.chat-input-field {
    flex:1; min-width:0; border:1px solid var(--c-input-bdr);
    background:var(--c-input-bg); border-radius:24px;
    padding:10px 18px; font-size:14px; color:var(--c-txt);
    outline:none; font-family:inherit; transition:all 0.2s;
}
.chat-input-field:focus { border-color:var(--c-send); box-shadow:0 0 0 3px rgba(14,165,233,0.1); }
.chat-input-field::placeholder { color:var(--c-txt3); }
.send-btn {
    width:42px; height:42px; border:none; border-radius:50%;
    background:linear-gradient(135deg,#4f46e5,#0ea5e9); color:#fff;
    cursor:pointer; display:flex; align-items:center; justify-content:center;
    font-size:16px; transition:all 0.2s; flex-shrink:0;
    box-shadow:0 4px 12px rgba(14,165,233,0.3);
}
.send-btn:hover { transform:scale(1.08); box-shadow:0 6px 18px rgba(14,165,233,0.4); }
.send-btn:active { transform:scale(0.95); }
.send-btn:disabled { opacity:0.5; cursor:not-allowed; transform:none!important; }

/* Emoji */
.emoji-wrap { position:relative; flex-shrink:0; }
.emoji-panel {
    display:none; position:absolute; bottom:54px; left:0;
    width:320px; max-height:340px;
    background:var(--c-sidebar);
    border:1px solid var(--c-border);
    border-radius:16px; box-shadow:0 10px 40px rgba(0,0,0,0.15);
    z-index:999; flex-direction:column; overflow:hidden;
    animation:epUp 0.2s ease;
}
.emoji-panel.open { display:flex; }
@keyframes epUp{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}
.ep-search { padding:10px; border-bottom:1px solid var(--c-border); }
.ep-search input {
    width:100%; padding:8px 12px;
    background:var(--c-input-bg); border:1px solid var(--c-input-bdr);
    border-radius:8px; font-size:13px; color:var(--c-txt);
    outline:none; font-family:inherit;
}
.ep-cats { display:flex; gap:2px; padding:6px 10px; border-bottom:1px solid var(--c-border); overflow-x:auto; }
.ep-cat { padding:4px 8px; border:none; background:transparent; border-radius:6px; cursor:pointer; font-size:15px; transition:background 0.15s; flex-shrink:0; }
.ep-cat:hover,.ep-cat.on { background:var(--c-hover); }
.ep-grid { flex:1; overflow-y:auto; padding:8px; display:grid; grid-template-columns:repeat(8,1fr); gap:1px; }
.ep-grid::-webkit-scrollbar { width:4px; }
.ep-grid::-webkit-scrollbar-thumb { background:var(--c-border); border-radius:10px; }
.ep-emoji { width:36px; height:36px; border:none; background:transparent; border-radius:6px; cursor:pointer; font-size:20px; display:flex; align-items:center; justify-content:center; transition:all 0.1s; }
.ep-emoji:hover { background:var(--c-hover); transform:scale(1.2); }
@media(max-width:600px){ .emoji-panel{ position:fixed; bottom:0; left:0; right:0; width:100%; max-height:45vh; border-radius:16px 16px 0 0; } }

/* Empty */
.chat-empty { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; padding:40px; gap:14px; }
.empty-icon { width:80px; height:80px; border-radius:20px; background:linear-gradient(135deg,rgba(79,70,229,0.1),rgba(14,165,233,0.08)); display:flex; align-items:center; justify-content:center; font-size:32px; color:var(--c-send); }
.chat-empty h3 { margin:0; font-size:22px; font-weight:800; color:var(--c-txt); }
.chat-empty p  { margin:0; font-size:13px; color:var(--c-txt2); max-width:300px; line-height:1.6; }

/* Mobile */
@media(max-width:991px){
    .chat-wrap { grid-template-columns:1fr; position:relative; }

    @if(isset($conversation))
    .chat-sidebar {
        position:fixed; left:0; top:0; bottom:0;
        width:300px; z-index:1060;
        transform:translateX(-100%);
        transition:transform 0.3s ease;
        box-shadow:5px 0 30px rgba(0,0,0,0.15);
    }
    .chat-sidebar.mob-open { transform:translateX(0); }
    .chat-window { display:flex; }
    @else
    .chat-window { display:none; }
    .chat-sidebar { position:relative; height:100%; transform:none; }
    @endif

    .mob-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.45); z-index:1059; display:none; }
    .mob-overlay.on { display:block; }
    .ch-back { display:flex!important; }
    .desk-only { display:none!important; }
    .msgs-area { padding:14px 12px; }
    .bubble { max-width:82%; }
}
@media(min-width:992px){
    .ch-back { display:none!important; }
    .mob-overlay { display:none!important; }
}
</style>
@endsection

@section('admin_content')

<div class="chat-wrap">

    {{-- ████ LEFT — CONVERSATIONS ████ --}}
    <div class="chat-sidebar" id="chatSidebar">
        <div class="sidebar-head">
            <h3>Chats</h3>
            <div class="sidebar-head-right">
                <button class="s-btn" id="dmToggle" title="Toggle dark mode">
                    <i class="fa-solid fa-moon" id="dmIcon"></i>
                </button>
                <button class="s-btn" onclick="closeSidebar()" id="sidebarCloseBtn" style="display:none;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <div class="chat-search">
            <div class="search-inner">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="convSearch" placeholder="Search customers...">
            </div>
        </div>

        <div class="conv-list" id="convList">
            @forelse($conversations as $conv)
                @php
                    $other  = $conv->sender_id === auth()->id() ? $conv->receiver : $conv->sender;
                    $last   = $conv->messages->sortByDesc('created_at')->first();
                    $unread = $conv->messages->where('user_id','!=',auth()->id())->where('is_read',false)->count();
                @endphp
                <a href="{{ route('admin.messages.chat', $conv->id) }}"
                   class="conv-item {{ isset($conversation) && $conversation->id===$conv->id ? 'active' : '' }} {{ $unread>0?'has-unread':'' }}"
                   data-name="{{ strtolower($other->name) }}">
                    @if($other->profile_image)
                        <img src="{{ display_image($other->profile_image) }}" class="c-avatar">
                    @else
                        <div class="c-avatar-ph">{{ strtoupper(substr($other->name,0,1)) }}</div>
                    @endif
                    <div class="c-info">
                        <div class="c-top">
                            <span class="c-name">{{ $other->name }}</span>
                            @if($last)<span class="c-time">{{ $last->created_at->format('g:i a') }}</span>@endif
                        </div>
                        <div class="c-bot">
                            <span class="c-last">
                                @if($last)
                                    @if($last->user_id===auth()->id())
                                        <i class="fa-solid fa-check-double" style="font-size:11px;color:{{ $last->is_read?'#0ea5e9':'var(--c-txt3)' }};margin-right:2px;"></i>
                                    @endif
                                    {{ $last->type==='image' ? '📷 Photo' : Str::limit($last->message,35) }}
                                @else No messages yet @endif
                            </span>
                            @if($unread>0)<span class="c-badge">{{ $unread }}</span>@endif
                        </div>
                    </div>
                </a>
            @empty
                <div style="padding:40px 20px;text-align:center;color:var(--c-txt3);">
                    <i class="fa-solid fa-inbox" style="font-size:32px;opacity:0.25;display:block;margin-bottom:10px;"></i>
                    <p style="font-size:13px;margin:0;">No conversations yet</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Mobile overlay --}}
    <div class="mob-overlay" id="mobOverlay" onclick="closeSidebar()"></div>

    {{-- ████ RIGHT — CHAT WINDOW ████ --}}
    @if(isset($conversation))
        @php $cu = $conversation->sender_id===auth()->id() ? $conversation->receiver : $conversation->sender; @endphp
        <div class="chat-window">
            <div class="chat-head">
                <button class="ch-back" id="backBtn" onclick="openSidebar()">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                @if($cu->profile_image)
                    <img src="{{ display_image($cu->profile_image) }}" class="ch-avatar">
                @else
                    <div class="ch-avatar-ph">{{ strtoupper(substr($cu->name,0,1)) }}</div>
                @endif
                <div class="ch-info">
                    <h4>{{ $cu->name }}</h4>
                    <div class="ch-status">
                        <span class="on-dot off" id="onDot"></span>
                        <span id="onText">checking...</span>
                    </div>
                </div>
                <div class="ch-actions desk-only">
                    <button class="ch-action-btn" onclick="toggleDark()" title="Toggle theme">
                        <i class="fa-solid fa-moon" id="dmIcon2"></i>
                    </button>
                    <button class="ch-action-btn" title="More"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                </div>
            </div>

            <div class="msgs-area" id="msgsArea">
                @foreach($messages as $msg)
                    <div class="msg-row {{ $msg->user_id===auth()->id()?'sent':'received' }}">
                        <div id="msg-{{ $msg->id }}" class="bubble {{ $msg->user_id===auth()->id()?'sent':'received' }}">
                            @if($msg->type==='image'&&$msg->file_path)
                                <img src="{{ display_image($msg->file_path) }}" class="b-img" onclick="window.open(this.src)">
                            @endif
                            @if($msg->message)<span class="b-text">{{ $msg->message }}</span>@endif
                            <span class="b-meta">
                                {{ $msg->created_at->format('g:i a') }}
                                @if($msg->user_id===auth()->id())
                                    <i class="fa-solid fa-check-double {{ $msg->is_read?'read':'' }}"></i>
                                @endif
                            </span>
                        </div>
                    </div>
                @endforeach
                <div class="typing-row" id="typingRow">
                    <div class="typing-bub">
                        <div class="t-dot"></div><div class="t-dot"></div><div class="t-dot"></div>
                    </div>
                </div>
            </div>

            <div class="img-preview-bar" id="imgPreview">
                <img id="previewImg" src="#" alt="">
                <button type="button" class="img-remove" onclick="clearImg()">
                    <i class="fa-solid fa-xmark"></i> Remove
                </button>
            </div>

            <form id="msgForm" enctype="multipart/form-data" style="margin:0;flex-shrink:0;">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <input type="hidden" name="receiver_id"     value="{{ $cu->id }}">
                <input type="file"   name="image" id="imgFile" accept="image/*" style="display:none;" onchange="previewImg(this)">
                <div class="chat-input-bar">
                    <div class="emoji-wrap">
                        <button type="button" class="ci-btn" id="emojiBtn" title="Emoji">
                            <i class="fa-regular fa-face-smile"></i>
                        </button>
                        <div class="emoji-panel" id="emojiPanel">
                            <div class="ep-search"><input type="text" id="emojiSearch" placeholder="Search emoji..."></div>
                            <div class="ep-cats" id="emojiCats"></div>
                            <div class="ep-grid" id="emojiGrid"></div>
                        </div>
                    </div>
                    <label for="imgFile" class="ci-btn" title="Attach photo">
                        <i class="fa-solid fa-paperclip"></i>
                    </label>
                    <input type="text" name="message" id="msgInput"
                           class="chat-input-field" placeholder="Type a message..." autocomplete="off">
                    <button type="submit" class="send-btn" id="sendBtn">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>

    @else
        <div class="chat-window">
            <div class="chat-head" style="justify-content:flex-end;">
                <button class="ch-action-btn" onclick="toggleDark()" title="Toggle theme">
                    <i class="fa-solid fa-moon" id="dmIcon2"></i>
                </button>
            </div>
            <div class="chat-empty">
                <div class="empty-icon"><i class="fa-solid fa-comments"></i></div>
                <h3>Customer Messages</h3>
                <p>Select a conversation from the left to start chatting with your customers</p>
            </div>
        </div>
    @endif

</div>

@endsection

@section('scripts')
<script>
// ── Dark / Light mode ──────────────────────────────────────
function setTheme(dark) {
    document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
    localStorage.setItem('adminChatTheme', dark ? 'dark' : 'light');
    const icons = [document.getElementById('dmIcon'), document.getElementById('dmIcon2')];
    icons.forEach(i => { if(i) i.className = dark ? 'fa-solid fa-sun' : 'fa-solid fa-moon'; });
}
function toggleDark() {
    setTheme(document.documentElement.getAttribute('data-theme') !== 'dark');
}
setTheme(localStorage.getItem('adminChatTheme') === 'dark');
document.getElementById('dmToggle')?.addEventListener('click', toggleDark);

// ── Mobile sidebar ─────────────────────────────────────────
function openSidebar() {
    document.getElementById('chatSidebar').classList.add('mob-open');
    document.getElementById('mobOverlay').classList.add('on');
    const cb = document.getElementById('sidebarCloseBtn');
    if(cb) cb.style.display = 'flex';
}
function closeSidebar() {
    document.getElementById('chatSidebar').classList.remove('mob-open');
    document.getElementById('mobOverlay').classList.remove('on');
    const cb = document.getElementById('sidebarCloseBtn');
    if(cb) cb.style.display = 'none';
}

// ── Conv search ────────────────────────────────────────────
document.getElementById('convSearch')?.addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.conv-item').forEach(el => {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
});

// ── Image preview ──────────────────────────────────────────
function previewImg(input) {
    if(input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => { document.getElementById('previewImg').src=e.target.result; document.getElementById('imgPreview').classList.add('on'); };
        r.readAsDataURL(input.files[0]);
    }
}
function clearImg() {
    document.getElementById('imgFile').value='';
    document.getElementById('imgPreview').classList.remove('on');
}

// ── Emoji Picker ───────────────────────────────────────────
const EMOJIS = {
    '😊':['S','smile'],'😂':['S','laugh'],'❤️':['S','heart'],'😍':['S','love'],
    '😘':['S','kiss'],'🥰':['S','love'],'😎':['S','cool'],'🤣':['S','laugh'],
    '😅':['S','sweat'],'😭':['S','cry'],'🥺':['S','plead'],'😤':['S','angry'],
    '🤔':['S','think'],'😱':['S','shock'],'😴':['S','sleep'],'🤗':['S','hug'],
    '🤩':['S','star'],'😜':['S','wink'],'🙄':['S','roll'],'😏':['S','smirk'],
    '😇':['S','angel'],'🤯':['S','mind'],'🥳':['S','party'],
    '👍':['G','thumbs up'],'👎':['G','thumbs down'],'👏':['G','clap'],
    '🙏':['G','pray'],'👋':['G','wave'],'✌️':['G','peace'],
    '🤝':['G','handshake'],'💪':['G','strong'],'👌':['G','ok'],'🤞':['G','luck'],'✋':['G','stop'],
    '🔥':['O','fire'],'⭐':['O','star'],'💯':['O','hundred'],'🎉':['O','party'],
    '🎁':['O','gift'],'💰':['O','money'],'📦':['O','package'],'🛒':['O','cart'],
    '💳':['O','card'],'📱':['O','phone'],'💻':['O','laptop'],'📧':['O','email'],
    '✅':['Y','check'],'❌':['Y','cross'],'⚠️':['Y','warning'],'❓':['Y','question'],
    '💬':['Y','chat'],'🔔':['Y','bell'],'📍':['Y','location'],'💡':['Y','idea'],
    '🚚':['T','truck'],'✈️':['T','plane'],'🏠':['T','home'],'🌍':['T','world'],
};
const CATS = {'S':['😊','Smileys'],'G':['👍','Gestures'],'O':['🔥','Objects'],'Y':['✅','Symbols'],'T':['🚚','Travel']};
let curCat = null;

(function initEmoji(){
    const btn=document.getElementById('emojiBtn');
    const panel=document.getElementById('emojiPanel');
    const cats=document.getElementById('emojiCats');
    const grid=document.getElementById('emojiGrid');
    const srch=document.getElementById('emojiSearch');
    if(!btn||!panel) return;

    const allB=mkCat('🔤','All',null); allB.classList.add('on'); cats.appendChild(allB);
    Object.entries(CATS).forEach(([k,[e,n]])=>cats.appendChild(mkCat(e,n,k)));
    draw();

    btn.addEventListener('click',e=>{e.stopPropagation();panel.classList.toggle('open');});
    srch.addEventListener('input',draw);
    document.addEventListener('click',e=>{
        if(!panel.contains(e.target)&&e.target!==btn&&!btn.contains(e.target))
            panel.classList.remove('open');
    });

    function mkCat(emoji,title,cat){
        const b=document.createElement('button');
        b.type='button'; b.className='ep-cat'; b.textContent=emoji; b.title=title;
        b.onclick=()=>{curCat=cat;document.querySelectorAll('.ep-cat').forEach(x=>x.classList.remove('on'));b.classList.add('on');draw();};
        return b;
    }
    function draw(){
        grid.innerHTML='';
        const s=(srch.value||'').toLowerCase();
        Object.entries(EMOJIS).forEach(([e,[c,k]])=>{
            if(curCat&&c!==curCat) return;
            if(s&&!k.includes(s)) return;
            const b=document.createElement('button');
            b.type='button'; b.className='ep-emoji'; b.textContent=e;
            b.onclick=()=>{
                const inp=document.getElementById('msgInput');
                const st=inp.selectionStart,en=inp.selectionEnd;
                inp.value=inp.value.slice(0,st)+e+inp.value.slice(en);
                inp.focus(); inp.setSelectionRange(st+e.length,st+e.length);
            };
            grid.appendChild(b);
        });
    }
})();

// ── Chat ───────────────────────────────────────────────────
@if(isset($conversation))
const msgsArea=document.getElementById('msgsArea');
msgsArea.scrollTop=msgsArea.scrollHeight;
let lastId={{ $messages->last() ? $messages->last()->id : 0 }};
let typingTimer=null;

document.getElementById('msgInput')?.addEventListener('input',()=>{
    clearTimeout(typingTimer);
    fetch('{{ route("admin.messages.typing") }}',{
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json','Accept':'application/json'},
        body:JSON.stringify({conversation_id:{{ $conversation->id }}})
    }).catch(()=>{});
    typingTimer=setTimeout(()=>{},3000);
});

setInterval(()=>{
    fetch(`{{ route('admin.messages.poll',$conversation->id) }}?last_id=${lastId}`)
    .then(r=>r.json()).then(data=>{
        (data.messages||[]).forEach(msg=>{
            if(document.getElementById('msg-'+msg.id)) return;
            const sent=msg.user_id==={{ auth()->id() }};
            let c='';
            if(msg.type==='image'&&msg.file_path){ const u=msg.file_path.includes('http')?msg.file_path:'/storage/'+msg.file_path; c+=`<img src="${u}" class="b-img" onclick="window.open(this.src)">`; }
            if(msg.message) c+=`<span class="b-text">${msg.message}</span>`;
            const t=new Date(msg.created_at).toLocaleTimeString([],{hour:'numeric',minute:'2-digit',hour12:true});
            const ri=sent?`<i class="fa-solid fa-check-double ${msg.is_read?'read':''}"></i>`:'';
            const html=`<div class="msg-row ${sent?'sent':'received'}"><div id="msg-${msg.id}" class="bubble ${sent?'sent':'received'}">${c}<span class="b-meta">${t} ${ri}</span></div></div>`;
            document.getElementById('typingRow').insertAdjacentHTML('beforebegin',html);
            if(msg.id>lastId) lastId=msg.id;
        });
        if((data.messages||[]).length) msgsArea.scrollTop=msgsArea.scrollHeight;
        const dot=document.getElementById('onDot'),txt=document.getElementById('onText');
        if(data.isOnline){dot.className='on-dot on';txt.textContent='online';}
        else{dot.className='on-dot off';txt.textContent=data.lastSeen!=='Never'?'last seen '+data.lastSeen:'offline';}
    });
    fetch(`{{ route('admin.messages.typing.status',$conversation->id) }}`)
    .then(r=>r.json()).then(d=>{const t=document.getElementById('typingRow');if(d.isTyping){t.classList.add('on');msgsArea.scrollTop=msgsArea.scrollHeight;}else t.classList.remove('on');}).catch(()=>{});
},2500);

document.getElementById('msgForm').addEventListener('submit',function(e){
    e.preventDefault();
    const fd=new FormData(this);
    const inp=document.getElementById('msgInput');
    const sb=document.getElementById('sendBtn');
    if(!inp.value.trim()&&!document.getElementById('imgFile').files.length) return;
    const orig=inp.value; inp.value='';
    document.getElementById('imgFile').value=''; clearImg(); sb.disabled=true;
    fetch('{{ route("admin.messages.send") }}',{
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','X-Requested-With':'XMLHttpRequest','Accept':'application/json'},
        body:fd
    }).then(r=>r.json()).then(d=>{
        if(d.status==='success') inp.focus();
        else{inp.value=orig;alert('Error sending message');}
    }).catch(()=>{inp.value=orig;}).finally(()=>{sb.disabled=false;});
});
@endif
</script>
@endsection
