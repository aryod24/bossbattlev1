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
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g. 'boss-novice'
            $table->string('name'); // e.g. 'Boss Novice'
            $table->string('emoji'); // e.g. '🎮'
            $table->text('description'); // e.g. 'Kalahkan 1 boss...'
            $table->string('category')->nullable(); // e.g. 'Boss', 'Event'
            $table->integer('threshold')->nullable(); // generic threshold value
            $table->boolean('is_system')->default(false); // protected from deletion
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};
