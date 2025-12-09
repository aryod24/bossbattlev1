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
                // Legacy logic handles complex OR condition (Solo OR Event win)
                'requirements' => null, 
            ],
            [
                'id' => 2,
                'slug' => 'boss-veteran', 
                // User asked for "Boss Slayer" but "Boss Veteran" exists with this logic. 
                // I'll keep slug consistent but update name if needed, or just add requirements.
                // User said "is boss defeated 3 easy medium hard = Boss Slayer". 
                // I will rename it to align with user request.
                'name' => 'Boss Slayer', 
                'emoji' => '⭐',
                'description' => 'Kalahkan Boss di 3 level Solo Raid (Easy, Medium, Hard)',
                'is_system' => true,
                'requirements' => [
                    'type' => 'complete_difficulties',
                    'levels' => ['Easy', 'Medium', 'Hard']
                ]
            ],
            [
                'id' => 3,
                'slug' => 'top-3-challenger',
                'name' => 'Top 3 Challenger',
                'emoji' => '🏆',
                'description' => 'Raih peringkat 1-3 di Event Multiplayer',
                'is_system' => true,
                // Legacy logic specific to leaderboard
                'requirements' => null,
            ],
            [
                'id' => 4,
                'slug' => 'perfect-strike',
                'name' => 'Perfect Strike',
                'emoji' => '💯',
                'description' => 'Jawab 100% benar di satu sesi game',
                'is_system' => true,
                'requirements' => [
                    'type' => 'perfect_score'
                ]
            ],
            [
                'id' => 5,
                'slug' => 'event-warrior',
                'name' => 'Event Warrior',
                'emoji' => '⚔️',
                'description' => 'Berpartisipasi dalam minimal 2 Event Multiplayer',
                'is_system' => true,
                'requirements' => [
                    'type' => 'event_participation_count',
                    'count' => 2
                ]
            ],
            [
                'id' => 6,
                'slug' => 'im-the-boss',
                'name' => 'Im the boss',
                'emoji' => '👑',
                'description' => 'Menyelesaikan 6 Boss Solo Raid yang berbeda',
                'is_system' => true,
                'requirements' => [
                    'type' => 'solo_victory_count',
                    'count' => 6,
                    'unique_raid' => true
                ]
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
