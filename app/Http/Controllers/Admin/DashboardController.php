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
        // Cache basic stats for 10 minutes
        $stats = \Illuminate\Support\Facades\Cache::remember('admin_dashboard_stats', 600, function() {
            return [
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
        });

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

        // Optimized Monthly Sales Data (Single Query)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $monthlySalesRaw = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_amount) as total')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $monthlySales = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthObj = now()->subMonths($i);
            $found = $monthlySalesRaw->where('month', $monthObj->month)->where('year', $monthObj->year)->first();
            $monthlySales[] = [
                'month' => $monthObj->format('M'),
                'total' => $found ? (float)$found->total : 0
            ];
        }

        return view('admin.dashboard', compact('stats', 'outOfStockProducts', 'lowStockProducts', 'recentOrders', 'monthlySales'));
    }

    public function analytics()
    {
        // 1. User Registrations (Single Query)
        $twelveMonthsAgo = now()->subMonths(11)->startOfMonth();
        $userRegRaw = User::where('role', '!=', 'admin')
            ->where('created_at', '>=', $twelveMonthsAgo)
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
            ->groupBy('year', 'month')
            ->get();

        $userRegistrations = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthObj = now()->subMonths($i);
            $found = $userRegRaw->where('month', $monthObj->month)->where('year', $monthObj->year)->first();
            $userRegistrations[] = [
                'label' => $monthObj->format('M Y'),
                'count' => $found ? $found->count : 0
            ];
        }

        // 2. Order Distribution (Last 30 Days - Single Query)
        $thirtyDaysAgo = now()->subDays(29)->startOfDay();
        $dailyOrdersRaw = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->get();

        $dailyOrders = [];
        for ($i = 29; $i >= 0; $i--) {
            $dateObj = now()->subDays($i);
            $found = $dailyOrdersRaw->where('date', $dateObj->toDateString())->first();
            $dailyOrders[] = [
                'label' => $dateObj->format('d M'),
                'count' => $found ? $found->count : 0
            ];
        }

        // 3. Revenue Trends (Last 12 Months - Single Query)
        $revTrendsRaw = Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $twelveMonthsAgo)
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total_amount) as total')
            ->groupBy('year', 'month')
            ->get();

        $revenueTrends = [];
        for ($i = 11; $i >= 0; $i--) {
            $monthObj = now()->subMonths($i);
            $found = $revTrendsRaw->where('month', $monthObj->month)->where('year', $monthObj->year)->first();
            $revenueTrends[] = [
                'label' => $monthObj->format('M'),
                'value' => $found ? (float)$found->total : 0
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

        // 6. Top Products
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
