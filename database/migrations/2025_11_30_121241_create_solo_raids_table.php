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
        Schema::create('solo_raid', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['draft', 'active', 'selesai'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            // Info nodes
            $table->text('info_node_1')->nullable();
            $table->text('info_node_2')->nullable();
            $table->text('info_node_3')->nullable();
            
            // Boss names
            $table->string('boss_easy_name', 50)->default('Goblin King');
            $table->string('boss_medium_name', 50)->default('Dragon Lord');
            $table->string('boss_hard_name', 50)->default('Demon Emperor');
            
            // Level toggles
            $table->boolean('easy_enabled')->default(true);
            $table->boolean('medium_enabled')->default(true);
            $table->boolean('hard_enabled')->default(true);
            
            // Optional: different dates per level
            $table->date('easy_date_start')->nullable();
            $table->date('easy_date_end')->nullable();
            $table->date('medium_date_start')->nullable();
            $table->date('medium_date_end')->nullable();
            $table->date('hard_date_start')->nullable();
            $table->date('hard_date_end')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'tanggal_mulai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solo_raid');
    }
};
