<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    private const FLAG_TERMS = [
        'spam',
        'scam',
        'fake',
        'hate',
        'offensive',
        'irrelevant',
    ];

    public function store(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to submit a review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $comment = (string) $request->input('comment', '');
        $flaggedTerm = $this->detectFlaggedTerm($comment);
        $isFlagged = $flaggedTerm !== null;

        $status = 'pending';
        $flagReason = $isFlagged ? 'Contains potentially inappropriate keyword: ' . $flaggedTerm : null;

        Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ],
            [
                'rating' => $request->rating,
                'comment' => $comment,
                'status' => $status,
                'is_flagged' => $isFlagged,
                'flag_reason' => $flagReason,
                'moderated_by' => null,
                'moderated_at' => null,
                'moderation_reason' => null,
            ]
        );

        return back()->with('success', 'Thank you for your review! It is pending moderation.');
    }

    private function detectFlaggedTerm(string $comment): ?string
    {
        $normalized = strtolower($comment);

        foreach (self::FLAG_TERMS as $term) {
            if (str_contains($normalized, $term)) {
                return $term;
            }
        }

        return null;
    }
}
