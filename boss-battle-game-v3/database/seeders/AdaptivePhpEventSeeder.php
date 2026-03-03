<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SoloRaid;
use App\Models\RaidNode;
use Illuminate\Support\Facades\DB;

class AdaptivePhpEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Setup initial PHP events
        $sections = ['Easy', 'Medium', 'Hard'];

        $eventTemplates = [
            // 1
            [
                'title' => 'Pengenalan & Syntax Dasar PHP',
                'description' => 'Mempelajari cara menulis kode PHP, penempatan tag PHP, dan perintah output dasar seperti echo dan print.',
            ],
            // 2
            [
                'title' => 'Variabel & Tipe Data',
                'description' => 'Memahami konsep variabel di PHP, tipe data string, integer, float, boolean, dan cara kerjanya.',
            ],
            // 3
            [
                'title' => 'Operator dalam PHP',
                'description' => 'Mempelajari operator aritmatika, penugasan, perbandingan, dan logika dalam bahasa pemrograman PHP.',
            ],
            // 4
            [
                'title' => 'Struktur Kontrol (If, Else, Switch)',
                'description' => 'Mempelajari cara membuat percabangan kode menggunakan if, else, elseif, dan switch case.',
            ],
            // 5
            [
                'title' => 'Perulangan (Looping)',
                'description' => 'Mempelajari cara mengulang instruksi kode menggunakan for, while, do-while, dan foreach loop.',
            ]
        ];

        DB::transaction(function () use ($sections, $eventTemplates) {
            foreach ($sections as $section) {
                // Determine Boss Names based on section
                $bossEasy = $section === 'Easy' ? 'Goblin King' : ($section === 'Medium' ? 'Orc Warlord' : 'Dragon Lord');
                $bossMedium = $section === 'Easy' ? 'Hobgoblin' : ($section === 'Medium' ? 'Ogre Crusher' : 'Dragon Sentinel');
                $bossHard = $section === 'Easy' ? 'Troll Berserker' : ($section === 'Medium' ? 'Minotaur King' : 'Dragon Emperor');
                
                // 1. Create 5 Learning Events
                foreach ($eventTemplates as $index => $template) {
                    $order = $index + 1;
                    $raid = SoloRaid::create([
                        'created_by' => 1, // Assumes user 1 exists, ideally an admin/dosen
                        'nama' => "{$template['title']} ({$section})",
                        'deskripsi' => $template['description'],
                        'tanggal_mulai' => now()->subDay(),
                        'tanggal_selesai' => now()->addMonths(6),
                        'question_bank_id' => 1, // Assumes Question Bank 1 (PHP) exists
                        'status' => 'active',
                        'type' => 'learning',
                        'section' => $section,
                        'section_order' => $order,
                        'boss_easy_name' => '',
                        'boss_medium_name' => '',
                        'boss_hard_name' => '',
                        'easy_enabled' => false,
                        'medium_enabled' => false,
                        'hard_enabled' => false,
                    ]);

                    // Create 5 Content Nodes + 1 Quiz Node for each Learning Event
                    for ($nodeOrder = 1; $nodeOrder <= 5; $nodeOrder++) {
                        RaidNode::create([
                            'solo_raid_id' => $raid->id,
                            'type' => 'content',
                            'title' => "Materi {$nodeOrder}: {$template['title']}",
                            'content' => "## {$template['title']} (Bagian {$nodeOrder})\n\nIni adalah contoh isi materi edukasi untuk section {$section}.\n\nSilakan pelajari materi ini dengan saksama sebelum maju ke tahap berikutnya.",
                            'order' => $nodeOrder,
                        ]);
                    }

                    // 1 Quiz Node
                    RaidNode::create([
                        'solo_raid_id' => $raid->id,
                        'type' => 'quiz',
                        'title' => "Quiz Akhir: {$template['title']}",
                        'content' => null,
                        'order' => 6,
                    ]);
                }

                // 2. Create 1 Boss Event at the end
                SoloRaid::create([
                    'created_by' => 1,
                    'nama' => "Boss Battle: {$section} Assessment",
                    'deskripsi' => "Ujian akhir untuk mengukur pemahaman seluruh materi di section {$section}.",
                    'tanggal_mulai' => now()->subDay(),
                    'tanggal_selesai' => now()->addMonths(6),
                    'question_bank_id' => 1, 
                    'status' => 'active',
                    'type' => 'boss',
                    'section' => $section,
                    'section_order' => 6, // 6th event in the sequence
                    'boss_easy_name' => clone $bossEasy,
                    'boss_medium_name' => clone $bossMedium,
                    'boss_hard_name' => clone $bossHard,
                    'easy_enabled' => true,
                    'medium_enabled' => true,
                    'hard_enabled' => true,
                ]);
            }
        });
    }
}
