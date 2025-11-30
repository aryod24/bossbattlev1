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
        Schema::create('session_solos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('solo_raid_id')->constrained('solo_raids');
            $table->enum('level', ['Easy', 'Medium', 'Hard']);
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi_detik')->default(0);
            $table->integer('jumlah_soal')->default(0);
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            $table->boolean('boss_kalah')->default(false);
            $table->integer('skor_akhir')->default(0);
            $table->integer('xp_diperoleh')->default(0);
            $table->boolean('is_first_attempt')->default(false);
            $table->integer('attempt_number')->default(1);
            $table->boolean('is_counted_research')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_solos');
    }
};
