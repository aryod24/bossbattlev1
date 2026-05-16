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
        Schema::table('user_node_completions', function (Blueprint $table) {
            // Composite index untuk query yang sering dipakai
            $table->index(['user_id', 'raid_node_id'], 'idx_user_raid_node');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_node_completions', function (Blueprint $table) {
            $table->dropIndex('idx_user_raid_node');
        });
    }
};
