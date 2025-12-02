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
        Schema::create('session_answer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');  // Could be session_solo.id or event_participant_id
            $table->enum('session_type', ['solo', 'event']);
            $table->foreignId('question_id')->constrained('question_bank')->onDelete('cascade');
            
            $table->integer('urutan_soal');  // 1, 2, 3, ...
            $table->text('jawaban_user')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('waktu_jawab_detik')->nullable();  // time spent on this question
            
            // Research tracking
            $table->integer('attempt_number')->nullable();
            $table->boolean('is_counted_research')->default(false);
            
            $table->datetime('answered_at');
            $table->timestamps();
            
            // Indexes
            $table->index(['session_id', 'session_type']);
            $table->index('is_counted_research');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_answer');
    }
};
