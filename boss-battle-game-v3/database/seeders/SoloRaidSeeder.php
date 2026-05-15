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
            'deskripsi'       => 'Pelajari dasar-dasar PHP: sintaks, variabel, tipe data, operator, echo/print, dan percabangan kondisi.',
            'tanggal_mulai'   => Carbon::now()->subDays(2),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 1,
            'type'            => 'learning',
            'section'         => 'Easy',
            'section_order'   => 1,
            'boss_easy_name'  => 'Goblin King',
            'easy_enabled'    => true,
        ]);

        $raid1Nodes = [
            ['title' => 'PHP Intro & Syntax',    'content' => <<<'EOT'
## PHP Intro & Syntax

PHP (*Hypertext Preprocessor*) adalah bahasa pemrograman **server-side** yang berjalan di server, bukan di browser.

**Cara kerja PHP:**
1. Browser mengirim request ke server
2. Server menjalankan kode PHP
3. Server mengirim **hasil HTML** kembali ke browser

**Struktur file .php:**
```php
<?php
// Kode PHP ditulis di sini
echo "Hello World!";
?>
```

**Hal penting:**
- File PHP disimpan dengan ekstensi `.php`
- Kode PHP diawali tag `<?php` dan (opsional) ditutup `?>`
- PHP bersifat **case-sensitive** untuk nama variabel (`$Nama` ≠ `$nama`), tetapi **tidak case-sensitive** untuk keyword (`echo` = `ECHO`)
- Setiap statement diakhiri tanda titik koma `;`
EOT
],
            ['title' => 'Variables & Data Types', 'content' => <<<'EOT'
## Variables & Data Types

Variabel di PHP selalu diawali tanda `$` dan tidak perlu dideklarasikan tipenya terlebih dahulu.

**Aturan penamaan variabel:**
- Diawali `$` diikuti huruf atau underscore (bukan angka)
- Contoh valid: `$nama`, `$_nilai`, `$data1`

**Tipe data utama PHP:**

| Tipe | Contoh | Keterangan |
|------|--------|------------|
| `string` | `"Budi"` | Teks/karakter |
| `int` | `20` | Bilangan bulat |
| `float` | `9.5` | Bilangan desimal |
| `bool` | `true` | Benar/salah |
| `null` | `null` | Tidak ada nilai |
| `array` | `[1, 2, 3]` | Kumpulan data |
| `object` | `new Kelas()` | Instance class |

```php
$nama  = "Budi";     // string
$umur  = 20;         // int
$nilai = 9.5;        // float
$lulus = true;       // bool
$data  = null;       // null
$arr   = [1, 2, 3];  // array
```

> **Tips:** Gunakan `var_dump($var)` untuk melihat tipe dan nilai variabel sekaligus.
EOT
],
            ['title' => 'Operators',    'content' => <<<'EOT'
## Operators

**1. Operator Aritmatika:**
```php
$a = 10; $b = 3;
echo $a + $b;  // 13
echo $a - $b;  // 7
echo $a * $b;  // 30
echo $a / $b;  // 3.33
echo $a % $b;  // 1 (sisa bagi)
echo $a ** $b; // 1000 (pangkat)
```

**2. Operator Assignment:**
```php
$x = 5;
$x += 3;   // $x = $x + 3 → 8
$x -= 2;   // $x = $x - 2 → 6
$x *= 4;   // $x = $x * 4 → 24
```

**3. Operator Perbandingan:**
```php
$a == $b   // sama nilai (loose)
$a === $b  // sama nilai DAN tipe (strict)
$a != $b   // tidak sama nilai
$a !== $b  // tidak sama nilai atau tipe
$a > $b    // lebih besar
$a < $b    // lebih kecil
```

**4. Operator Logika:**
```php
$a && $b  // AND
$a || $b  // OR
!$a       // NOT
```

**5. Operator String:**
```php
$s = "Hello" . " World"; // "Hello World"
$s .= "!";               // "Hello World!"
```
EOT
],
            ['title' => 'PHP Echo / Print & Comments',   'content' => <<<'EOT'
## PHP Echo / Print & Comments

### Echo vs Print

| | `echo` | `print` |
|-|--------|----------|
| Return value | Tidak ada | Selalu return `1` |
| Argumen | Bisa lebih dari satu | Hanya satu |
| Kecepatan | Sedikit lebih cepat | Sedikit lebih lambat |

```php
// Echo
echo "Hello", " ", "World!"; // Hello World!
echo "Nilai: " . 10;          // Nilai: 10

// Print
print("Hello World!");         // Hello World!
$result = print("Halo");      // $result = 1
```

**Double quote vs Single quote:**
```php
$nama = "Budi";
echo "Halo, $nama!";   // Halo, Budi!  (parse variabel)
echo 'Halo, $nama!';   // Halo, $nama! (literal)
```

### Comments
```php
// Komentar satu baris

# Komentar satu baris (gaya shell)

/*
   Komentar multi-baris
*/
```

> **Tips:** Komentar tidak dieksekusi PHP dan tidak tampil di browser.
EOT
],
            ['title' => 'PHP If...Else...Elseif & Match',  'content' => <<<'EOT'
## PHP If...Else...Elseif & Match

### If / Elseif / Else
```php
$nilai = 75;

if ($nilai >= 80) {
    echo "A";
} elseif ($nilai >= 70) {
    echo "B";
} elseif ($nilai >= 60) {
    echo "C";
} else {
    echo "D";
}
// Output: B
```

### Ternary Operator
```php
$umur = 18;
$status = ($umur >= 17) ? "Dewasa" : "Anak-anak";
echo $status; // Dewasa
```

### Match Expression (PHP 8+)
```php
$kode = 2;

$hasil = match($kode) {
    1       => "Satu",
    2, 3    => "Dua atau Tiga",
    default => "Lainnya",
};
echo $hasil; // Dua atau Tiga
```

**Perbedaan `match` vs `switch`:**

| | `switch` | `match` |
|-|----------|---------|
| Perbandingan | `==` (loose) | `===` (strict) |
| Return value | Tidak | Ya (expression) |
| Fall-through | Ya (perlu `break`) | Tidak |
| PHP version | Semua | PHP 8.0+ |

> **Ingat:** Jika tidak ada `default` dan nilai tidak cocok, PHP throw `UnhandledMatchError`.
EOT
],
        ];
        foreach ($raid1Nodes as $i => $node) {
            RaidNode::create(['solo_raid_id' => $raid1->id, 'type' => 'content', 'title' => $node['title'], 'content' => $node['content'], 'order' => $i + 1]);
        }
        RaidNode::create(['solo_raid_id' => $raid1->id, 'type' => 'quiz', 'title' => 'Latihan Soal: PHP Basics Week 1', 'content' => null, 'order' => 6]);

        // Easy Boss Battle
        SoloRaid::create([
            'nama'            => 'Boss Battle: Goblin King',
            'deskripsi'       => 'Saatnya menghadapi Goblin King! Jawab 10 soal campuran PHP Basics dalam 2 menit. Butuh 6 jawaban benar untuk mengalahkannya dan naik ke Section Medium!',
            'tanggal_mulai'   => Carbon::now()->subDays(2),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 2,
            'type'            => 'boss',
            'section'         => 'Easy',
            'section_order'   => 2,
            'boss_easy_name'  => 'Goblin King',
            'easy_enabled'    => true,
        ]);

        // ================================================================
        // SECTION MEDIUM — Learning Event 2 + Boss
        // ================================================================

        $raid2 = SoloRaid::create([
            'nama'            => 'Functions & Arrays',
            'deskripsi'       => 'Kuasai fungsi, array, string functions, dan loop di PHP untuk membangun logika yang lebih kompleks.',
            'tanggal_mulai'   => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 3,
            'type'            => 'learning',
            'section'         => 'Medium',
            'section_order'   => 1,
            'boss_medium_name'=> 'Array Arachnid',
            'medium_enabled'  => true,
        ]);

        $raid2Nodes = [
            ['title' => 'PHP Functions',          'content' => "## PHP Functions\n\nFungsi adalah blok kode yang dapat dipanggil berulang kali.\n\n**Sintaks dasar:**\n```php\nfunction sapa(\$nama) {\n    return \"Halo, \" . \$nama . \"!\";\n}\necho sapa(\"Budi\"); // Halo, Budi!\n```\n\n**Default parameter:**\n```php\nfunction sapa(\$nama = \"Dunia\") {\n    return \"Halo, \" . \$nama . \"!\";\n}\necho sapa();        // Halo, Dunia!\necho sapa(\"Andi\"); // Halo, Andi!\n```\n\n**Hal penting:**\n- Fungsi tanpa `return` mengembalikan `null`\n- Nama fungsi tidak case-sensitive (`Sapa()` = `sapa()`)\n- Variabel di dalam fungsi bersifat **lokal**"],
            ['title' => 'PHP Arrays',  'content' => "## PHP Arrays\n\n### Array Indexed\nIndex dimulai dari `0`.\n```php\n\$buah = [\"apel\", \"mangga\", \"jeruk\"];\necho \$buah[0];           // apel\necho count(\$buah);       // 3\narray_push(\$buah, \"pisang\"); // tambah di akhir\narray_pop(\$buah);            // hapus elemen terakhir\n```\n\n### Array Associative\nMenggunakan key berupa string.\n```php\n\$mahasiswa = [\n    \"nama\"  => \"Budi\",\n    \"umur\"  => 20,\n    \"kelas\" => \"TI-3A\",\n];\necho \$mahasiswa[\"nama\"]; // Budi\n\nforeach (\$mahasiswa as \$key => \$value) {\n    echo \"\$key: \$value\\n\";\n}\n```"],
            ['title' => 'PHP Loops',               'content' => "## PHP Loops\n\n### for\nDigunakan ketika jumlah iterasi sudah diketahui.\n```php\nfor (\$i = 1; \$i <= 5; \$i++) {\n    echo \$i . \" \"; // 1 2 3 4 5\n}\n```\n\n### while\nKondisi dicek **sebelum** eksekusi.\n```php\n\$i = 1;\nwhile (\$i <= 5) {\n    echo \$i . \" \"; // 1 2 3 4 5\n    \$i++;\n}\n```\n\n### foreach\nKhusus untuk array.\n```php\n\$nilai = [85, 90, 78, 92];\nforeach (\$nilai as \$n) {\n    echo \$n . \" \"; // 85 90 78 92\n}\n```"],
            ['title' => 'String Functions',        'content' => "## String Functions\n\n**Fungsi string yang wajib diketahui:**\n```php\n\$teks = \"  Hello World!  \";\n\nstrlen(\$teks);                       // 16\nstrtolower(\$teks);                   // \"  hello world!  \"\nstrtoupper(\$teks);                   // \"  HELLO WORLD!  \"\ntrim(\$teks);                         // \"Hello World!\"\nstr_replace(\"World\", \"PHP\", \$teks);  // \"  Hello PHP!  \"\nsubstr(\$teks, 2, 5);                 // \"Hello\"\nexplode(\" \", \"a b c\");              // [\"a\", \"b\", \"c\"]\nimplode(\"-\", [\"a\", \"b\", \"c\"]);     // \"a-b-c\"\n```"],
            ['title' => 'Ringkasan Functions & Arrays', 'content' => "## Kesimpulan Materi\n\nSelamat! Kamu telah mempelajari konsep-konsep krusial dalam PHP yang akan sering digunakan dalam pengembangan web:\n\n1. **Functions**: Membungkus logika program agar bisa digunakan kembali (reusable) dan modular.\n2. **Arrays**: Mengelola kumpulan data, baik menggunakan index angka (indexed) maupun kunci string (associative).\n3. **Loops**: Melakukan otomatisasi tugas berulang dengan `for`, `while`, dan `foreach`.\n4. **String Functions**: Melakukan manipulasi teks seperti mencari panjang string, mengganti kata, hingga memecah teks menjadi array.\n\n**💡 Tips Belajar:**\n- Cobalah menggabungkan `foreach` dengan **Array Associative** untuk menampilkan data yang lebih kompleks.\n- Gunakan `function` dengan `return` untuk mengolah data dan mengembalikan hasilnya ke variabel lain.\n\n**Siap untuk tantangan selanjutnya?**\n🎯 Klik tombol **Selesaikan Materi** di bawah untuk memulai latihan soal!"],
        ];
        foreach ($raid2Nodes as $i => $node) {
            RaidNode::create(['solo_raid_id' => $raid2->id, 'type' => 'content', 'title' => $node['title'], 'content' => $node['content'], 'order' => $i + 1]);
        }
        RaidNode::create(['solo_raid_id' => $raid2->id, 'type' => 'quiz', 'title' => 'Latihan Soal: Functions & Arrays', 'content' => null, 'order' => 6]);

        // Medium Boss Battle
        SoloRaid::create([
            'nama'            => 'Boss Battle: Array Arachnid',
            'deskripsi'       => 'Array Arachnid menghadangmu! Jawab 15 soal campuran Functions, Arrays, Strings, dan Loops dalam 3 menit. Butuh 9 jawaban benar untuk mengalahkannya dan naik ke Section Hard!',
            'tanggal_mulai'   => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 4,
            'type'            => 'boss',
            'section'         => 'Medium',
            'section_order'   => 2,
            'boss_medium_name'=> 'Array Arachnid',
            'medium_enabled'  => true,
        ]);

        // ================================================================
        // SECTION HARD — Learning Event 3 + Boss
        // ================================================================

        $raid3 = SoloRaid::create([
            'nama'            => 'OOP & Database',
            'deskripsi'       => 'Kuasai OOP, koneksi database MySQL, dan superglobals PHP untuk membangun aplikasi web yang sesungguhnya.',
            'tanggal_mulai'   => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 5,
            'type'            => 'learning',
            'section'         => 'Hard',
            'section_order'   => 1,
            'boss_hard_name'  => 'MVC Monarch',
            'hard_enabled'    => true,
        ]);

        $raid3Nodes = [
            ['title' => 'PHP Classes & Objects',       'content' => "## PHP Classes & Objects\n\nOOP (Object-Oriented Programming) adalah paradigma pemrograman yang mengorganisasi kode ke dalam **class** dan **object**.\n\n**Konsep dasar:**\n```php\nclass Mahasiswa {\n    public string \$nama;\n    public int \$umur;\n\n    public function __construct(string \$nama, int \$umur) {\n        \$this->nama = \$nama;\n        \$this->umur = \$umur;\n    }\n\n    public function perkenalan(): string {\n        return \"Halo, saya {\$this->nama}, umur {\$this->umur} tahun.\";\n    }\n}\n\n\$mhs = new Mahasiswa(\"Budi\", 20);\necho \$mhs->perkenalan();\n// Halo, saya Budi, umur 20 tahun.\n```\n\n**Hal penting:**\n- `class` = blueprint/cetakan\n- `new` = membuat instance (object) dari class\n- `\$this` = merujuk ke object itu sendiri\n- `__construct()` = method yang otomatis dipanggil saat `new`\n- Properties dan method diakses dengan operator `->`"],
            ['title' => 'Inheritance & Access Modifiers', 'content' => "## Inheritance & Access Modifiers\n\n**Inheritance:**\n```php\nclass Animal {\n    public function speak() { return \"...\"; }\n}\nclass Dog extends Animal {\n    public function speak() { return \"Woof!\"; }\n}\n\n\$d = new Dog();\necho \$d->speak(); // Woof!\n```\n\n**Access Modifiers:**\n\n| Modifier | Class sendiri | Class turunan | Luar class |\n|----------|:---:|:---:|:---:|\n| `public` | ✅ | ✅ | ✅ |\n| `protected` | ✅ | ✅ | ❌ |\n| `private` | ✅ | ❌ | ❌ |"],
            ['title' => 'Database MySQL & PDO',  'content' => "## Database MySQL & PDO\n\n```php\n\$pdo = new PDO('mysql:host=localhost;dbname=mydb', 'root', '');\n\n// SELECT\n\$stmt = \$pdo->prepare('SELECT * FROM mahasiswa WHERE nim = ?');\n\$stmt->execute([\$nim]);\n\$row = \$stmt->fetch();\n\n// INSERT\n\$stmt = \$pdo->prepare('INSERT INTO mahasiswa (nama, nim) VALUES (?, ?)');\n\$stmt->execute([\$nama, \$nim]);\n```"],
            ['title' => 'PHP Superglobals',  'content' => "## PHP Superglobals\n\n```php\n// \$_GET — dari URL query string\necho \$_GET['keyword'];\n\n// \$_POST — dari form method POST\necho \$_POST['username'];\n\n// \$_SESSION — simpan data antar halaman\nsession_start();\n\$_SESSION['user_id'] = 5;\n\$_SESSION['nama']    = \"Budi\";\necho \$_SESSION['nama']; // Budi\n\n// \$_SERVER — info server & request\necho \$_SERVER['REQUEST_METHOD']; // GET atau POST\n```"],
            ['title' => 'Ringkasan OOP & Database',      'content' => "## Kesimpulan Materi Tingkat Lanjut\n\nLuar biasa! Kamu telah mencapai akhir dari rangkaian materi persiapan. Berikut adalah ringkasan dari topik-topik tingkat lanjut yang telah kamu pelajari:\n\n1. **OOP (Object-Oriented Programming)**: Memahami paradigma pemrograman berbasis objek, penggunaan Class, Object, Inheritance (pewarisan), dan pentingnya Access Modifiers (`public`, `protected`, `private`).\n2. **Database & PDO**: Cara melakukan koneksi ke database MySQL menggunakan PDO, menjalankan query SELECT untuk mengambil data, dan INSERT untuk menyimpan data dengan aman menggunakan *prepared statements*.\n3. **Superglobals**: Menguasai variabel global PHP seperti `\$_GET`, `\$_POST`, `\$_SESSION`, dan `\$_SERVER` untuk menangani input user dan state aplikasi.\n\n**🏆 Langkah Terakhir:**\nMateri ini adalah fondasi utama untuk membangun aplikasi web yang modern dan aman. Kamu sekarang memiliki semua bekal yang dibutuhkan untuk menghadapi tantangan terakhir.\n\n**Siap Menjadi CodeBoss Sejati?**\n🎯 Klik **Selesaikan Materi** untuk memulai latihan soal terakhir sebelum menghadapi **Final Boss**!"],
        ];
        foreach ($raid3Nodes as $i => $node) {
            RaidNode::create(['solo_raid_id' => $raid3->id, 'type' => 'content', 'title' => $node['title'], 'content' => $node['content'], 'order' => $i + 1]);
        }
        RaidNode::create(['solo_raid_id' => $raid3->id, 'type' => 'quiz', 'title' => 'Latihan Soal: OOP & Database', 'content' => null, 'order' => 6]);

        // Hard Boss Battle
        SoloRaid::create([
            'nama'            => 'Boss Battle: MVC Monarch',
            'deskripsi'       => 'MVC Monarch menjaga gerbang terakhir! Jawab 17 soal campuran OOP, Database, dan Superglobals dalam 4 menit. Butuh 11 jawaban benar untuk mengalahkannya dan menjadi CodeBoss sejati!',
            'tanggal_mulai'   => Carbon::now()->subDays(1),
            'tanggal_selesai' => Carbon::now()->addDays(30),
            'status'          => 'active',
            'created_by'      => $adminId,
            'question_bank_id'=> 6,
            'type'            => 'boss',
            'section'         => 'Hard',
            'section_order'   => 2,
            'boss_hard_name'  => 'MVC Monarch',
            'hard_enabled'    => true,
        ]);
    }
}
