<?php

namespace App\Models;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'profile_photo_url',
        'profile_photo_public_id',
        'provider_id',
        'provider_name',
        'provider_token',
        'provider_refresh_token',
        // Shipping defaults
        'shipping_street',
        'shipping_secondary_address',
        'shipping_city',
        'shipping_region',
        'shipping_barangay',
        'shipping_province',
        'shipping_postal_code',
        'shipping_country',
        'shipping_address',
        'notify_order_updates',
        'notify_promotions',
        'notify_loyalty',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'provider_token',
        'provider_refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'archived_at' => 'datetime',
            'notify_order_updates' => 'boolean',
            'notify_promotions' => 'boolean',
            'notify_loyalty' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(\App\Models\Order::class);
    }

    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'wishlists')->withTimestamps();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function isPrivilegedStaff(): bool
    {
        return (bool) ($this->is_admin ?? false)
            || $this->hasRole('admin')
            || $this->hasRole('staff')
            || $this->can('inventory.view')
            || $this->can('sales.view')
            || $this->can('deliveries.manage')
            || $this->can('reviews.moderate');
    }
}
