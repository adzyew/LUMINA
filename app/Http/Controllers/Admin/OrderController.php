<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\InventoryLog;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

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

    public function previewPlacedEmail(Order $order): OrderPlacedMail
    {
        return new OrderPlacedMail($order->fresh(['user', 'items.product']));
    }

    public function previewStatusEmail(Request $request, Order $order): OrderStatusUpdatedMail
    {
        $request->validate([
            'status' => 'nullable|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'previous_status' => 'nullable|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $previewOrder = $order->fresh(['user', 'items.product']);
        $previewStatus = (string) ($request->input('status') ?: $previewOrder->status);
        $previousStatus = (string) ($request->input('previous_status') ?: $order->status);

        $previewOrder->status = $previewStatus;

        if ($previewStatus === 'shipped') {
            $previewOrder->courier_name = $previewOrder->courier_name ?: 'Standard Carrier';
            $previewOrder->tracking_url = $previewOrder->tracking_url ?: 'https://example-courier.test/track/' . urlencode((string) $previewOrder->tracking_number);
        }

        return new OrderStatusUpdatedMail($previewOrder, $previousStatus);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
            'courier_name' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:500',
        ]);

        $previousStatus = $order->status;
        $wasDelivered = $order->status === 'delivered';

        $order->update([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'courier_name' => $request->has('courier_name') ? $request->input('courier_name') : $order->courier_name,
            'tracking_url' => $request->has('tracking_url') ? $request->input('tracking_url') : $order->tracking_url,
            'shipped_at' => $request->status === 'shipped' ? ($order->shipped_at ?? now()) : $order->shipped_at,
            'delivered_at' => $request->status === 'delivered' ? now() : $order->delivered_at,
        ]);

        // Restore stock when cancelling a non-cancelled order
        if ($request->status === 'cancelled' && $previousStatus !== 'cancelled') {
            $order->load('items.product');
            foreach ($order->items as $item) {
                if ($item->product) {
                    $previousStock = $item->product->stock_quantity;
                    $item->product->increment('stock_quantity', $item->quantity);
                    InventoryLog::create([
                        'product_id'      => $item->product_id,
                        'user_id'         => auth()->id(),
                        'quantity_change' => $item->quantity,
                        'previous_stock'  => $previousStock,
                        'new_stock'       => $previousStock + $item->quantity,
                        'reason'          => 'Stock restored — Order #' . $order->id . ' cancelled.',
                        'reference_id'    => $order->id,
                    ]);
                }
            }
        }

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

        return redirect()->back()->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $orderId = $order->id;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Order #{$orderId} deleted successfully.");
    }
}
