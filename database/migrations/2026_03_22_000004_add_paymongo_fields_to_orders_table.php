<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('payment_status', 30)->default('pending')->after('payment_method');
            $table->string('paymongo_checkout_session_id', 120)->nullable()->after('payment_status');
            $table->string('paymongo_payment_intent_id', 120)->nullable()->after('paymongo_checkout_session_id');
            $table->string('paymongo_reference', 120)->nullable()->after('paymongo_payment_intent_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn([
                'payment_status',
                'paymongo_checkout_session_id',
                'paymongo_payment_intent_id',
                'paymongo_reference',
            ]);
        });
    }
};
