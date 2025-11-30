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
        Schema::create('solo_raids', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->constrained('users');
            $table->text('info_node_1')->nullable();
            $table->text('info_node_2')->nullable();
            $table->text('info_node_3')->nullable();
            $table->string('boss_easy_name')->default('Goblin King');
            $table->string('boss_medium_name')->default('Orc Warlord');
            $table->string('boss_hard_name')->default('Dragon Lord');
            $table->boolean('easy_enabled')->default(true);
            $table->boolean('medium_enabled')->default(false);
            $table->boolean('hard_enabled')->default(false);
            $table->date('easy_start_date')->nullable();
            $table->date('easy_end_date')->nullable();
            $table->date('medium_start_date')->nullable();
            $table->date('medium_end_date')->nullable();
            $table->date('hard_start_date')->nullable();
            $table->date('hard_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solo_raids');
    }
};
