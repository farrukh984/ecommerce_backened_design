<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get all conversations for this user with messages for unread count
        $conversations = Conversation::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver', 'messages.user'])
            ->latest('last_message_at')
            ->get();

        // Check if user has an active conversation with admin
        $admin = User::where('role', 'admin')->first();
        $hasAdminChat = false;
        if ($admin) {
            $hasAdminChat = $conversations->contains(function($c) use ($admin) {
                return $c->sender_id === $admin->id || $c->receiver_id === $admin->id;
            });
        }

        $view = $user->role === 'admin' ? 'admin.messages' : 'user.messages';

        return view($view, [
            'conversations' => $conversations,
            'active' => 'messages',
            'admin' => $admin,
            'hasAdminChat' => $hasAdminChat
        ]);
    }

    public function chat($id)
    {
        $conversation = Conversation::with(['sender', 'receiver', 'messages.user'])
            ->findOrFail($id);

        $authId = (int) Auth::id();
        $senderId = (int) $conversation->sender_id;
        $receiverId = (int) $conversation->receiver_id;

        // Security check: user must be part of the conversation
        if ($senderId !== $authId && $receiverId !== $authId) {
            abort(403, 'You are not authorized to view this conversation.');
        }

        // Mark messages as read
        Message::where('conversation_id', $id)
            ->where('user_id', '!=', Auth::id())
            ->update(['is_read' => true]);

        // Get all conversations for sidebar
        $conversations = Conversation::where('sender_id', Auth::id())
            ->orWhere('receiver_id', Auth::id())
            ->with(['sender', 'receiver', 'messages.user'])
            ->latest('last_message_at')
            ->get();

        // Messages for this conversation
        $messages = $conversation->messages->sortBy('created_at');

        // Check if user has an active conversation with admin
        $admin = User::where('role', 'admin')->first();
        $hasAdminChat = false;
        if ($admin) {
            $hasAdminChat = $conversations->contains(function($c) use ($admin) {
                return $c->sender_id === $admin->id || $c->receiver_id === $admin->id;
            });
        }

        $view = Auth::user()->role === 'admin' ? 'admin.messages' : 'user.messages';

        return view($view, [
            'conversations' => $conversations,
            'conversation' => $conversation,
            'messages' => $messages,
            'active' => 'messages',
            'admin' => $admin,
            'hasAdminChat' => $hasAdminChat
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:5120', // Max 5MB
            'receiver_id' => 'required_without:conversation_id|exists:users,id',
            'conversation_id' => 'nullable|exists:conversations,id'
        ]);

        $sender_id = Auth::id();
        $conversation_id = $request->conversation_id;

        // Find or Create conversation
        if (!$conversation_id) {
            $receiver_id = $request->receiver_id;
            $conversation = Conversation::where(function($q) use ($sender_id, $receiver_id) {
                $q->where('sender_id', $sender_id)->where('receiver_id', $receiver_id);
            })->orWhere(function($q) use ($sender_id, $receiver_id) {
                $q->where('sender_id', $receiver_id)->where('receiver_id', $sender_id);
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'sender_id' => $sender_id,
                    'receiver_id' => $receiver_id,
                    'last_message_at' => now()
                ]);
            }
            $conversation_id = $conversation->id;
        }

        $type = 'text';
        $file_path = null;

        if ($request->hasFile('image')) {
            $type = 'image';
            $file_path = $request->file('image')->store('chat_images', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation_id,
            'user_id' => $sender_id,
            'message' => $request->message ?? ($type === 'image' ? 'Sent an image' : ''),
            'is_read' => false,
            'type' => $type,
            'file_path' => $file_path
        ]);

        Conversation::where('id', $conversation_id)->update(['last_message_at' => now()]);

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message->load('user')
            ]);
        }

        return redirect()->back();
    }

    public function getMessages($id)
    {
        $last_message_id = request('last_id');
        
        $messages = Message::where('conversation_id', $id)
            ->where('id', '>', $last_message_id)
            ->with('user')
            ->get();

        // Mark as read while fetching
        Message::where('conversation_id', $id)
            ->where('user_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Get other user online status
        $conversation = Conversation::find($id);
        $otherUser = $conversation->sender_id === Auth::id() ? $conversation->receiver : $conversation->sender;
        $isOnline = $otherUser->last_seen_at && $otherUser->last_seen_at->diffInMinutes(now()) < 5;

        return response()->json([
            'messages' => $messages,
            'isOnline' => $isOnline,
            'lastSeen' => $otherUser->last_seen_at ? $otherUser->last_seen_at->diffForHumans() : 'Never'
        ]);
    }
}
