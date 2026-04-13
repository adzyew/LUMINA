<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('return_requests', function (Blueprint $table): void {
            if (!Schema::hasColumn('return_requests', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('return_requests', 'order_id')) {
                $table->foreignId('order_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('return_requests', 'reason')) {
                $table->string('reason', 120)->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('return_requests', 'details')) {
                $table->text('details')->nullable()->after('reason');
            }
            if (!Schema::hasColumn('return_requests', 'requested_amount')) {
                $table->decimal('requested_amount', 12, 2)->nullable()->after('details');
            }
            if (!Schema::hasColumn('return_requests', 'status')) {
                $table->string('status', 30)->default('pending')->after('requested_amount');
            }
            if (!Schema::hasColumn('return_requests', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('return_requests', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('admin_notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('return_requests', function (Blueprint $table): void {
            $dropColumns = [];
            foreach ([
                'user_id',
                'order_id',
                'reason',
                'details',
                'requested_amount',
                'status',
                'admin_notes',
                'resolved_at',
            ] as $column) {
                if (Schema::hasColumn('return_requests', $column)) {
                    $dropColumns[] = $column;
                }
            }

            if (in_array('user_id', $dropColumns, true)) {
                $table->dropConstrainedForeignId('user_id');
            }
            if (in_array('order_id', $dropColumns, true)) {
                $table->dropConstrainedForeignId('order_id');
            }

            $plainColumns = array_values(array_diff($dropColumns, ['user_id', 'order_id']));
            if (!empty($plainColumns)) {
                $table->dropColumn($plainColumns);
            }
        });
    }
};

