<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Order extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Order $order): void {
            if (empty($order->tracking_number)) {
                $order->tracking_number = static::generateTrackingNumber();
            }
            if (empty($order->order_number)) {
                $order->order_number = static::generateOrderNumber();
            }
        });
    }

    protected static function generateTrackingNumber(): string
    {
        do {
            $trackingNumber = 'TRK-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8));
        } while (static::where('tracking_number', $trackingNumber)->exists());

        return $trackingNumber;
    }

    protected static function generateOrderNumber(): string
    {
        do {
            $orderNumber = now()->format('ymd') . Str::upper(Str::random(8));
        } while (static::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    protected $fillable = [
        'order_number',
        'user_id',
        'total_price',
        'points_used',
        'discount_amount',
        'status',
        'tracking_number',
        'courier_name',
        'tracking_url',
        'payment_method',
        'payment_channel',
        'payment_status',
        'paymongo_checkout_session_id',
        'paymongo_payment_intent_id',
        'paymongo_reference',
        'shipping_address',
        'contact_phone',
        'contact_email',
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

    public function getDisplayOrderNumberAttribute(): string
    {
        return $this->order_number ?: (string) $this->id;
    }

    public function getPaymentChannelLabelAttribute(): string
    {
        if ($this->payment_method === 'cod') {
            return 'COD';
        }

        $channel = (string) ($this->payment_channel ?? '');
        if ($channel === '' || $channel === 'online') {
            return 'Online';
        }

        return self::formatPaymentChannel($channel);
    }

    public function getPaymentDisplayAttribute(): string
    {
        if ($this->payment_method === 'cod') {
            return 'Cash on Delivery';
        }

        $channel = (string) ($this->payment_channel ?? '');
        if ($channel === '' || $channel === 'online') {
            return 'Online Payment';
        }

        return 'Online (' . self::formatPaymentChannel($channel) . ')';
    }

    private static function formatPaymentChannel(string $channel): string
    {
        $normalized = strtolower(trim($channel));

        return match ($normalized) {
            'cod' => 'COD',
            'gcash' => 'GCash',
            'paymaya', 'maya' => 'Maya',
            'grab_pay', 'grabpay' => 'GrabPay',
            'card' => 'Card',
            'dob' => 'Online Banking',
            default => ucfirst(str_replace('_', ' ', $normalized)),
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function returnRequests(): HasMany
    {
        return $this->hasMany(ReturnRequest::class);
    }

    public function courierFeedback(): HasOne
    {
        return $this->hasOne(CourierFeedback::class);
    }
}
