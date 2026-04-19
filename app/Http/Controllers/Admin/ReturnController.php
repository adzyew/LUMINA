<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function index(Request $request): View
    {
        $query = ReturnRequest::query()
            ->with([
                'user:id,name,email',
                'order:id,order_number,total_price,payment_method,payment_status,status',
            ])
            ->latest();

        $status = strtolower((string) $request->query('status', ''));
        if (in_array($status, [ReturnRequest::STATUS_PENDING, ReturnRequest::STATUS_APPROVED, ReturnRequest::STATUS_REJECTED], true)) {
            $query->where('status', $status);
        }

        if ($request->filled('q')) {
            $search = trim((string) $request->query('q'));
            $query->where(function ($builder) use ($search) {
                $builder->where('reason', 'like', '%' . $search . '%')
                    ->orWhere('details', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('order', function ($orderQuery) use ($search) {
                        $orderQuery->where('order_number', 'like', '%' . $search . '%');
                    });
            });
        }

        $returnRequests = $query->paginate(12)->withQueryString();

        $stats = [
            'total' => ReturnRequest::count(),
            'pending' => ReturnRequest::where('status', ReturnRequest::STATUS_PENDING)->count(),
            'approved' => ReturnRequest::where('status', ReturnRequest::STATUS_APPROVED)->count(),
            'rejected' => ReturnRequest::where('status', ReturnRequest::STATUS_REJECTED)->count(),
        ];

        return view('admin.return_management.index', compact('returnRequests', 'stats'));
    }

    public function approve(Request $request, ReturnRequest $returnRequest): RedirectResponse
    {
        if (!$returnRequest->isPending()) {
            return back()
                ->with('toast_type', 'error')
                ->with('toast_message', 'This refund request has already been resolved.');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $returnRequest->update([
            'status' => ReturnRequest::STATUS_APPROVED,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'resolved_at' => now(),
        ]);

        if ($returnRequest->order && $returnRequest->order->payment_method !== 'cod') {
            $returnRequest->order->update([
                'payment_status' => 'refunded',
            ]);
        }

        return back()
            ->with('toast_type', 'success')
            ->with('toast_message', 'Refund request approved successfully.');
    }

    public function reject(Request $request, ReturnRequest $returnRequest): RedirectResponse
    {
        if (!$returnRequest->isPending()) {
            return back()
                ->with('toast_type', 'error')
                ->with('toast_message', 'This refund request has already been resolved.');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $returnRequest->update([
            'status' => ReturnRequest::STATUS_REJECTED,
            'admin_notes' => $validated['admin_notes'],
            'resolved_at' => now(),
        ]);

        return back()
            ->with('toast_type', 'success')
            ->with('toast_message', 'Refund request rejected.');
    }
}
