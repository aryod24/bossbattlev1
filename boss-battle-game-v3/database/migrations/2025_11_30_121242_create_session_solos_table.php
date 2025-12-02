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
        Schema::create('session_solo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('solo_raid_id')->constrained('solo_raid')->onDelete('cascade');
            $table->enum('level', ['Easy', 'Medium', 'Hard']);
            
            // Timing
            $table->datetime('waktu_mulai');
            $table->datetime('waktu_selesai')->nullable();
            $table->integer('durasi_detik')->nullable();
            
            // Quiz results
            $table->integer('jumlah_soal');
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            
            // Boss battle
            $table->integer('boss_hp_awal');
            $table->integer('boss_hp_akhir');
            $table->boolean('boss_kalah')->default(false);
            
            // Scoring
            $table->decimal('skor_akhir', 5, 2)->nullable();
            $table->integer('xp_diperoleh')->default(0);
            
            // Research tracking
            $table->integer('attempt_number')->default(1);
            $table->boolean('is_counted_research')->default(false);
            $table->boolean('is_first_attempt')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'solo_raid_id']);
            $table->index(['is_counted_research', 'attempt_number']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_solo');
    }
};
