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
            // ID 1: Boss Novice - Tetap sama
            [
                'id' => 1,
                'slug' => 'boss-novice',
                'name' => 'Boss Novice',
                'emoji' => '🎮',
                'description' => 'Kalahkan 1 boss level apapun (Solo/Event)',
                'is_system' => true,
                'requirements' => null, // Legacy check via checkBossNovice()
            ],
            // ID 2: Boss Slayer - Sekarang = kalahkan 2 boss
            [
                'id' => 2,
                'slug' => 'boss-slayer',
                'name' => 'Boss Slayer',
                'emoji' => '⚔️',
                'description' => 'Kalahkan 2 Boss Solo Raid',
                'is_system' => true,
                'requirements' => [
                    'type' => 'solo_victory_count',
                    'count' => 2,
                ],
            ],
            // ID 3: Im the Boss - Sekarang = kalahkan 3 boss
            [
                'id' => 3,
                'slug' => 'im-the-boss',
                'name' => 'Im the Boss',
                'emoji' => '👑',
                'description' => 'Kalahkan 3 Boss Solo Raid',
                'is_system' => true,
                'requirements' => [
                    'type' => 'solo_victory_count',
                    'count' => 3,
                ],
            ],
            // ID 4: Perfect Strike - Tetap sama
            [
                'id' => 4,
                'slug' => 'perfect-strike',
                'name' => 'Perfect Strike',
                'emoji' => '💯',
                'description' => 'Jawab 100% benar di satu sesi game',
                'is_system' => true,
                'requirements' => [
                    'type' => 'perfect_score',
                ],
            ],
            // ID 5: Triple Perfect - Ganti dari Top 3 Challenger
            [
                'id' => 5,
                'slug' => 'triple-perfect',
                'name' => 'Triple Perfect',
                'emoji' => '🌟',
                'description' => 'Jawab 100% benar dalam 3 sesi game',
                'is_system' => true,
                'requirements' => [
                    'type' => 'perfect_score_count',
                    'count' => 3,
                ],
            ],
            // ID 6: Knowledge Master - Ganti dari Event Warrior
            [
                'id' => 6,
                'slug' => 'knowledge-master',
                'name' => 'Knowledge Master',
                'emoji' => '📚',
                'description' => 'Selesaikan 10 materi (content node)',
                'is_system' => true,
                'requirements' => [
                    'type' => 'node_completion_count',
                    'count' => 10,
                ],
            ],
            // ID 7: Centurion - Jawab 100 benar dalam 5 sesi game (perfect_score_count = 5)
            [
                'id' => 7,
                'slug' => 'Flawless Streak',
                'name' => 'Flawless Streak',
                'emoji' => '🏆',
                'description' => 'Jawab 100% benar dalam 5 sesi game',
                'is_system' => true,
                'requirements' => [
                    'type' => 'perfect_score_count',
                    'count' => 5,
                ],
            ],
            // ID 8: Lore Keeper - Selesaikan 15 materi (content node)
            [
                'id' => 8,
                'slug' => 'Scholar',
                'name' => 'Scholar',
                'emoji' => '🧠',
                'description' => 'Selesaikan 15 materi (content node)',
                'is_system' => true,
                'requirements' => [
                    'type' => 'node_completion_count',
                    'count' => 15,
                ],
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['id' => $badge['id']],
                $badge
            );
        }

        // Hapus badge lama yang tidak dipakai (slug berbeda dari 6 badge di atas)
        $validSlugs = array_column($badges, 'slug');
        Badge::where('is_system', true)->whereNotIn('slug', $validSlugs)->delete();
    }
}
