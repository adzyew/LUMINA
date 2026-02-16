<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->decimal('total_price', 12, 2)->default(0)->after('user_id');
            $table->string('status')->default('pending')->after('total_price'); // pending, confirmed, processing, shipped, delivered, cancelled
            $table->string('tracking_number')->nullable()->after('status');
            $table->text('shipping_address')->nullable()->after('tracking_number');
            $table->text('notes')->nullable()->after('shipping_address');
            $table->timestamp('shipped_at')->nullable()->after('notes');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['total_price', 'status', 'tracking_number', 'shipping_address', 'notes', 'shipped_at', 'delivered_at']);
        });
    }
};
