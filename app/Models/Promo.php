<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promo extends Model
{
    protected $fillable = [
        'code',
        'name',
        'discount_percent',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function claims(): HasMany
    {
        return $this->hasMany(PromoClaim::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

