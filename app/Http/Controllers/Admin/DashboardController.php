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

        // Monthly Sales Data for Chart (last 6 months)
        $monthlySales = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $total = Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');
            
            $monthlySales[] = [
                'month' => $month->format('M'),
                'total' => (float)$total
            ];
        }

        return view('admin.dashboard', compact('stats', 'outOfStockProducts', 'lowStockProducts', 'recentOrders', 'monthlySales'));
    }

    public function analytics()
    {
        // 1. User Registrations (Last 12 Months)
        $userRegistrations = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = User::where('role', '!=', 'admin')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
            
            $userRegistrations[] = [
                'label' => $month->format('M Y'),
                'count' => $count
            ];
        }

        // 2. Order Distribution (Last 30 Days)
        $dailyOrders = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = Order::whereDate('created_at', $date->toDateString())->count();
            $dailyOrders[] = [
                'label' => $date->format('d M'),
                'count' => $count
            ];
        }

        // 3. Revenue Trends (Last 12 Months)
        $revenueTrends = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $total = Order::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');
            
            $revenueTrends[] = [
                'label' => $month->format('M'),
                'value' => (float)$total
            ];
        }

        // 4. Category Distribution
        $categories = Category::withCount('products')->get();
        $categoryData = $categories->map(function($cat) {
            return [
                'name' => $cat->name,
                'count' => $cat->products_count
            ];
        });

        // 5. Order Status Breakdown
        $statusBreakdown = Order::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // 6. Top Products (by Order Count)
        $topProducts = \DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.name as product_name', \DB::raw('count(*) as total_orders'), \DB::raw('sum(order_items.quantity) as total_quantity'))
            ->groupBy('products.name', 'products.id')
            ->orderByDesc('total_orders')
            ->take(5)
            ->get();

        return view('admin.analytics', compact(
            'userRegistrations', 
            'dailyOrders', 
            'revenueTrends', 
            'categoryData', 
            'statusBreakdown', 
            'topProducts'
        ));
    }
}
