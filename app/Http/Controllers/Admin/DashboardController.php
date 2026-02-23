<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'active_products' => Product::where('is_active', true)->count(),
            'inactive_products' => Product::where('is_active', false)->count(),
            'unread_messages' => Message::where('is_read', false)
                ->whereHas('conversation', function ($q) {
                    $q->where('receiver_id', auth()->id());
                })->count(),
        ];

        // Out of stock products
        $outOfStockProducts = Product::where('stock_quantity', '<=', 0)->take(10)->get();

        // Low stock products (between 1-9)
        $lowStockProducts = Product::where('stock_quantity', '>', 0)
            ->where('stock_quantity', '<', 10)
            ->orderBy('stock_quantity', 'asc')
            ->take(10)
            ->get();

        // Recent orders
        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'outOfStockProducts', 'lowStockProducts', 'recentOrders'));
    }
}
