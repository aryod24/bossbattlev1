<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SoloRaidSeeder::class,
            
            // Pre-Test Questions (Bank Group 0)
            QuestionBankPreTestSeeder::class,
            
            // Bank Group 1: PHP Basics
            QuestionBankEasySeeder::class,
            QuestionBankBossSeeder::class,
            
            // Bank Group 2: Functions & Arrays
            QuestionBankMediumSeeder::class,
            QuestionBankBossMediumSeeder::class,
            
            // Bank Group 3: OOP & Database
            QuestionBankHardSeeder::class,
            QuestionBankBossHardSeeder::class,
            
            BadgeSeeder::class,
        ]);
    }
}
