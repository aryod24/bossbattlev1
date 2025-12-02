<?php

namespace Database\Seeders;

use App\Models\SoloRaid;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SoloRaidSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        // Solo Raid 1: PHP Basics
        SoloRaid::create([
            'nama' => 'PHP Basics Week 1',
            'deskripsi' => 'Pelajari dasar-dasar PHP: variabel, tipe data, operator, dan kondisi.',
            'tanggal_mulai' => Carbon::now()->subDays(2),
            'tanggal_selesai' => Carbon::now()->addDays(5),
            'status' => 'active',
            'created_by' => $admin->id,
            
            'info_node_1' => "📘 **Selamat Datang di Dungeon PHP!**\n\nDi level Easy ini, kamu akan menghadapi **Goblin King** - boss pertamamu!\n\n**Materi yang akan diuji:**\n- Variabel dan tipe data (string, int, float, boolean)\n- Operator aritmatika (+, -, *, /, %)\n- Operator perbandingan (==, !=, >, <)\n- Kondisi if-else\n\n**Tips:**\n- Ingat perbedaan '==' dan '==='\n- Operator modulo (%) untuk cek ganjil/genap\n- Echo vs print (hampir sama!)\n\nSiap? Klik level Easy untuk mulai!",
            
            'info_node_2' => "🔥 **Medium Zone: Dragon's Lair**\n\nSelamat! Goblin King sudah dikalahkan. Sekarang saatnya menghadapi **Dragon Lord**!\n\n**Materi yang akan diuji:**\n- Array (indexed & associative)\n- Loop (for, while, foreach)\n- Fungsi built-in array (count, array_push, array_pop)\n- String manipulation (strlen, substr, strpos)\n\n**Tips:**\n- Foreach untuk iterate array\n- Array dimulai dari index 0\n- Gunakan count() untuk ukuran array\n\nGood luck, warrior!",
            
            'info_node_3' => "⚔️ **Hard Zone: Demon's Volcano**\n\nIni adalah tantangan terakhir! **Demon Emperor** menunggu di puncak!\n\n**Materi yang akan diuji:**\n- Function (parameter, return value)\n- OOP Basics (class, object, method, property)\n- Database query (SELECT, WHERE, JOIN)\n- MVC pattern\n\n**Tips:**\n- Class = blueprint, Object = instance\n- \$this untuk akses property dalam class\n- SQL JOIN untuk relasi tabel\n- MVC: Model (data), View (tampilan), Controller (logic)\n\nIni final boss! All the best!",
            
            'boss_easy_name' => 'Goblin King',
            'boss_medium_name' => 'Dragon Lord',
            'boss_hard_name' => 'Demon Emperor',
            
            'easy_enabled' => true,
            'medium_enabled' => true,
            'hard_enabled' => true,
        ]);

        // Solo Raid 2: Functions & Arrays
        SoloRaid::create([
            'nama' => 'Functions & Arrays',
            'deskripsi' => 'Mastering PHP functions and array manipulation.',
            'tanggal_mulai' => Carbon::now()->addDays(2),
            'tanggal_selesai' => Carbon::now()->addDays(10),
            'status' => 'active',
            'created_by' => $admin->id,

            'info_node_1' => "📘 **Function Basics**\n\nDefine your spells (functions)!\n\n**Code:**\n```php\nfunction tambah(\$a, \$b = 0) {\n    return \$a + \$b;\n}\n```",
            'info_node_2' => "🎯 **Array Mastery**\n\nTime to master array operations!\n\n**Topics:**\n- Multidimensional arrays\n- Array functions (map, filter, reduce)\n- Sorting arrays\n- Array search\n\nLet's go!",
            'info_node_3' => "🚀 **Advanced Combinations**\n\nCombine functions with arrays for powerful code!\n\n**Topics:**\n- Functions returning arrays\n- Array callbacks\n- Anonymous functions\n- Array destructuring\n\nFinal challenge awaits!",
            
            'boss_easy_name' => 'Function Phantom',
            'boss_medium_name' => 'Array Arachnid',
            'boss_hard_name' => 'Lambda Lich',
            'easy_enabled' => true,
            'medium_enabled' => true,
            'hard_enabled' => true,
        ]);

        // Solo Raid 3: OOP & Database
        SoloRaid::create([
            'nama' => 'OOP & Database',
            'deskripsi' => 'Object Oriented Programming and Database Connections.',
            'tanggal_mulai' => Carbon::now()->addDays(10),
            'tanggal_selesai' => Carbon::now()->addDays(20),
            'status' => 'draft',
            'created_by' => $admin->id,

            'info_node_1' => "📘 **OOP Intro**\n\nCreate your own classes.\n\n**Code:**\n```php\nclass Mahasiswa {\n    public \$nama;\n    private \$nim;\n    \n    public function __construct(\$nama, \$nim) {\n        \$this->nama = \$nama;\n        \$this->nim = \$nim;\n    }\n}\n```",
            'info_node_2' => "💾 **Database Fundamentals**\n\nConnect to database and query data!\n\n**Topics:**\n- MySQL connection (PDO, mysqli)\n- SELECT queries\n- WHERE clauses\n- INSERT, UPDATE, DELETE\n\nTime to store data!",
            'info_node_3' => "🎓 **MVC Architecture**\n\nThe ultimate pattern for web apps!\n\n**MVC:**\n- Model: Data & business logic\n- View: UI & presentation\n- Controller: Handle requests\n\nMaster this and you're ready for Laravel!",
            
            'boss_easy_name' => 'Class Construct',
            'boss_medium_name' => 'SQL Sorcerer',
            'boss_hard_name' => 'MVC Monarch',
            'easy_enabled' => true,
            'medium_enabled' => false,
            'hard_enabled' => true,
        ]);
    }
}
