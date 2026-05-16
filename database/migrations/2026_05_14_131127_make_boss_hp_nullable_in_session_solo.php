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
            // Make boss_hp columns nullable for pre-test sessions
            $table->integer('boss_hp_awal')->nullable()->change();
            $table->integer('boss_hp_akhir')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_solo', function (Blueprint $table) {
            // Revert to not nullable (with default value to avoid errors)
            $table->integer('boss_hp_awal')->nullable(false)->default(0)->change();
            $table->integer('boss_hp_akhir')->nullable(false)->default(0)->change();
        });
    }
};
