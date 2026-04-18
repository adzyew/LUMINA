<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (!Schema::hasColumn('orders', 'promo_id')) {
                $table->foreignId('promo_id')->nullable()->after('user_id')->constrained('promos')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'promo_code')) {
                $table->string('promo_code')->nullable()->after('discount_amount');
            }
            if (!Schema::hasColumn('orders', 'promo_discount_percent')) {
                $table->decimal('promo_discount_percent', 5, 2)->nullable()->after('promo_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            if (Schema::hasColumn('orders', 'promo_id')) {
                $table->dropConstrainedForeignId('promo_id');
            }
            if (Schema::hasColumn('orders', 'promo_code')) {
                $table->dropColumn('promo_code');
            }
            if (Schema::hasColumn('orders', 'promo_discount_percent')) {
                $table->dropColumn('promo_discount_percent');
            }
        });
    }
};

