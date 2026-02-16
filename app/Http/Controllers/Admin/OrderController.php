<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $wasShipped = $order->status === 'shipped';
        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'shipped_at' => $request->status === 'shipped' ? ($order->shipped_at ?? now()) : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);

        if ($request->status === 'shipped' && !$wasShipped && $order->user) {
            try {
                Mail::to($order->user->email)->send(new OrderShippedMail($order->fresh()));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->back()->with('success', 'Order updated successfully.');
    }
}
