<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\QuestionBank;

class FreshSeedQuestions extends Command
{
    protected $signature = 'questions:fresh-seed';
    protected $description = 'Delete all questions and re-seed from all question bank seeders';

    public function handle()
    {
        $this->info('🗑️  Deleting all questions from question_bank table...');
        
        // Disable foreign key checks temporarily
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        QuestionBank::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $this->line('✓ Cleared question_bank table');

        $seeders = [
            'QuestionBankPreTestSeeder',
            'QuestionBankEasySeeder',
            'QuestionBankBossSeeder',
            'QuestionBankMediumSeeder',
            'QuestionBankBossMediumSeeder',
            'QuestionBankHardSeeder',
            'QuestionBankBossHardSeeder',
        ];

        $count = count($seeders);
        $this->info("\n📚 Seeding {$count} question bank(s)...\n");

        foreach ($seeders as $seeder) {
            $this->call('db:seed', ['--class' => $seeder]);
        }

        $total = QuestionBank::count();
        $this->info("\n✓ Done! Total questions in database: {$total}");

        return 0;
    }
}
