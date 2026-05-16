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
            // Composite index untuk query yang sering dipakai
            $table->index(['status', 'section', 'section_order'], 'idx_status_section_order');
            
            // Index untuk filter by section saja
            $table->index('section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solo_raid', function (Blueprint $table) {
            $table->dropIndex('idx_status_section_order');
            $table->dropIndex(['section']);
        });
    }
};
