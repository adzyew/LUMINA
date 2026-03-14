<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('status')->default('approved')->after('comment');
            $table->boolean('is_flagged')->default(false)->after('status');
            $table->string('flag_reason')->nullable()->after('is_flagged');
            $table->foreignId('moderated_by')->nullable()->after('flag_reason')->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            $table->text('moderation_reason')->nullable()->after('moderated_at');

            $table->index('status');
            $table->index('is_flagged');
            $table->index(['status', 'is_flagged']);
        });

        Schema::create('review_moderation_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained('reviews')->cascadeOnDelete();
            $table->foreignId('moderator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_moderation_logs');

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['is_flagged']);
            $table->dropIndex(['status', 'is_flagged']);

            $table->dropConstrainedForeignId('moderated_by');
            $table->dropColumn([
                'status',
                'is_flagged',
                'flag_reason',
                'moderated_at',
                'moderation_reason',
            ]);
        });
    }
};
