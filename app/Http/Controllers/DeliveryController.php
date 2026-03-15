<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DeliveryController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:processing,shipped,delivered',
        ]);

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
            'courier_name' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:500',
        ]);

        $previousStatus = $order->status;
        $wasDelivered = $order->status === 'delivered';

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'courier_name' => $request->input('courier_name'),
            'tracking_url' => $request->input('tracking_url'),
            'shipped_at' => $request->status === 'shipped' ? ($order->shipped_at ?? now()) : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);

        if ($request->status !== $previousStatus && $order->user) {
            try {
                Mail::to($order->user->email)->send(
                    new OrderStatusUpdatedMail($order->fresh(['user', 'items.product']), $previousStatus)
                );
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if ($request->status === 'delivered' && !$wasDelivered && $order->user) {
            $pointsEarned = floor($order->total_price / 100); // 1 point per $100 spent
            $order->user->increment('points_balance', $pointsEarned);
        }

        return redirect()->back()->with('success', 'Delivery status updated.');
    }
}
