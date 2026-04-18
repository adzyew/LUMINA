<?php

use App\Console\Commands\SendDailyPromoEmails;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('promo:send-daily', function () {
    return app(SendDailyPromoEmails::class)->handle();
})->purpose('Generate / refresh the daily promo code and queue email notifications.');

Schedule::command('promo:send-daily')
    ->cron('0 */5 * * *')
    ->withoutOverlapping();
