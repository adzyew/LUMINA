<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table): void {
            if (!Schema::hasColumn('return_requests', 'proof_image_path')) {
                $table->string('proof_image_path')->nullable()->after('details');
            }
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table): void {
            if (Schema::hasColumn('return_requests', 'proof_image_path')) {
                $table->dropColumn('proof_image_path');
            }
        });
    }
};

