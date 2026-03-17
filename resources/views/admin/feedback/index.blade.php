@extends('admin.admin_layout')

@section('title', 'Feedback Moderation | Lumina')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-gray-900">Feedback Moderation</h1>
            <p class="text-sm text-gray-600 mt-1">Review, approve, reject, and remove customer product feedback.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-4">
            <p class="text-xs uppercase tracking-wide text-gray-500">Total Reviews</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalReviews }}</p>
        </div>
        <div class="rounded-2xl border border-amber-300/30 bg-amber-50 p-4">
            <p class="text-xs uppercase tracking-wide text-amber-700">Pending</p>
            <p class="text-2xl font-bold text-amber-800 mt-1">{{ $pendingReviews }}</p>
        </div>
        <div class="rounded-2xl border border-rose-300/30 bg-rose-50 p-4">
            <p class="text-xs uppercase tracking-wide text-rose-700">Flagged</p>
            <p class="text-2xl font-bold text-rose-800 mt-1">{{ $flaggedReviews }}</p>
        </div>
        <div class="rounded-2xl border border-green-300/30 bg-green-50 p-4">
            <p class="text-xs uppercase tracking-wide text-green-700">Approval Rate</p>
            <p class="text-2xl font-bold text-green-800 mt-1">{{ number_format($approvalRate, 1) }}%</p>
        </div>
        <div class="rounded-2xl border border-blue-300/30 bg-blue-50 p-4">
            <p class="text-xs uppercase tracking-wide text-blue-700">Avg Rating</p>
            <p class="text-2xl font-bold text-blue-800 mt-1">{{ number_format($averageRating, 2) }}</p>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 mb-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Satisfaction Trend (Last 6 Months)</h2>
        <div class="relative h-48">
            <div id="feedbackTrendChart"></div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 mb-6">
        <form method="GET" action="{{ route('admin.feedback.index') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search comment, user, product" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm">
            <select name="status" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm">
                <option value="">All Status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                <option value="removed" @selected(request('status') === 'removed')>Removed</option>
            </select>
            <select name="rating" class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm">
                <option value="">Any rating</option>
                @for($rating = 5; $rating >= 1; $rating--)
                    <option value="{{ $rating }}" @selected((string) request('rating') === (string) $rating)>{{ $rating }} stars</option>
                @endfor
            </select>
            <label class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm">
                <input type="checkbox" name="flagged" value="1" @checked(request()->boolean('flagged'))>
                Flagged only
            </label>
            <div class="flex gap-2">
                <button type="submit" class="w-full rounded-xl bg-amber-300 hover:bg-amber-400 text-black font-semibold px-4 py-2.5 text-sm">Filter</button>
                <a href="{{ route('admin.feedback.index') }}" class="w-full rounded-xl border border-gray-300 px-4 py-2.5 text-sm text-center">Reset</a>
            </div>
        </form>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold">Review</th>
                        <th class="text-left px-4 py-3 font-semibold">Rating</th>
                        <th class="text-left px-4 py-3 font-semibold">Status</th>
                        <th class="text-left px-4 py-3 font-semibold">Flag</th>
                        <th class="text-left px-4 py-3 font-semibold">Moderated</th>
                        <th class="text-left px-4 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reviews as $review)
                    <tr>
                        <td class="px-4 py-4 align-top">
                            <p class="font-semibold text-gray-900">{{ $review->product->name ?? 'Unknown Product' }}</p>
                            <p class="text-xs text-gray-500">By {{ $review->user->name ?? 'Unknown User' }} ({{ $review->user->email ?? 'no-email' }})</p>
                            @if($review->comment)
                                <p class="mt-2 text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                            @else
                                <p class="mt-2 text-gray-500">No comment provided.</p>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top font-semibold text-gray-900">{{ $review->rating }}/5</td>
                        <td class="px-4 py-4 align-top">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                {{ $review->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $review->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $review->status === 'rejected' ? 'bg-red-100 text-red-700' : '' }}
                                {{ $review->status === 'removed' ? 'bg-gray-100 text-gray-700' : '' }}">
                                {{ ucfirst($review->status ?? 'pending') }}
                            </span>
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if($review->is_flagged)
                                <p class="text-xs font-semibold text-rose-600">Flagged</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $review->flag_reason ?: 'No reason provided' }}</p>
                            @else
                                <span class="text-xs text-gray-500">Not flagged</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top text-xs text-gray-500">
                            <p>{{ optional($review->moderator)->name ?: '-' }}</p>
                            <p>{{ optional($review->moderated_at)->diffForHumans() ?: '-' }}</p>
                        </td>
                        <td class="px-4 py-4 align-top">
                            <div class="grid grid-cols-2 gap-2">
                                <form method="POST" action="{{ route('admin.feedback.approve', $review) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full rounded-lg bg-green-600 hover:bg-green-500 text-white px-3 py-2 text-xs font-semibold">Approve</button>
                                </form>

                                <form method="POST" action="{{ route('admin.feedback.flag', $review) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="reason" value="Flag toggled by moderator">
                                    <button class="w-full rounded-lg bg-blue-600 hover:bg-blue-500 text-white px-3 py-2 text-xs font-semibold">{{ $review->is_flagged ? 'Unflag' : 'Flag' }}</button>
                                </form>

                                <button
                                    type="button"
                                    class="col-span-2 w-full rounded-lg bg-red-600 hover:bg-red-500 text-white px-3 py-2 text-xs font-semibold"
                                    onclick="openModerationReasonModal('{{ route('admin.feedback.reject', $review) }}', 'Reject Review', 'Please provide the reason for rejecting this review.')"
                                >
                                    Reject
                                </button>

                                <button
                                    type="button"
                                    class="col-span-2 w-full rounded-lg bg-gray-700 hover:bg-gray-600 text-white px-3 py-2 text-xs font-semibold"
                                    onclick="openModerationReasonModal('{{ route('admin.feedback.remove', $review) }}', 'Remove Review', 'Please provide the reason for removing this review from public listing.')"
                                >
                                    Remove
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-gray-500">No reviews matched your current filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-4 border-t border-gray-100">
            {{ $reviews->links() }}
        </div>
    </div>
</div>

<div id="moderationReasonModal" class="fixed inset-0 z-120 hidden" aria-labelledby="moderation-reason-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" onclick="closeModerationReasonModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-2xl bg-white border border-gray-200 shadow-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 id="moderation-reason-title" class="text-lg font-bold text-gray-900">Moderation Action</h3>
                <p id="moderation-reason-subtitle" class="text-sm text-gray-500 mt-1">Provide a reason before continuing.</p>
            </div>
            <form id="moderationReasonForm" method="POST" class="px-5 py-4 space-y-3">
                @csrf
                @method('PATCH')
                <label for="moderationReasonInput" class="block text-sm font-medium text-gray-700">Reason</label>
                <textarea id="moderationReasonInput" name="reason" rows="4" required class="w-full rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm" placeholder="Enter moderation reason"></textarea>
                <div class="flex gap-2 pt-1">
                    <button type="button" onclick="closeModerationReasonModal()" class="w-full rounded-xl border border-gray-300 px-4 py-2 text-sm">Cancel</button>
                    <button type="submit" class="w-full rounded-xl bg-amber-300 hover:bg-amber-400 text-black font-semibold px-4 py-2 text-sm">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModerationReasonModal(actionUrl, title, subtitle) {
        const modal = document.getElementById('moderationReasonModal');
        const form = document.getElementById('moderationReasonForm');
        const input = document.getElementById('moderationReasonInput');
        const modalTitle = document.getElementById('moderation-reason-title');
        const modalSubtitle = document.getElementById('moderation-reason-subtitle');

        if (!modal || !form || !input || !modalTitle || !modalSubtitle) return;

        form.action = actionUrl;
        modalTitle.textContent = title;
        modalSubtitle.textContent = subtitle;
        input.value = '';

        modal.classList.remove('hidden');
        input.focus();
    }

    function closeModerationReasonModal() {
        const modal = document.getElementById('moderationReasonModal');
        if (modal) modal.classList.add('hidden');
    }

    (function () {
        const el = document.getElementById('feedbackTrendChart');
        if (!el || typeof ApexCharts === 'undefined') return;

        const isDark = document.documentElement.classList.contains('dark');
        const reviewCounts = @json($trendReviewCounts);
        const ratings = @json($trendAverageRatings);

        new ApexCharts(el, {
            chart: {
                type: 'area',
                height: 192,
                toolbar: { show: false },
                background: 'transparent',
                animations: { enabled: false },
            },
            theme: { mode: isDark ? 'dark' : 'light' },
            series: [{ name: 'Satisfaction Trend', data: ratings }],
            xaxis: { categories: @json($trendLabels) },
            yaxis: { min: 1, max: 5, tickAmount: 4 },
            colors: ['#f59e0b'],
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 },
            },
            stroke: { curve: 'smooth', width: 2 },
            dataLabels: { enabled: false },
            markers: { size: 4 },
            legend: { show: false },
            tooltip: {
                custom: function ({ dataPointIndex }) {
                    const count = reviewCounts[dataPointIndex] ?? 0;
                    const value = ratings[dataPointIndex];
                    if (value === null) {
                        return '<div style="padding:8px 12px">No approved reviews this month</div>';
                    }
                    return `<div style="padding:8px 12px">Smoothed rating: ${value} (${count} review${count === 1 ? '' : 's'})</div>`;
                },
            },
        }).render();
    })();

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeModerationReasonModal();
        }
    });
</script>
@endsection
