<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Mail\Admin\NewInquiryAlert;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class MessageController extends Controller
{
    /**
     * List all conversations for the authenticated user.
     */
    public function index()
    {
        $user  = Auth::user();
        $admin = User::where('role', 'admin')->latest('id')->first();

        $conversations = Conversation::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'messages.user'])
            ->latest('last_message_at')
            ->get();

        // Mark all unread messages as read when viewing the list
        Message::where('is_read', false)
            ->where('user_id', '!=', $user->id)
            ->whereHas('conversation', fn($q) =>
                $q->where('sender_id', $user->id)->orWhere('receiver_id', $user->id)
            )
            ->update(['is_read' => true]);

        $view = $user->role === 'admin' ? 'admin.messages' : 'user.messages';

        return view($view, compact('conversations', 'admin'));
    }

    /**
     * Show a specific conversation chat.
     */
    public function chat($id)
    {
        $user  = Auth::user();
        $admin = User::where('role', 'admin')->latest('id')->first();

        $conversation = Conversation::with(['sender', 'receiver', 'messages.user'])->findOrFail($id);

        // Security: user must be part of conversation
        if ((int) $conversation->sender_id !== (int) $user->id &&
            (int) $conversation->receiver_id !== (int) $user->id) {
            abort(403, 'Unauthorized.');
        }

        // Mark messages from the other user as read
        Message::where('conversation_id', $id)
            ->where('user_id', '!=', $user->id)
            ->update(['is_read' => true]);

        // All conversations for sidebar
        $conversations = Conversation::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'messages.user'])
            ->latest('last_message_at')
            ->get();

        $messages = $conversation->messages->sortBy('created_at');

        $view = $user->role === 'admin' ? 'admin.messages' : 'user.messages';

        return view($view, compact('conversations', 'conversation', 'messages', 'admin'));
    }

    /**
     * Send a message (text or image).
     */
    public function send(Request $request)
    {
        $request->validate([
            'message'         => 'nullable|string',
            'image'           => 'nullable|image|max:5120',
            'receiver_id'     => 'required_without:conversation_id|exists:users,id',
            'conversation_id' => 'nullable|exists:conversations,id',
        ]);

        $senderId       = Auth::id();
        $conversationId = $request->conversation_id;

        // Find or create conversation
        if (! $conversationId) {
            $receiverId   = $request->receiver_id;
            $conversation = Conversation::where(function ($q) use ($senderId, $receiverId) {
                $q->where('sender_id', $senderId)->where('receiver_id', $receiverId);
            })->orWhere(function ($q) use ($senderId, $receiverId) {
                $q->where('sender_id', $receiverId)->where('receiver_id', $senderId);
            })->first();

            if (! $conversation) {
                $conversation = Conversation::create([
                    'sender_id'       => $senderId,
                    'receiver_id'     => $receiverId,
                    'last_message_at' => now(),
                ]);
            }

            $conversationId = $conversation->id;
        }

        // Handle image upload
        $type     = 'text';
        $filePath = null;

        if ($request->hasFile('image')) {
            $type     = 'image';
            $filePath = Cloudinary::uploadApi()
                ->upload($request->file('image')->getRealPath(), ['folder' => 'chat_images'])['secure_url'];
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversationId,
            'user_id'         => $senderId,
            'message'         => $request->message ?? ($type === 'image' ? 'Sent an image' : ''),
            'is_read'         => false,
            'type'            => $type,
            'file_path'       => $filePath,
        ]);

        Conversation::where('id', $conversationId)->update(['last_message_at' => now()]);

        // Notify admin if receiver is admin
        $conversation = Conversation::find($conversationId);
        $receiver     = $conversation->sender_id === $senderId
            ? $conversation->receiver
            : $conversation->sender;

        if ($receiver && $receiver->role === 'admin') {
            try {
                $message->load('sender');
                Mail::to($receiver->email)->send(new NewInquiryAlert($message));
            } catch (\Exception $e) {
                \Log::error('Admin Message Mail Error: ' . $e->getMessage());
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'status'  => 'success',
                'message' => $message->load('user'),
            ]);
        }

        return redirect()->back();
    }

    /**
     * Poll new messages for a conversation (AJAX).
     */
    public function getMessages($id)
    {
        $lastId = request('last_id', 0);

        $messages = Message::where('conversation_id', $id)
            ->where('id', '>', $lastId)
            ->with('user')
            ->get();

        // Mark messages from other user as read
        Message::where('conversation_id', $id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Other user's online status
        $conversation = Conversation::find($id);
        $otherUser    = $conversation->sender_id === Auth::id()
            ? $conversation->receiver
            : $conversation->sender;

        $isOnline = $otherUser->last_seen_at
            && $otherUser->last_seen_at->diffInMinutes(now()) < 5;

        return response()->json([
            'messages' => $messages,
            'isOnline' => $isOnline,
            'lastSeen' => $otherUser->last_seen_at
                ? $otherUser->last_seen_at->diffForHumans()
                : 'Never',
        ]);
    }

    /**
     * Record that the current user is typing.
     */
    public function typing(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        Cache::put(
            'typing_' . $request->conversation_id . '_' . Auth::id(),
            true,
            now()->addSeconds(5)
        );

        return response()->json(['status' => 'ok']);
    }

    /**
     * Check if the other user is typing.
     */
    public function typingStatus($id)
    {
        $conversation = Conversation::findOrFail($id);

        $otherUserId = $conversation->sender_id === Auth::id()
            ? $conversation->receiver_id
            : $conversation->sender_id;

        $isTyping = Cache::get('typing_' . $id . '_' . $otherUserId, false);

        return response()->json(['isTyping' => $isTyping]);
    }
}
