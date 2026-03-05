@extends('layouts.admin')

@section('title', 'Messages')
@section('header_title', 'Customer Messages')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin-messages.css') }}?v={{ time() }}">
@endsection

@section('admin_content')

<div class="chat-wrap {{ isset($conversation) ? 'has-active-chat' : 'no-active-chat' }}">

    {{-- ████ LEFT — CONVERSATIONS ████ --}}
    <div class="chat-sidebar" id="chatSidebar">
        <div class="sidebar-head">
            <h3>Chats</h3>
            <div class="sidebar-head-right">
                <button class="s-btn theme-toggle" id="dmToggle" title="Toggle dark mode">
                    <i class="fa-solid fa-moon theme-toggle-icon" id="dmIcon"></i>
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
                    <button class="ch-action-btn theme-toggle" title="Toggle theme">
                        <i class="fa-solid fa-moon theme-toggle-icon" id="dmIcon2"></i>
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
