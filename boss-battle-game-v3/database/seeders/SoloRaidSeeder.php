<?php

namespace Database\Seeders;

use App\Models\SoloRaid;
use App\Models\RaidNode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SoloRaidSeeder extends Seeder
{
    public function run(): void
    {
        $admin   = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : 1;

        // ================================================================
        // SECTION EASY — Learning Event 1 + Boss
        // ================================================================

        $raid1 = SoloRaid::create([
            'nama'            => 'PHP Basics Week 1',
            'deskripsi'       => 'Pelajari dasar-dasar PHP: variabel, tipe data, operator, dan kondisi.',
            'tanggal_mulai'   => Carbon::now()->subDays(2),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 1,         // PHP Basics bank
            'type'            => 'learning',
            'section'         => 'Easy',
            'section_order'   => 1,
            'boss_easy_name'  => 'Goblin King',
            'easy_enabled'    => true,
        ]);

        $raid1Nodes = [
            ['title' => 'Pengenalan PHP',    'content' => "## Pengenalan PHP\n\nPHP adalah bahasa pemrograman server-side yang banyak digunakan untuk pengembangan web.\n\n**Cara menulis PHP:**\n```php\n<?php\necho \"Hello World!\";\n?>\n```\n\nTag `<?php` digunakan untuk membuka blok kode PHP."],
            ['title' => 'Variabel & Tipe Data', 'content' => "## Variabel & Tipe Data\n\nVariabel di PHP diawali dengan tanda `\$`.\n\n**Tipe data utama:**\n- `string` → teks\n- `int` → bilangan bulat\n- `float` → bilangan desimal\n- `bool` → true/false\n\n```php\n\$nama = \"Budi\"; // string\n\$umur = 20;     // int\n\$nilai = 9.5;   // float\n\$lulus = true;  // bool\n```"],
            ['title' => 'Operator Dasar',    'content' => "## Operator Dasar\n\n**Operator Aritmatika:**\n```php\n\$a = 10; \$b = 3;\necho \$a + \$b;  // 13\necho \$a - \$b;  // 7\necho \$a * \$b;  // 30\necho \$a / \$b;  // 3.33\necho \$a % \$b;  // 1 (sisa bagi)\n```\n\n**Operator Perbandingan:**\n- `==` sama nilai, `===` sama nilai & tipe\n- `!=` tidak sama, `>` lebih besar, `<` lebih kecil"],
            ['title' => 'Kondisi If-Else',   'content' => "## Kondisi If-Else\n\nDigunakan untuk membuat percabangan logika.\n\n```php\n\$nilai = 75;\n\nif (\$nilai >= 80) {\n    echo \"A\";\n} elseif (\$nilai >= 70) {\n    echo \"B\";\n} else {\n    echo \"C\";\n}\n```\n\n**Tips:** Ingat perbedaan `==` (sama nilai) dan `===` (sama nilai & tipe data)!"],
            ['title' => 'Ringkasan Materi',  'content' => "## Ringkasan Materi\n\n✅ **Yang sudah dipelajari:**\n- Sintaks dasar PHP dan tag `<?php`\n- Variabel diawali `\$` dan tipe data (string, int, float, bool)\n- Operator aritmatika dan perbandingan\n- Percabangan dengan `if`, `elseif`, `else`\n\n🎯 **Siap untuk Latihan Soal!** Kerjakan soal di bawah untuk lanjut ke materi berikutnya."],
        ];
        foreach ($raid1Nodes as $i => $node) {
            RaidNode::create(['solo_raid_id' => $raid1->id, 'type' => 'content', 'title' => $node['title'], 'content' => $node['content'], 'order' => $i + 1]);
        }
        RaidNode::create(['solo_raid_id' => $raid1->id, 'type' => 'quiz', 'title' => 'Latihan Soal: PHP Basics', 'content' => null, 'order' => 6]);

        // Easy Boss Battle
        SoloRaid::create([
            'nama'            => 'Boss Battle: Goblin King',
            'deskripsi'       => 'Saatnya menghadapi Goblin King! Jawab semua soal PHP Basics dengan benar untuk mengalahkannya dan naik ke section Medium.',
            'tanggal_mulai'   => Carbon::now()->subDays(2),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 1,
            'type'            => 'boss',
            'section'         => 'Easy',
            'section_order'   => 2,
            'boss_easy_name'  => 'Goblin King',
            'easy_enabled'    => true,
        ]);

        // ================================================================
        // SECTION MEDIUM — Learning Event 1 + Boss
        // ================================================================

        $raid2 = SoloRaid::create([
            'nama'            => 'Functions & Arrays',
            'deskripsi'       => 'Mastering PHP functions dan array manipulation.',
            'tanggal_mulai'   => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 2,
            'type'            => 'learning',
            'section'         => 'Medium',
            'section_order'   => 1,
            'boss_medium_name'=> 'Array Arachnid',
            'medium_enabled'  => true,
        ]);

        $raid2Nodes = [
            ['title' => 'Pengenalan Function',          'content' => "## Pengenalan Function\n\nFunction adalah blok kode yang dapat dipanggil berulang kali.\n```php\nfunction sapa(\$nama) {\n    return \"Halo, \" . \$nama . \"!\";\n}\necho sapa(\"Budi\"); // Halo, Budi!\n```"],
            ['title' => 'Array Indexed & Associative',  'content' => "## Array Indexed & Associative\n\n```php\n\$buah = [\"Apel\", \"Mangga\"];\necho \$buah[0]; // Apel\n\n\$mhs = [\"nama\" => \"Budi\", \"ipk\" => 3.8];\necho \$mhs[\"nama\"]; // Budi\n```"],
            ['title' => 'Loop & Foreach',               'content' => "## Loop & Foreach\n\n```php\nfor (\$i = 0; \$i < 5; \$i++) { echo \$i; }\n\nforeach (\$buah as \$b) { echo \$b; }\n```"],
            ['title' => 'Fungsi Built-in Array',        'content' => "## Fungsi Built-in Array\n\n```php\ncount(\$arr); sort(\$arr);\narray_push(\$arr, 6);\nin_array(5, \$arr);\n```"],
            ['title' => 'Ringkasan Functions & Arrays', 'content' => "## Ringkasan\n\n✅ Functions, array indexed & associative, loop, fungsi built-in array.\n🎯 Latihan soal selanjutnya!"],
        ];
        foreach ($raid2Nodes as $i => $node) {
            RaidNode::create(['solo_raid_id' => $raid2->id, 'type' => 'content', 'title' => $node['title'], 'content' => $node['content'], 'order' => $i + 1]);
        }
        RaidNode::create(['solo_raid_id' => $raid2->id, 'type' => 'quiz', 'title' => 'Latihan Soal: Functions & Arrays', 'content' => null, 'order' => 6]);

        // Medium Boss Battle
        SoloRaid::create([
            'nama'            => 'Boss Battle: Array Arachnid',
            'deskripsi'       => 'Hadapi Array Arachnid! Buktikan penguasaanmu atas functions dan arrays untuk naik ke section Hard.',
            'tanggal_mulai'   => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 2,
            'type'            => 'boss',
            'section'         => 'Medium',
            'section_order'   => 2,
            'boss_medium_name'=> 'Array Arachnid',
            'medium_enabled'  => true,
        ]);

        // ================================================================
        // SECTION HARD — Learning Event 1 + Boss
        // ================================================================

        $raid3 = SoloRaid::create([
            'nama'            => 'OOP & Database',
            'deskripsi'       => 'Object-Oriented Programming dan koneksi database MySQL dengan Laravel Eloquent.',
            'tanggal_mulai'   => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 2,
            'type'            => 'learning',
            'section'         => 'Hard',
            'section_order'   => 1,
            'boss_hard_name'  => 'MVC Monarch',
            'hard_enabled'    => true,
        ]);

        $raid3Nodes = [
            ['title' => 'Pengenalan OOP',       'content' => "## Pengenalan OOP\n\nObject-Oriented Programming (OOP) adalah paradigma pemrograman berbasis objek.\n\n```php\nclass Mahasiswa {\n    public \$nama;\n    private \$nim;\n\n    public function __construct(\$nama, \$nim) {\n        \$this->nama = \$nama;\n        \$this->nim  = \$nim;\n    }\n\n    public function getNim() {\n        return \$this->nim;\n    }\n}\n\n\$mhs = new Mahasiswa(\"Budi\", \"12345\");\necho \$mhs->nama;\n```"],
            ['title' => 'Inheritance & Polymorphism', 'content' => "## Inheritance & Polymorphism\n\n**Inheritance:**\n```php\nclass Animal {\n    public function speak() { return \"...\"; }\n}\nclass Dog extends Animal {\n    public function speak() { return \"Woof!\"; }\n}\n\$d = new Dog();\necho \$d->speak(); // Woof!\n```\n\n**Polymorphism** = satu interface, banyak implementasi."],
            ['title' => 'Database MySQL & PDO',  'content' => "## Database MySQL & PDO\n\n```php\n\$pdo = new PDO('mysql:host=localhost;dbname=mydb', 'root', '');\n\n// SELECT\n\$stmt = \$pdo->prepare('SELECT * FROM mahasiswa WHERE nim = ?');\n\$stmt->execute([\$nim]);\n\$row = \$stmt->fetch();\n\n// INSERT\n\$stmt = \$pdo->prepare('INSERT INTO mahasiswa (nama, nim) VALUES (?, ?)');\n\$stmt->execute([\$nama, \$nim]);\n```"],
            ['title' => 'Laravel Eloquent ORM',  'content' => "## Laravel Eloquent ORM\n\nEloquent memetakan tabel database ke PHP class.\n\n```php\n// Model\nclass Mahasiswa extends Model {\n    protected \$fillable = ['nama', 'nim', 'ipk'];\n}\n\n// Query\n\$all  = Mahasiswa::all();\n\$one  = Mahasiswa::find(1);\n\$mhs  = Mahasiswa::where('ipk', '>', 3.5)->get();\n\n// Create\nMahasiswa::create(['nama' => 'Budi', 'nim' => '12345', 'ipk' => 3.8]);\n```"],
            ['title' => 'MVC Architecture',      'content' => "## MVC Architecture\n\n| Komponen | Fungsi | Contoh |\n|----------|--------|--------|\n| **Model** | Data & business logic | `Mahasiswa.php` |\n| **View** | Tampilan UI | `mahasiswa.blade.php` |\n| **Controller** | Handle request | `MahasiswaController.php` |\n\n**Alur:** Request → Controller → Model (ambil data) → View (render HTML)"],
        ];
        foreach ($raid3Nodes as $i => $node) {
            RaidNode::create(['solo_raid_id' => $raid3->id, 'type' => 'content', 'title' => $node['title'], 'content' => $node['content'], 'order' => $i + 1]);
        }
        RaidNode::create(['solo_raid_id' => $raid3->id, 'type' => 'quiz', 'title' => 'Latihan Soal: OOP & Database', 'content' => null, 'order' => 6]);

        // Hard Boss Battle
        SoloRaid::create([
            'nama'            => 'Boss Battle: MVC Monarch',
            'deskripsi'       => 'Final Boss! Tunjukkan penguasaanmu atas OOP, Database, dan arsitektur MVC.',
            'tanggal_mulai'   => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 2,
            'type'            => 'boss',
            'section'         => 'Hard',
            'section_order'   => 2,
            'boss_hard_name'  => 'MVC Monarch',
            'hard_enabled'    => true,
        ]);
    }
}
