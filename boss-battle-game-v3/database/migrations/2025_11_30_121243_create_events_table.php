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
            $table->id('event_id');
            $table->string('nama_event', 100);
            $table->enum('level', ['Easy', 'Medium', 'Hard']);  // ONLY 1 level per event
            $table->date('tanggal_mulai');
            $table->time('jam_mulai');
            $table->datetime('jam_mulai_actual')->nullable();  // actual start time
            $table->string('kode_event', 6)->unique();  // "ABC123"
            $table->enum('status', ['draft', 'ongoing', 'selesai'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'tanggal_mulai']);
            $table->index('kode_event');
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
