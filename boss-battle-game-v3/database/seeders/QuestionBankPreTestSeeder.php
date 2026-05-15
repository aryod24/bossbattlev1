<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionBank;

class QuestionBankPreTestSeeder extends Seeder
{
    public function run(): void
    {
        $commonAttributes = [
            'bank_group'       => 0, // Group 0 khusus untuk Pre-Test
            'bank_name'        => 'Initial Assessment: Pre-Test',
            'bank_icon'        => '📋',
            'bank_description' => 'Uji kemampuan awal PHP Anda dengan 30 soal acak dari tingkat Easy hingga Hard.',
        ];

        $questions = [

            // ==================== 10 SOAL DARI EASY (Basics) ====================
            [
                'level'         => 'Easy',
                'soal_text'     => 'Tag pembuka yang digunakan untuk memulai kode PHP adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '<?php',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'PHP adalah singkatan dari...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'Hypertext Preprocessor',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Karakter apa yang wajib ada di awal nama variabel PHP?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '$',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Berapa hasil dari operasi sisa bagi (modulus) 10 % 3 di PHP?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '1',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Operator mana yang digunakan untuk menggabungkan (konkatenasi) dua buah string di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '+', 'pilihan_b' => '&', 'pilihan_c' => '.', 'pilihan_d' => ',',
                'jawaban_benar' => '.',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Manakah operator perbandingan yang membandingkan NILAI DAN TIPE DATA secara bersamaan?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '==', 'pilihan_b' => '!=', 'pilihan_c' => '===', 'pilihan_d' => '<>',
                'jawaban_benar' => '===',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Fungsi bawaan PHP untuk mengetahui tipe data dan nilai sebuah variabel sekaligus adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'gettype()', 'pilihan_b' => 'typeof()', 'pilihan_c' => 'var_dump()', 'pilihan_d' => 'print_r()',
                'jawaban_benar' => 'var_dump()',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Manakah nama variabel yang VALID di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '$1nama', 'pilihan_b' => '$nama_1', 'pilihan_c' => '$nama-user', 'pilihan_d' => '$nama user',
                'jawaban_benar' => '$nama_1',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Simbol komentar multi-baris yang benar di PHP adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '// komentar //', 'pilihan_b' => '# komentar', 'pilihan_c' => '/* komentar */', 'pilihan_d' => '** komentar **',
                'jawaban_benar' => '/* komentar */',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'PHP dieksekusi di sisi...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'Server',
                'bobot_xp'      => 10,
            ],

            // ==================== 10 SOAL DARI MEDIUM (Arrays & Functions) ====================
            [
                'level'         => 'Medium',
                'soal_text'     => 'Fungsi yang digunakan untuk menghitung jumlah elemen dalam sebuah array adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'count()',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Fungsi array_pop() digunakan untuk menghapus dan mengembalikan elemen ... array',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'terakhir',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Fungsi PHP yang digunakan untuk memecah string menjadi array berdasarkan delimiter adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'implode()', 'pilihan_b' => 'split()', 'pilihan_c' => 'explode()', 'pilihan_d' => 'str_split()',
                'jawaban_benar' => 'explode()',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Loop mana yang PASTI dieksekusi minimal satu kali meskipun kondisi bernilai false?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'for', 'pilihan_b' => 'while', 'pilihan_c' => 'foreach', 'pilihan_d' => 'do...while',
                'jawaban_benar' => 'do...while',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Fungsi yang TEPAT digunakan untuk mengecek apakah sebuah nilai ada di dalam array adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'array_search()', 'pilihan_b' => 'in_array()', 'pilihan_c' => 'array_exists()', 'pilihan_d' => 'isset()',
                'jawaban_benar' => 'in_array()',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Variabel yang dideklarasikan di dalam fungsi bersifat...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Global', 'pilihan_b' => 'Lokal', 'pilihan_c' => 'Static', 'pilihan_d' => 'Protected',
                'jawaban_benar' => 'Lokal — hanya bisa diakses di dalam fungsi tersebut',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Apa string hasil dari fungsi str_replace("World", "PHP", "Hello World")?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Hello World', 'pilihan_b' => 'Hello PHP', 'pilihan_c' => 'PHP World', 'pilihan_d' => 'PHP PHP',
                'jawaban_benar' => 'Hello PHP',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Fungsi yang digunakan untuk menggabungkan elemen array menjadi sebuah string adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'explode()', 'pilihan_b' => 'concat()', 'pilihan_c' => 'implode()', 'pilihan_d' => 'join_array()',
                'jawaban_benar' => 'implode()',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Apa string hasil dari fungsi substr("Hello World", 6, 5)?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Hello', 'pilihan_b' => 'World', 'pilihan_c' => 'ello ', 'pilihan_d' => 'lo Wo',
                'jawaban_benar' => 'World',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Keyword yang digunakan untuk menghentikan eksekusi loop sepenuhnya secara paksa di PHP adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'stop', 'pilihan_b' => 'exit', 'pilihan_c' => 'continue', 'pilihan_d' => 'break',
                'jawaban_benar' => 'break',
                'bobot_xp'      => 20,
            ],

            // ==================== 10 SOAL DARI HARD (OOP & Database) ====================
            [
                'level'         => 'Hard',
                'soal_text'     => 'Keyword yang digunakan untuk membuat instance (object) dari sebuah class di PHP adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'new',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Access modifier mana yang membuat property hanya bisa diakses dari dalam class itu sendiri DAN class turunannya?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'protected',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Keyword yang digunakan untuk mewarisi (inherit) class lain di PHP adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'extends',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Cara yang benar untuk memanggil method dari class induk di dalam class turunan adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'base::method()', 'pilihan_b' => 'super::method()', 'pilihan_c' => 'parent::method()', 'pilihan_d' => 'this->parent->method()',
                'jawaban_benar' => 'parent::method()',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Manakah cara yang BENAR dan AMAN untuk melakukan query INSERT menggunakan MySQLi agar terhindar dari SQL Injection?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Langsung masukkan variabel', 'pilihan_b' => 'Menggunakan prepared statement dengan bind_param()', 'pilihan_c' => 'Query tanpa filter', 'pilihan_d' => 'Menggunakan raw_query()',
                'jawaban_benar' => 'Menggunakan prepared statement dengan bind_param()',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Perbedaan utama PDO dibanding MySQLi adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'PDO hanya MySQL', 'pilihan_b' => 'PDO mendukung lebih dari 12 jenis database', 'pilihan_c' => 'PDO tidak mendukung prepared statement', 'pilihan_d' => 'PDO lebih lambat',
                'jawaban_benar' => 'PDO mendukung lebih dari 12 jenis database',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Superglobal PHP mana yang digunakan untuk menerima data dari form dengan method POST?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '$_GET', 'pilihan_b' => '$_REQUEST', 'pilihan_c' => '$_POST', 'pilihan_d' => '$_FORM',
                'jawaban_benar' => '$_POST',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Fungsi PHP yang harus dipanggil SEBELUM bisa mengakses superglobal session adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'session_init()', 'pilihan_b' => 'session_open()', 'pilihan_c' => 'session_create()', 'pilihan_d' => 'session_start()',
                'jawaban_benar' => 'session_start()',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Superglobal apa yang digunakan untuk menyimpan data user yang menetap di server agar bisa diakses di seluruh halaman aplikasi?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '$_SESSION',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Di dalam method sebuah class PHP, pseudo-variable apa yang digunakan untuk merujuk dan mengakses property dari object itu sendiri?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '$this',
                'bobot_xp'      => 30,
            ],
        ];

        foreach ($questions as $question) {
            QuestionBank::create(array_merge($commonAttributes, $question));
        }
    }
}