<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')
            ->select('id')
            ->where(function ($query): void {
                $query->whereNull('tracking_number')
                    ->orWhere('tracking_number', '');
            })
            ->orderBy('id')
            ->chunkById(200, function ($orders): void {
                foreach ($orders as $order) {
                    do {
                        $trackingNumber = 'TRK-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8));
                    } while (
                        DB::table('orders')
                            ->where('tracking_number', $trackingNumber)
                            ->exists()
                    );

                    DB::table('orders')
                        ->where('id', $order->id)
                        ->update(['tracking_number' => $trackingNumber]);
                }
            });
    }

    public function down(): void
    {
        // Irreversible data migration: keep generated tracking numbers.
    }
};
