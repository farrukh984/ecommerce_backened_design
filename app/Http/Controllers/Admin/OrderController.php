<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdatedMail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Mark all as viewed
        Order::where('is_viewed', false)->update(['is_viewed' => true]);

        $query = Order::with('user')->withCount('items');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', $search) // Exact match for numeric IDs
                      ->orWhere('name', 'like', "%{$search}%");
                } else {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                }
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $orders = $query->latest()->paginate(15);

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,processing,shipped,delivered,cancelled',
        ]);

        $oldStatus = $order->status;

        $order->update([
            'status' => $request->status,
            'is_viewed_by_user' => false // Notify user of status change in dashboard
        ]);

        // Send Email Notification to User
        try {
            Mail::to($order->email)->send(new OrderStatusUpdatedMail($order, $oldStatus));
        } catch (\Exception $e) {
            // Silently fail if mail fails (optional logger here)
        }

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status) . ' and email sent to customer.');
    }
}
