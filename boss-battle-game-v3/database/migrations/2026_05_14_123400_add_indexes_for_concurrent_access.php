<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add composite indexes for faster lookups during concurrent access
        Schema::table('session_answer', function (Blueprint $table) {
            // Index for idempotency check in submitAnswer
            $table->index(['session_id', 'session_type', 'question_id'], 'idx_session_question_lookup');
        });

        Schema::table('session_solo', function (Blueprint $table) {
            // Index for checking active sessions
            $table->index(['user_id', 'is_pretest', 'waktu_selesai'], 'idx_active_pretest_sessions');
            $table->index(['user_id', 'solo_raid_id', 'level', 'waktu_selesai'], 'idx_active_raid_sessions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_answer', function (Blueprint $table) {
            $table->dropIndex('idx_session_question_lookup');
        });

        Schema::table('session_solo', function (Blueprint $table) {
            $table->dropIndex('idx_active_pretest_sessions');
            $table->dropIndex('idx_active_raid_sessions');
        });
    }
};
