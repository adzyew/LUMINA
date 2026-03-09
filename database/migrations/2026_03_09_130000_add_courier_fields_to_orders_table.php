<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('courier_name', 100)->nullable()->after('tracking_number');
            $table->string('tracking_url', 500)->nullable()->after('courier_name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn(['courier_name', 'tracking_url']);
        });
    }
};
