<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])
            ->whereIn('status', ['processing', 'shipped', 'delivered'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.deliveries.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        if (!in_array($order->status, ['processing', 'shipped', 'delivered'])) {
            return redirect()->route('admin.orders.show', $order)
                ->with('info', 'This order is not yet in delivery.');
        }

        return view('admin.deliveries.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processing,shipped,delivered',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'shipped_at' => $request->status === 'shipped' ? ($order->shipped_at ?? now()) : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);

        return redirect()->back()->with('success', 'Delivery status updated.');
    }
}
