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
        Schema::table('solo_raid', function (Blueprint $table) {
            $table->enum('type', ['learning', 'boss'])->default('boss')->after('status');
            $table->enum('section', ['Easy', 'Medium', 'Hard'])->nullable()->after('type');
            $table->integer('section_order')->default(1)->after('section'); // 1-6 ordering within a section
        });

        // Drop old materi columns (data migrated to raid_nodes)
        if (Schema::hasColumn('solo_raid', 'materi_node_1')) {
            Schema::table('solo_raid', function (Blueprint $table) {
                $table->dropColumn(['materi_node_1', 'materi_node_2', 'materi_node_3']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solo_raid', function (Blueprint $table) {
            $table->dropColumn(['type', 'section', 'section_order']);
        });

        // Restore materi columns
        Schema::table('solo_raid', function (Blueprint $table) {
            $table->longText('materi_node_1')->nullable();
            $table->longText('materi_node_2')->nullable();
            $table->longText('materi_node_3')->nullable();
        });
    }
};
