<?php

namespace Database\Seeders;

use App\Models\User;
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
            QuestionBankSeeder::class,   // Bank 1: PHP Basics (74 questions)
            QuestionBankSeeder2::class,  // Bank 2: PHP Advanced (75 questions)
            QuestionBankSeeder3::class,  // Bank 3: JavaScript (75 questions)
        ]);
    }
}
