<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ReturnRequest extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected static function booted(): void
    {
        static::creating(function (ReturnRequest $request): void {
            if (empty($request->request_number)) {
                $request->request_number = static::generateRequestNumber();
            }
        });
    }

    protected static function generateRequestNumber(): string
    {
        do {
            $number = 'RFD-' . now()->format('ymd') . '-' . Str::upper(Str::random(6));
        } while (static::where('request_number', $number)->exists());

        return $number;
    }

    protected $fillable = [
        'request_number',
        'user_id',
        'order_id',
        'reason',
        'details',
        'proof_image_path',
        'requested_amount',
        'status',
        'admin_notes',
        'resolved_at',
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
        'resolved_at' => 'datetime',
    ];

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getDisplayRequestNumberAttribute(): string
    {
        return $this->request_number ?: ('RFD-' . str_pad((string) $this->id, 8, '0', STR_PAD_LEFT));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
