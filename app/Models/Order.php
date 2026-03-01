<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'points_used',
        'discount_amount',
        'status',
        'tracking_number',
        'shipping_address',
        'contact_phone',
        'shipping_street',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'shipping_country',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    /**
     * Get formatted shipping address (detailed or legacy).
     */
    public function getFormattedShippingAddressAttribute(): string
    {
        if ($this->shipping_street || $this->shipping_city) {
            $parts = array_filter([
                $this->shipping_street,
                $this->shipping_city,
                $this->shipping_province,
                $this->shipping_postal_code,
                $this->shipping_country,
            ]);
            return implode(', ', $parts) ?: ($this->shipping_address ?? '');
        }
        return $this->shipping_address ?? '';
    }

    protected $casts = [
        'total_price' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
