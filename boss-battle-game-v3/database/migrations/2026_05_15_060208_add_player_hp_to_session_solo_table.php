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
            $table->integer('player_hp_awal')->nullable()->after('boss_hp_akhir');
            $table->integer('player_hp_akhir')->nullable()->after('player_hp_awal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_solo', function (Blueprint $table) {
            $table->dropColumn(['player_hp_awal', 'player_hp_akhir']);
        });
    }
};
