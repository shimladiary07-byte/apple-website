<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $dispatchedOrders = Order::where('status', 'dispatched')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_amount');

        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $unreadInquiries = Inquiry::where('is_read', false)->count();

        $recentOrders = Order::orderBy('created_at', 'desc')->take(6)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'dispatched_orders' => $dispatchedOrders,
                'delivered_orders' => $deliveredOrders,
                'total_revenue' => (float) $totalRevenue,
                'total_products' => $totalProducts,
                'active_products' => $activeProducts,
                'unread_inquiries' => $unreadInquiries,
                'recent_orders' => $recentOrders,
            ],
        ]);
    }
}
