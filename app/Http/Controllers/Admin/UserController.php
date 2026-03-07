<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders')->latest();

        // Filter by role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15);

        $stats = [
            'total' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'customers' => User::where('role', 'user')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself!');
        }

        try {
            \DB::transaction(function() use ($user) {
                // Delete messages directly associated with the user
                \App\Models\Message::where('user_id', $user->id)->delete();
                
                // Delete conversations where user is sender or receiver
                \App\Models\Conversation::where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id)
                    ->delete();
                    
                $user->delete();
            });

            return back()->with('success', 'User has been deleted successfully. They can no longer login unless they register again.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
