<?php

namespace App\Console\Commands;

use App\Mail\DailyPromoCodeMail;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class SendDailyPromoEmails extends Command
{
    protected $signature = 'promo:send-daily';

    protected $description = 'Generate / refresh active promo code and email it to verified users.';

    public function handle(): int
    {
        $promoCode = strtoupper((string) config('services.promo.daily_code', 'LUMIPRO'));
        $discountPercent = (float) config('services.promo.daily_discount_percent', 5);
        $discountPercent = max(5.0, $discountPercent);
        $expiresAt = now()->addHours(24);

        $promo = Promo::query()->updateOrCreate(
            ['code' => $promoCode],
            [
                'name' => 'Daily Promo',
                'discount_percent' => $discountPercent,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => $expiresAt,
            ]
        );

        $usersQuery = User::query()
            ->whereNotNull('email_verified_at');

        if (Schema::hasColumn('users', 'archived_at')) {
            $usersQuery->whereNull('archived_at');
        }

        $sent = 0;

        $usersQuery->select(['id', 'first_name', 'email'])
            ->orderBy('id')
            ->chunkById(200, function ($users) use ($promo, &$sent): void {
                foreach ($users as $user) {
                    Mail::to($user->email)->queue(new DailyPromoCodeMail($promo, $user));
                    $sent++;
                }
            });

        $this->info("Promo {$promo->code} ({$promo->discount_percent}%) queued to {$sent} verified users.");

        return self::SUCCESS;
    }
}

