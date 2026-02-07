<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
// use App\Models\Order; // Uncomment when you have an Order model

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Fetch Stats
        $totalUsers = User::count();
        $totalProducts = Product::count();
        
        // Placeholder data until you have an Order model
        $totalOrders = 0; 
        $totalRevenue = 0; 
        $recentOrders = [];

        // If you had an Order model, you would do:
        // $totalOrders = Order::count();
        // $totalRevenue = Order::sum('total_price');
        // $recentOrders = Order::latest()->take(5)->get();

        return view('admin.admin_dashboard', compact('totalUsers', 'totalProducts', 'totalOrders', 'totalRevenue', 'recentOrders'));
    }
}