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
        Schema::create('raid_nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solo_raid_id')->constrained('solo_raid')->onDelete('cascade');
            $table->enum('type', ['content', 'quiz']); // content = materi, quiz = quiz akhir
            $table->string('title', 150);
            $table->longText('content')->nullable(); // Rich text materi, nullable for quiz nodes
            $table->integer('order')->default(1); // 1-6 ordering within an event
            $table->timestamps();

            // Indexes
            $table->index(['solo_raid_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raid_nodes');
    }
};
