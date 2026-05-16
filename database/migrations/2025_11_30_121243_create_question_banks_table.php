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
        Schema::create('question_bank', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['Easy', 'Medium', 'Hard']);
            $table->text('soal_text');
            $table->enum('tipe', ['multiple_choice', 'short_answer']);
            
            // For multiple choice (nullable for short answer)
            $table->string('pilihan_a', 255)->nullable();
            $table->string('pilihan_b', 255)->nullable();
            $table->string('pilihan_c', 255)->nullable();
            $table->string('pilihan_d', 255)->nullable();
            
            $table->string('jawaban_benar', 255);  // 'A'/'B'/'C'/'D' or text
            $table->integer('bobot_xp')->default(10);  // 10/15/20 per level
            $table->timestamps();
            
            // Indexes
            $table->index(['level', 'tipe']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_bank');
    }
};
