<?php

namespace Database\Seeders;

use App\Models\SoloRaid;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SoloRaidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->first()->id ?? 1;

        $raids = [
            [
                'nama' => 'PHP Basics Week 1',
                'deskripsi' => 'Master the fundamentals of PHP: Syntax, Variables, and Data Types.',
                'boss_easy_name' => 'Bugbear Novice',
                'boss_medium_name' => 'Syntax Serpent',
                'boss_hard_name' => 'Parse Error Dragon',
            ],
            [
                'nama' => 'Control Structures Week 2',
                'deskripsi' => 'Conquer If-Else, Switch, and Loops.',
                'boss_easy_name' => 'Looping Lizard',
                'boss_medium_name' => 'Conditional Chimera',
                'boss_hard_name' => 'Infinite Loop Golem',
            ],
            [
                'nama' => 'Functions & Arrays Week 3',
                'deskripsi' => 'Learn to organize code with Functions and manage data with Arrays.',
                'boss_easy_name' => 'Array Arachnid',
                'boss_medium_name' => 'Function Phantom',
                'boss_hard_name' => 'Recursion Hydra',
            ],
        ];

        foreach ($raids as $raid) {
            SoloRaid::create(array_merge($raid, [
                'tanggal_mulai' => now()->subDays(1),
                'tanggal_selesai' => now()->addDays(14),
                'status' => 'active',
                'created_by' => $adminId,
                'info_node_1' => 'Study Material Part 1',
                'info_node_2' => 'Study Material Part 2',
                'info_node_3' => 'Study Material Part 3',
                'easy_enabled' => true,
                'medium_enabled' => true,
                'hard_enabled' => true,
            ]));
        }
    }
}
