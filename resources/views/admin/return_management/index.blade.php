@extends('admin.admin_layout')

@section('title', 'Returns | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header>
        <h1 class="text-4xl font-playfair font-bold text-gray-900">Returns & Refunds</h1>
        <p class="text-sm text-gray-600 mt-1">Review customer refund requests and resolve them with clear notes.</p>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <article class="rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-blue-700 font-semibold">Total Requests</p>
            <p class="text-4xl font-bold text-blue-700 mt-2">{{ number_format($stats['total'] ?? 0) }}</p>
        </article>
        <article class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-amber-700 font-semibold">Pending</p>
            <p class="text-4xl font-bold text-amber-600 mt-2">{{ number_format($stats['pending'] ?? 0) }}</p>
        </article>
        <article class="rounded-3xl border border-green-200 bg-gradient-to-br from-green-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-green-700 font-semibold">Approved</p>
            <p class="text-4xl font-bold text-green-600 mt-2">{{ number_format($stats['approved'] ?? 0) }}</p>
        </article>
        <article class="rounded-3xl border border-red-200 bg-gradient-to-br from-red-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-red-700 font-semibold">Rejected</p>
            <p class="text-4xl font-bold text-red-600 mt-2">{{ number_format($stats['rejected'] ?? 0) }}</p>
        </article>
    </div>

    <section class="bg-white border border-gray-200 rounded-3xl p-4 shadow-sm">
        <form method="GET" action="{{ route('admin.returns.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                placeholder="Search order #, customer, reason"
                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900"
            >
            <select name="status" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-900">
                <option value="">All Status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
            </select>
            <div class="flex gap-2">
                <button type="submit" class="w-full rounded-xl bg-amber-300 hover:bg-amber-400 text-black font-semibold px-4 py-2.5 text-sm">Filter</button>
                <a href="{{ route('admin.returns.index') }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-center">Reset</a>
            </div>
        </form>
    </section>

    <section class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Order</th>
                        <th class="px-4 py-3 font-semibold">Customer</th>
                        <th class="px-4 py-3 font-semibold">Reason</th>
                        <th class="px-4 py-3 font-semibold">Requested</th>
                        <th class="px-4 py-3 font-semibold">Status</th>
                        <th class="px-4 py-3 font-semibold">Requested At</th>
                        <th class="px-4 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($returnRequests as $refund)
                        @php
                            $badgeClass = match($refund->status) {
                                'approved' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                default => 'bg-amber-100 text-amber-700',
                            };
                        @endphp
                        <tr class="align-top hover:bg-amber-50/40 transition-colors">
                            <td class="px-4 py-4">
                                <p class="font-semibold text-gray-900">#{{ $refund->order?->order_number ?? ('ORD-'.$refund->order_id) }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $refund->display_request_number }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-900">{{ $refund->user?->name ?? 'Unknown User' }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $refund->user?->email ?? '-' }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-gray-900">{{ $refund->reason }}</p>
                                @if($refund->details)
                                    <p class="text-xs text-gray-600 mt-1 max-w-md line-clamp-3">{{ $refund->details }}</p>
                                @endif
                                @if($refund->proof_image_path)
                                    <a href="{{ asset('storage/' . $refund->proof_image_path) }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-2">
                                        <img src="{{ asset('storage/' . $refund->proof_image_path) }}" alt="Refund proof photo" class="h-14 w-14 rounded-lg border border-gray-200 object-cover">
                                    </a>
                                @endif
                                @if($refund->admin_notes)
                                    <p class="text-xs text-gray-500 mt-2"><span class="font-semibold">Admin note:</span> {{ $refund->admin_notes }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4 font-semibold text-amber-600">
                                PHP {{ number_format((float) $refund->requested_amount, 2) }}
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst($refund->status) }}
                                </span>
                                @if($refund->resolved_at)
                                    <p class="text-xs text-gray-500 mt-2">Resolved {{ $refund->resolved_at->diffForHumans() }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-gray-600">
                                {{ $refund->created_at?->format('M d, Y g:i A') }}
                            </td>
                            <td class="px-4 py-4 min-w-[220px]">
                                @if($refund->status === 'pending')
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            type="button"
                                            onclick="toggleReturnAction('{{ $refund->id }}', 'approve')"
                                            class="h-10 w-10 rounded-lg bg-green-600 hover:bg-green-500 text-white inline-flex items-center justify-center transition-colors"
                                            title="Approve refund"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            onclick="toggleReturnAction('{{ $refund->id }}', 'reject')"
                                            class="h-10 w-10 rounded-lg bg-red-600 hover:bg-red-500 text-white inline-flex items-center justify-center transition-colors"
                                            title="Reject refund"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <form id="approve-form-{{ $refund->id }}" method="POST" action="{{ route('admin.returns.approve', $refund) }}" class="hidden mt-2 space-y-2">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex justify-end">
                                            <button
                                                type="button"
                                                onclick="closeReturnAction('{{ $refund->id }}')"
                                                class="inline-flex items-center justify-center h-6 w-6 rounded-md border border-gray-200 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                title="Close"
                                            >
                                                <span class="text-sm leading-none">&times;</span>
                                            </button>
                                        </div>
                                        <input
                                            type="text"
                                            name="admin_notes"
                                            maxlength="1000"
                                            placeholder="Optional note"
                                            class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs"
                                        >
                                        <button type="submit" class="w-full rounded-lg bg-green-600 hover:bg-green-500 text-white font-semibold px-3 py-2 text-xs inline-flex items-center justify-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Confirm Approve
                                        </button>
                                    </form>

                                    <form id="reject-form-{{ $refund->id }}" method="POST" action="{{ route('admin.returns.reject', $refund) }}" class="hidden mt-2 space-y-2" onsubmit="return confirm('Reject this refund request?');">
                                        @csrf
                                        @method('PATCH')
                                        <div class="flex justify-end">
                                            <button
                                                type="button"
                                                onclick="closeReturnAction('{{ $refund->id }}')"
                                                class="inline-flex items-center justify-center h-6 w-6 rounded-md border border-gray-200 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                                                title="Close"
                                            >
                                                <span class="text-sm leading-none">&times;</span>
                                            </button>
                                        </div>
                                        <input
                                            type="text"
                                            name="admin_notes"
                                            maxlength="1000"
                                            required
                                            placeholder="Reason for rejection"
                                            class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs"
                                        >
                                        <button type="submit" class="w-full rounded-lg bg-red-600 hover:bg-red-500 text-white font-semibold px-3 py-2 text-xs inline-flex items-center justify-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Confirm Reject
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-500">Resolved</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-500">No refund requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($returnRequests->hasPages())
            <div class="px-4 py-4 border-t border-gray-100">
                {{ $returnRequests->links() }}
            </div>
        @endif
    </section>
</div>

<script>
function toggleReturnAction(refundId, type) {
    const approveForm = document.getElementById('approve-form-' + refundId);
    const rejectForm = document.getElementById('reject-form-' + refundId);
    if (!approveForm || !rejectForm) return;

    const targetForm = type === 'approve' ? approveForm : rejectForm;
    const otherForm = type === 'approve' ? rejectForm : approveForm;

    otherForm.classList.add('hidden');
    targetForm.classList.toggle('hidden');
}

function closeReturnAction(refundId) {
    const approveForm = document.getElementById('approve-form-' + refundId);
    const rejectForm = document.getElementById('reject-form-' + refundId);
    if (approveForm) approveForm.classList.add('hidden');
    if (rejectForm) rejectForm.classList.add('hidden');
}
</script>
@endsection
