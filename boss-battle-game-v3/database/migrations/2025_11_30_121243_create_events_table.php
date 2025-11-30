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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('nama_event');
            $table->enum('level', ['Easy', 'Medium', 'Hard']);
            $table->date('tanggal_mulai');
            $table->time('jam_mulai');
            $table->timestamp('jam_mulai_actual')->nullable();
            $table->string('kode_event', 6)->unique();
            $table->enum('status', ['draft', 'ongoing', 'selesai'])->default('draft');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
