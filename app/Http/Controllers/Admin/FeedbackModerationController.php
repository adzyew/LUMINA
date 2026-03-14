<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FeedbackModerationController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::query()->with(['user:id,name,email', 'product:id,name', 'moderator:id,name']);

        if ($request->filled('status')) {
            $allowedStatuses = ['pending', 'approved', 'rejected', 'removed'];
            if (in_array($request->string('status')->toString(), $allowedStatuses, true)) {
                $query->where('status', $request->string('status')->toString());
            }
        }

        if ($request->boolean('flagged')) {
            $query->where('is_flagged', true);
        }

        if ($request->filled('rating')) {
            $rating = (int) $request->input('rating');
            if ($rating >= 1 && $rating <= 5) {
                $query->where('rating', $rating);
            }
        }

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));
            $query->where(function ($builder) use ($search) {
                $builder->where('comment', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $totalReviews = Review::count();
        $pendingReviews = Review::where('status', 'pending')->count();
        $flaggedReviews = Review::where('is_flagged', true)->count();
        $approvedReviews = Review::where('status', 'approved')->count();
        $averageRating = (float) (Review::where('status', 'approved')->avg('rating') ?? 0);
        $approvalRate = $totalReviews > 0 ? round(($approvedReviews / $totalReviews) * 100, 1) : 0;

        $trendLabels = [];
        $trendAverageRatings = [];
        $trendReviewCounts = [];
        $rawTrendAverageRatings = [];

        $startMonth = Carbon::now()->subMonths(5)->startOfMonth();
        $endMonth = Carbon::now()->endOfMonth();

        $monthlyAverages = Review::query()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, AVG(rating) as avg_rating, COUNT(*) as review_count')
            ->where('status', 'approved')
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(function ($row) {
                return $row->year . '-' . $row->month;
            });

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $key = $month->year . '-' . $month->month;
            $monthData = $monthlyAverages->get($key);

            $trendLabels[] = $month->format('M Y');
            $trendReviewCounts[] = (int) ($monthData->review_count ?? 0);
            $rawTrendAverageRatings[] = $monthData ? round((float) $monthData->avg_rating, 2) : null;
        }

        // Build a stable trend line: smooth with a trailing 3-point average and carry
        // forward the last known value for empty months.
        $defaultTrendValue = $averageRating > 0 ? round($averageRating, 2) : 3.0;
        $lastStableValue = $defaultTrendValue;

        foreach ($rawTrendAverageRatings as $index => $rawValue) {
            if ($rawValue === null) {
                $trendAverageRatings[] = $lastStableValue;
                continue;
            }

            $window = [];
            for ($j = max(0, $index - 2); $j <= $index; $j++) {
                if ($rawTrendAverageRatings[$j] !== null) {
                    $window[] = $rawTrendAverageRatings[$j];
                }
            }

            $stableValue = count($window) > 0
                ? round(array_sum($window) / count($window), 2)
                : $rawValue;

            $trendAverageRatings[] = $stableValue;
            $lastStableValue = $stableValue;
        }

        return view('admin.feedback.index', compact(
            'reviews',
            'totalReviews',
            'pendingReviews',
            'flaggedReviews',
            'approvedReviews',
            'averageRating',
            'approvalRate',
            'trendLabels',
            'trendAverageRatings',
            'trendReviewCounts'
        ));
    }

    public function approve(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $this->moderate($review, 'approved', $request->input('reason'));

        return back()->with('success', 'Review approved successfully.');
    }

    public function reject(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->moderate($review, 'rejected', $request->input('reason'));

        return back()->with('success', 'Review rejected.');
    }

    public function remove(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $this->moderate($review, 'removed', $request->input('reason'));

        return back()->with('success', 'Review removed from public listing.');
    }

    public function flag(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $isFlagged = !$review->is_flagged;

        $review->update([
            'is_flagged' => $isFlagged,
            'flag_reason' => $isFlagged ? ($request->input('reason') ?: 'Flagged by moderator') : null,
        ]);

        $this->logAction($review, $isFlagged ? 'flagged' : 'unflagged', $request->input('reason'));

        return back()->with('success', $isFlagged ? 'Review flagged for follow-up.' : 'Review unflagged.');
    }

    private function moderate(Review $review, string $status, ?string $reason): void
    {
        $review->update([
            'status' => $status,
            'moderated_by' => Auth::id(),
            'moderated_at' => now(),
            'moderation_reason' => $reason,
        ]);

        $this->logAction($review, $status, $reason);
    }

    private function logAction(Review $review, string $action, ?string $reason): void
    {
        DB::table('review_moderation_logs')->insert([
            'review_id' => $review->id,
            'moderator_id' => Auth::id(),
            'action' => $action,
            'reason' => $reason,
            'created_at' => now(),
        ]);
    }
}
