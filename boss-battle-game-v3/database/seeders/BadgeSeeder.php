<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            [
                'id' => 1,
                'slug' => 'boss-novice',
                'name' => 'Boss Novice',
                'emoji' => '🎮',
                'description' => 'Kalahkan 1 boss level apapun (Solo/Event)',
                'is_system' => true,
            ],
            [
                'id' => 2,
                'slug' => 'boss-veteran',
                'name' => 'Boss Veteran',
                'emoji' => '⭐',
                'description' => 'Kalahkan Boss di 3 level Solo Raid (Easy, Medium, Hard)',
                'is_system' => true,
            ],
            [
                'id' => 3,
                'slug' => 'top-3-challenger',
                'name' => 'Top 3 Challenger',
                'emoji' => '🏆',
                'description' => 'Raih peringkat 1-3 di Event Multiplayer',
                'is_system' => true,
            ],
            [
                'id' => 4,
                'slug' => 'perfect-strike',
                'name' => 'Perfect Strike',
                'emoji' => '💯',
                'description' => 'Jawab 100% benar di satu sesi game',
                'is_system' => true,
            ],
            [
                'id' => 5,
                'slug' => 'event-warrior',
                'name' => 'Event Warrior',
                'emoji' => '⚔️',
                'description' => 'Berpartisipasi dalam minimal 2 Event Multiplayer',
                'is_system' => true,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['slug' => $badge['slug']], // Check by slug
                $badge
            );
        }
    }
}
