<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('payment_channel', 50)->nullable()->after('payment_method');
        });

        DB::table('orders')
            ->where('payment_method', 'cod')
            ->update(['payment_channel' => 'cod']);

        DB::table('orders')
            ->where('payment_method', 'paymongo')
            ->whereNull('payment_channel')
            ->update(['payment_channel' => 'online']);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn('payment_channel');
        });
    }
};