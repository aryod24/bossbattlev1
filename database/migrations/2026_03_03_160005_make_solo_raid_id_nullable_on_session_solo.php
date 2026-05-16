<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('session_solo', function (Blueprint $table) {
            // Drop existing FK, make nullable, then re-add FK
            $table->dropForeign(['solo_raid_id']);
            $table->unsignedBigInteger('solo_raid_id')->nullable()->change();
            $table->foreign('solo_raid_id')->references('id')->on('solo_raid')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('session_solo', function (Blueprint $table) {
            $table->dropForeign(['solo_raid_id']);
            $table->unsignedBigInteger('solo_raid_id')->nullable(false)->change();
            $table->foreign('solo_raid_id')->references('id')->on('solo_raid')->cascadeOnDelete();
        });
    }
};
