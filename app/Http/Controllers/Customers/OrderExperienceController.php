<?php

namespace App\Http\Controllers\Customers;

use App\Http\Controllers\Controller;
use App\Models\CourierFeedback;
use App\Models\Order;
use App\Models\ReturnRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderExperienceController extends Controller
{
    private function ensureOwnedOrder(Order $order): void
    {
        $user = Auth::user();
        if (!$user || (int) $order->user_id !== (int) $user->id) {
            abort(403);
        }
    }

    public function showRefundForm(Order $order)
    {
        $this->ensureOwnedOrder($order);

        if ($order->status !== 'delivered') {
            return redirect()
                ->route('orders.show', $order)
                ->with('toast_type', 'error')
                ->with('toast_message', 'Refund requests are available after the order is delivered.');
        }

        $order->loadMissing('items.product');
        $refundRequest = ReturnRequest::where('order_id', $order->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if ($refundRequest && $refundRequest->status === 'pending') {
            return redirect()
                ->route('orders.show', $order)
                ->with('toast_type', 'error')
                ->with('toast_message', 'You already have a pending refund request. You can track its status in your order details.');
        }

        return view('user.refund_request', compact('order', 'refundRequest'));
    }

    public function storeRefund(Request $request, Order $order): RedirectResponse
    {
        $this->ensureOwnedOrder($order);

        if ($order->status !== 'delivered') {
            return redirect()
                ->route('orders.show', $order)
                ->with('toast_type', 'error')
                ->with('toast_message', 'Refund requests are available after the order is delivered.');
        }

        $existingRequest = ReturnRequest::where('order_id', $order->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->first();

        if ($existingRequest && $existingRequest->status === 'pending') {
            return redirect()
                ->route('orders.show', $order)
                ->with('toast_type', 'error')
                ->with('toast_message', 'You already have a pending refund request for this order. Please wait for an update.');
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:120',
            'other_reason' => 'required_if:reason,Other|nullable|string|max:120',
            'details' => 'nullable|string|max:2000',
            'proof_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'other_reason.required_if' => 'Please specify the reason when selecting Others.',
            'proof_image.max' => 'Proof image must not exceed 5MB.',
        ]);

        $finalReason = $validated['reason'] === 'Other'
            ? trim((string) ($validated['other_reason'] ?? ''))
            : $validated['reason'];

        $proofImagePath = $existingRequest?->proof_image_path;
        if ($request->hasFile('proof_image')) {
            if (!empty($proofImagePath)) {
                Storage::disk('public')->delete($proofImagePath);
            }

            $proofImagePath = $request->file('proof_image')->store('refund-proofs', 'public');
        }

        ReturnRequest::updateOrCreate(
            [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
            ],
            [
                'reason' => $finalReason,
                'details' => $validated['details'] ?? null,
                'proof_image_path' => $proofImagePath,
                'requested_amount' => $order->total_price,
                'status' => 'pending',
                'admin_notes' => null,
                'resolved_at' => null,
            ]
        );

        return redirect()
            ->route('orders.show', $order)
            ->with('toast_type', 'success')
            ->with('toast_message', 'Your refund request has been submitted.');
    }

    public function storeCourierFeedback(Request $request, Order $order): RedirectResponse
    {
        $this->ensureOwnedOrder($order);

        if ($order->status !== 'delivered') {
            return redirect()
                ->route('orders.show', $order)
                ->with('toast_type', 'error')
                ->with('toast_message', 'Courier feedback is available only after delivery.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:800',
        ]);

        CourierFeedback::updateOrCreate(
            [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
            ],
            [
                'courier_name' => $order->courier_name,
                'rating' => (int) $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        return redirect()
            ->route('orders.show', $order)
            ->with('toast_type', 'success')
            ->with('toast_message', 'Thanks for your courier feedback.');
    }
}
