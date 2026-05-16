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
        Schema::create('event_participant', function (Blueprint $table) {
            $table->id('event_participant_id');
            $table->foreignId('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Timing
            $table->datetime('waktu_mulai')->nullable();
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
            $table->integer('peringkat_leaderboard')->nullable();
            
            // Status
            $table->enum('status', ['joined', 'in_progress', 'finished'])->default('joined');
            
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['event_id', 'user_id']);
            
            // Indexes
            $table->index(['event_id', 'peringkat_leaderboard']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_participant');
    }
};
