<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if old columns exist and new columns don't exist
        if (Schema::hasColumn('solo_raid', 'info_node_1') && 
            !Schema::hasColumn('solo_raid', 'materi_node_1')) {
            
            // Add new materi columns
            Schema::table('solo_raid', function (Blueprint $table) {
                $table->longText('materi_node_1')->nullable();
                $table->longText('materi_node_2')->nullable();
                $table->longText('materi_node_3')->nullable();
            });
            
            // Copy data from info_node to materi_node
            DB::statement('UPDATE solo_raid SET materi_node_1 = info_node_1, materi_node_2 = info_node_2, materi_node_3 = info_node_3');
            
            // Drop old info_node columns
            Schema::table('solo_raid', function (Blueprint $table) {
                $table->dropColumn(['info_node_1', 'info_node_2', 'info_node_3']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if new columns exist and old columns don't exist
        if (Schema::hasColumn('solo_raid', 'materi_node_1') && 
            !Schema::hasColumn('solo_raid', 'info_node_1')) {
            
            // Add back info_node columns
            Schema::table('solo_raid', function (Blueprint $table) {
                $table->text('info_node_1')->nullable();
                $table->text('info_node_2')->nullable();
                $table->text('info_node_3')->nullable();
            });
            
            // Copy data back from materi_node to info_node
            DB::statement('UPDATE solo_raid SET info_node_1 = materi_node_1, info_node_2 = materi_node_2, info_node_3 = materi_node_3');
            
            // Drop materi_node columns
            Schema::table('solo_raid', function (Blueprint $table) {
                $table->dropColumn(['materi_node_1', 'materi_node_2', 'materi_node_3']);
            });
        }
    }
};
