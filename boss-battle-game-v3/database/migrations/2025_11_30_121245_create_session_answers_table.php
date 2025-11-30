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
        Schema::create('session_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->string('session_type'); // 'solo' or 'event'
            $table->foreignId('question_id')->constrained('question_banks');
            $table->integer('urutan_soal');
            $table->string('jawaban_user')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->integer('waktu_jawab_detik')->default(0);
            $table->integer('attempt_number')->default(1);
            $table->boolean('is_counted_research')->default(false);
            $table->timestamp('answered_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_answers');
    }
};
