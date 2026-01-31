<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'total_sales' => Sale::count(),
            'total_users' => User::count(),
            'today_sales' => Sale::whereDate('sale_date', today())->count(),
            'today_revenue' => Sale::whereDate('sale_date', today())->sum('total'),
            'month_sales' => Sale::whereMonth('sale_date', now()->month)->count(),
            'month_revenue' => Sale::whereMonth('sale_date', now()->month)->sum('total'),
        ];

        $recent_sales = Sale::with(['customer', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recent_sales'));
    }
}
