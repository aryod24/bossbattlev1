<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('question_bank', function (Blueprint $table) {
            $table->string('bank_name', 255)->nullable()->after('bank_group');
            $table->string('bank_icon', 50)->nullable()->after('bank_name');
            $table->text('bank_description')->nullable()->after('bank_icon');
            
            $table->index('bank_name');
        });

        // Populate existing questions with metadata from config
        $bankConfig = config('question_banks.banks', [
            1 => ['name' => 'PHP Basics', 'icon' => 'code', 'description' => 'Fundamental PHP concepts, syntax, and basic operations'],
            2 => ['name' => 'PHP Advanced', 'icon' => 'code_blocks', 'description' => 'OOP, Laravel framework, and advanced PHP topics'],
            3 => ['name' => 'JavaScript', 'icon' => 'javascript', 'description' => 'ES6+, DOM manipulation, and async programming'],
        ]);

        foreach ($bankConfig as $bankId => $metadata) {
            DB::table('question_bank')
                ->where('bank_group', $bankId)
                ->update([
                    'bank_name' => $metadata['name'],
                    'bank_icon' => $metadata['icon'],
                    'bank_description' => $metadata['description'] ?? '',
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_bank', function (Blueprint $table) {
            $table->dropIndex(['bank_name']);
            $table->dropColumn(['bank_name', 'bank_icon', 'bank_description']);
        });
    }
};
