<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('contact_phone', 20)->nullable()->after('shipping_address');
            $table->string('shipping_street', 255)->nullable()->after('contact_phone');
            $table->string('shipping_city', 100)->nullable()->after('shipping_street');
            $table->string('shipping_province', 100)->nullable()->after('shipping_city');
            $table->string('shipping_postal_code', 20)->nullable()->after('shipping_province');
            $table->string('shipping_country', 100)->nullable()->after('shipping_postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'contact_phone',
                'shipping_street',
                'shipping_city',
                'shipping_province',
                'shipping_postal_code',
                'shipping_country',
            ]);
        });
    }
};
