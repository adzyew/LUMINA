<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_order_updates')->default(true)->after('points_balance');
            $table->boolean('notify_promotions')->default(false)->after('notify_order_updates');
            $table->boolean('notify_loyalty')->default(false)->after('notify_promotions');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['notify_order_updates', 'notify_promotions', 'notify_loyalty']);
        });
    }
};
