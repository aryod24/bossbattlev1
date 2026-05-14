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
        // Add unique constraint to prevent duplicate answers for same question in same session
        // This provides database-level protection against race conditions
        Schema::table('session_answer', function (Blueprint $table) {
            $table->unique(['session_id', 'question_id'], 'unique_session_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_answer', function (Blueprint $table) {
            $table->dropUnique('unique_session_question');
        });
    }
};
