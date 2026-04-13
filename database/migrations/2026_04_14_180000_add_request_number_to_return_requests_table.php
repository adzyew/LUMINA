<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('return_requests', 'request_number')) {
            Schema::table('return_requests', function (Blueprint $table): void {
                $table->string('request_number', 32)->nullable()->after('id');
            });
        }

        $rows = DB::table('return_requests')
            ->select(['id', 'created_at'])
            ->whereNull('request_number')
            ->orderBy('id')
            ->get();

        foreach ($rows as $row) {
            $datePart = $row->created_at
                ? \Illuminate\Support\Carbon::parse($row->created_at)->format('ymd')
                : now()->format('ymd');

            $requestNumber = 'RFD-' . $datePart . '-' . str_pad((string) $row->id, 6, '0', STR_PAD_LEFT);

            DB::table('return_requests')
                ->where('id', $row->id)
                ->update(['request_number' => $requestNumber]);
        }

        Schema::table('return_requests', function (Blueprint $table): void {
            if (!Schema::hasColumn('return_requests', 'request_number')) {
                return;
            }

            $table->unique('request_number', 'return_requests_request_number_unique');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('return_requests', 'request_number')) {
            return;
        }

        Schema::table('return_requests', function (Blueprint $table): void {
            $table->dropUnique('return_requests_request_number_unique');
            $table->dropColumn('request_number');
        });
    }
};

