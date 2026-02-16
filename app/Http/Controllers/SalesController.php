<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $totalRevenue = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->sum('total_price');
        $totalOrders = Order::whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $recentOrders = Order::with('user')
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->latest()
            ->take(10)
            ->get();

        $orders = Order::with('user')
            ->whereIn('status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.sales.index', compact('totalRevenue', 'totalOrders', 'pendingOrders', 'recentOrders', 'orders'));
    }
}
