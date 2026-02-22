<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ecommerce\Category;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\Product;
use Illuminate\View\View;

class AdminDashboardController extends BaseController
{
    public function __invoke(): View
    {
        $stats = [
            'categories_count' => Category::count(),
            'products_count' => Product::count(),
            'orders_count' => Order::count(),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'orders_paid' => Order::where('status', 'paid')->count(),
            'orders_shipped' => Order::where('status', 'shipped')->count(),
            'orders_recent' => Order::with(['user', 'orderItems.product'])
                ->latest()
                ->take(10)
                ->get(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
