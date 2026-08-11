<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $customerCount = User::where('role', 'customer')->count();

        if ($user->role === 'admin') {
            $stats = $this->adminStats();

            $recentOrders = Order::with('user')->latest('placed_at')->limit(8)->get();
            $topProducts = $this->topProducts();
            $salesTrend = $this->salesTrend();
            $statusBreakdown = $this->statusBreakdown();
            $quickLinks = $this->adminQuickLinks();

            $isAdmin = true;
        } else {
            $vendor = $user->vendor;
            $productIds = $vendor ? $vendor->products()->pluck('products.id') : collect();
            $hasProducts = ! $productIds->isEmpty();

            $stats = [
                'orderCount' => $hasProducts
                    ? Order::whereHas('items', fn ($q) => $q->whereIn('product_id', $productIds))->count()
                    : 0,
                'revenue' => $hasProducts
                    ? (float) DB::table('order_items')->whereIn('product_id', $productIds)->sum('line_total')
                    : 0,
                'customerCount' => $customerCount,
                'productCount' => $productIds->count(),
                'pendingOrders' => $hasProducts
                    ? Order::where('status', 'pending')
                        ->whereHas('items', fn ($q) => $q->whereIn('product_id', $productIds))
                        ->count()
                    : 0,
            ];

            $recentOrders = $hasProducts
                ? Order::with('user')
                    ->whereHas('items', fn ($q) => $q->whereIn('product_id', $productIds))
                    ->latest('placed_at')
                    ->limit(8)
                    ->get()
                : collect();

            $topProducts = $this->topProducts($productIds);
            $salesTrend = $this->salesTrend($productIds);
            $statusBreakdown = $this->statusBreakdown($productIds);
            $quickLinks = $this->sellerQuickLinks();

            $isAdmin = false;
        }

        return view('admin.index', array_merge($stats, [
            'isAdmin' => $isAdmin,
            'recentOrders' => $recentOrders,
            'topProducts' => $topProducts,
            'salesTrend' => $salesTrend,
            'statusBreakdown' => $statusBreakdown,
            'quickLinks' => $quickLinks,
        ]));
    }

    protected function adminStats(): array
    {
        $today = Carbon::today();

        return [
            'orderCount' => Order::count(),
            'revenue' => (float) Order::sum('total_amount'),
            'customerCount' => User::where('role', 'customer')->count(),
            'productCount' => Product::count(),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'pendingApprovals' => Product::where('approval_status', 'pending')->count(),
            'totalSellers' => Vendor::count(),
            'pendingSellers' => Vendor::where('status', 'pending')->count(),
            'todayOrders' => Order::where('placed_at', '>=', $today)->count(),
            'todayRevenue' => (float) Order::where('placed_at', '>=', $today)->sum('total_amount'),
        ];
    }

    protected function topProducts(?Collection $productIds = null): Collection
    {
        $query = DB::table('order_items')
            ->select('product_id')
            ->selectRaw('MAX(product_name) as product_name')
            ->selectRaw('SUM(quantity) as total_qty')
            ->selectRaw('SUM(line_total) as total_revenue')
            ->groupBy('product_id');

        if ($productIds !== null && $productIds->isEmpty()) {
            return collect();
        }

        if ($productIds !== null) {
            $query->whereIn('product_id', $productIds);
        }

        return $query->orderByDesc('total_revenue')->limit(5)->get();
    }

    protected function salesTrend(?Collection $productIds = null, int $days = 14): array
    {
        $start = Carbon::today()->subDays($days - 1);
        $labels = [];
        $revenue = [];

        $query = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('DATE(orders.placed_at) as d')
            ->selectRaw('SUM(order_items.line_total) as revenue')
            ->where('orders.placed_at', '>=', $start->startOfDay());

        if ($productIds !== null && $productIds->isEmpty()) {
            $query->whereRaw('0 = 1');
        } elseif ($productIds !== null) {
            $query->whereIn('order_items.product_id', $productIds);
        }

        $rows = $query->groupBy('d')->orderBy('d')->get()->keyBy('d');

        for ($i = 0; $i < $days; $i++) {
            $day = $start->copy()->addDays($i);
            $labels[] = $day->format('M j');
            $revenue[] = round((float) ($rows[$day->toDateString()]->revenue ?? 0), 2);
        }

        return ['labels' => $labels, 'revenue' => $revenue];
    }

    protected function statusBreakdown(?Collection $productIds = null): Collection
    {
        $query = DB::table('orders')->select('status')->selectRaw('COUNT(*) as total');

        if ($productIds !== null && $productIds->isEmpty()) {
            return collect();
        }

        if ($productIds !== null) {
            $query->whereIn('orders.id', function ($sub) use ($productIds) {
                $sub->select('order_id')->from('order_items')->whereIn('product_id', $productIds);
            });
        }

        return $query->groupBy('status')->orderBy('total', 'desc')->get();
    }

    protected function adminQuickLinks(): array
    {
        return [
            ['label' => 'Orders', 'icon' => 'fa-shopping-cart', 'color' => 'text-blue', 'route' => 'admin.order.index'],
            ['label' => 'Products', 'icon' => 'fa-box', 'color' => 'text-pink', 'route' => 'admin.product.index'],
            ['label' => 'Customers', 'icon' => 'fa-user', 'color' => 'text-green', 'route' => 'admin.customer.index'],
            ['label' => 'Sellers', 'icon' => 'fa-store', 'color' => 'text-orange', 'route' => 'admin.seller.index'],
            ['label' => 'Categories', 'icon' => 'fa-th-large', 'color' => 'text-blue', 'route' => 'admin.category.index'],
            ['label' => 'Coupons', 'icon' => 'fa-ticket-alt', 'color' => 'text-pink', 'route' => 'admin.coupon.index'],
            ['label' => 'Reviews', 'icon' => 'fa-star', 'color' => 'text-green', 'route' => 'admin.review.index'],
            ['label' => 'Banners', 'icon' => 'fa-image', 'color' => 'text-orange', 'route' => 'admin.banner.index'],
        ];
    }

    protected function sellerQuickLinks(): array
    {
        return [
            ['label' => 'My Products', 'icon' => 'fa-box', 'color' => 'text-pink', 'route' => 'seller.product.index'],
            ['label' => 'Add Product', 'icon' => 'fa-plus', 'color' => 'text-green', 'route' => 'seller.product.create'],
            ['label' => 'My Orders', 'icon' => 'fa-shopping-cart', 'color' => 'text-blue', 'route' => 'seller.order.index'],
            ['label' => 'Request Category', 'icon' => 'fa-th-large', 'color' => 'text-orange', 'route' => 'seller.request.category'],
            ['label' => 'Request Coupon', 'icon' => 'fa-ticket-alt', 'color' => 'text-pink', 'route' => 'seller.request.coupon'],
            ['label' => 'Settings', 'icon' => 'fa-cog', 'color' => 'text-blue', 'route' => 'seller.settings'],
        ];
    }
}
