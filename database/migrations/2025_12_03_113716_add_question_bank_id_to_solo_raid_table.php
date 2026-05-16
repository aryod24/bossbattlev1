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
        Schema::table('solo_raid', function (Blueprint $table) {
            $table->unsignedTinyInteger('question_bank_id')->default(1)->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solo_raid', function (Blueprint $table) {
            $table->dropColumn('question_bank_id');
        });
    }
};
