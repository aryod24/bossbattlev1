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
        Schema::table('session_solo', function (Blueprint $table) {
            // Composite index untuk query badge checking
            $table->index(['user_id', 'boss_kalah'], 'idx_user_boss_kalah');
            
            // Index untuk level + boss_kalah
            $table->index(['level', 'boss_kalah'], 'idx_level_boss_kalah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_solo', function (Blueprint $table) {
            $table->dropIndex('idx_user_boss_kalah');
            $table->dropIndex('idx_level_boss_kalah');
        });
    }
};
