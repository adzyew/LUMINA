<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('shipping_street')->nullable()->after('phone');
            $table->string('shipping_city')->nullable()->after('shipping_street');
            $table->string('shipping_province')->nullable()->after('shipping_city');
            $table->string('shipping_postal_code')->nullable()->after('shipping_province');
            $table->string('shipping_country')->nullable()->after('shipping_postal_code');
            $table->text('shipping_address')->nullable()->after('shipping_country');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_street',
                'shipping_city',
                'shipping_province',
                'shipping_postal_code',
                'shipping_country',
                'shipping_address',
            ]);
        });
    }
};
