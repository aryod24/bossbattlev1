<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Bersih-bersih:
     * - Drop tabel `event_participant` & `events` (fitur multiplayer dipindah/digabung ke solo_raid).
     * - Drop kolom-kolom di `solo_raid` yang sudah tidak dipakai:
     *   boss_*_name, *_enabled, *_date_start, *_date_end.
     * Nama boss kini ditentukan dari `section` lewat konstanta di SoloRaid model.
     */
    public function up(): void
    {
        // 1. Drop tabel event_participant dulu (ada FK ke events)
        Schema::dropIfExists('event_participant');

        // 2. Drop tabel events
        Schema::dropIfExists('events');

        // 3. Drop kolom-kolom solo_raid yang tidak dipakai
        Schema::table('solo_raid', function (Blueprint $table) {
            $columns = [
                'boss_easy_name', 'boss_medium_name', 'boss_hard_name',
                'easy_enabled', 'medium_enabled', 'hard_enabled',
                'easy_date_start', 'easy_date_end',
                'medium_date_start', 'medium_date_end',
                'hard_date_start', 'hard_date_end',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('solo_raid', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom solo_raid
        Schema::table('solo_raid', function (Blueprint $table) {
            $table->string('boss_easy_name', 50)->default('Goblin King');
            $table->string('boss_medium_name', 50)->default('Dragon Lord');
            $table->string('boss_hard_name', 50)->default('Demon Emperor');
            $table->boolean('easy_enabled')->default(true);
            $table->boolean('medium_enabled')->default(true);
            $table->boolean('hard_enabled')->default(true);
            $table->date('easy_date_start')->nullable();
            $table->date('easy_date_end')->nullable();
            $table->date('medium_date_start')->nullable();
            $table->date('medium_date_end')->nullable();
            $table->date('hard_date_start')->nullable();
            $table->date('hard_date_end')->nullable();
        });

        // Kembalikan tabel events
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('nama_event', 100);
            $table->enum('level', ['Easy', 'Medium', 'Hard']);
            $table->date('tanggal_mulai');
            $table->time('jam_mulai');
            $table->datetime('jam_mulai_actual')->nullable();
            $table->string('kode_event', 6)->unique();
            $table->enum('status', ['draft', 'ongoing', 'selesai'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->index(['status', 'tanggal_mulai']);
            $table->index('kode_event');
        });

        // Kembalikan tabel event_participant
        Schema::create('event_participant', function (Blueprint $table) {
            $table->id('event_participant_id');
            $table->foreignId('event_id')->references('event_id')->on('events')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable();
            $table->integer('durasi_detik')->nullable();
            $table->integer('jumlah_soal');
            $table->integer('jumlah_benar')->default(0);
            $table->integer('jumlah_salah')->default(0);
            $table->integer('boss_hp_awal');
            $table->integer('boss_hp_akhir');
            $table->boolean('boss_kalah')->default(false);
            $table->decimal('skor_akhir', 5, 2)->nullable();
            $table->integer('xp_diperoleh')->default(0);
            $table->integer('peringkat_leaderboard')->nullable();
            $table->enum('status', ['joined', 'in_progress', 'finished'])->default('joined');
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
            $table->index(['event_id', 'peringkat_leaderboard']);
        });
    }
};
