<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionBank;

class QuestionBankBossSeeder extends Seeder
{
    public function run(): void
    {
        $commonAttributes = [
            'bank_group'       => 2,
            'bank_name'        => 'PHP Basics Week 1 - Boss',
            'bank_icon'        => '👑',
            'bank_description' => 'Bank soal PHP (Boss Level): operator lanjutan, case-sensitivity, dan match expression.',
        ];

        $questions = [
            [
                'level'         => 'Easy',
                'soal_text'     => 'Setiap statement di PHP harus diakhiri dengan karakter...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '.',
                'pilihan_b'     => ':',
                'pilihan_c'     => ';',
                'pilihan_d'     => ',',
                'jawaban_benar' => ';',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Manakah nama variabel yang VALID di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '$1nama',
                'pilihan_b'     => '$nama_1',
                'pilihan_c'     => '$nama-user',
                'pilihan_d'     => '$nama user',
                'jawaban_benar' => '$nama_1',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Berapa hasil dari operasi perpangkatan 10 ** 2 di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '20',
                'pilihan_b'     => '102',
                'pilihan_c'     => '100',
                'pilihan_d'     => '1000',
                'jawaban_benar' => '100',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Variabel PHP bersifat case-sensitive. Pernyataan ini...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Benar untuk variabel, salah untuk keyword',
                'pilihan_b'     => 'Salah, PHP tidak case-sensitive sama sekali',
                'pilihan_c'     => 'Benar untuk semua elemen PHP',
                'pilihan_d'     => 'Hanya berlaku untuk fungsi',
                'jawaban_benar' => 'Benar untuk variabel, salah untuk keyword',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Apakah hasil dari perbandingan identik (===) antara string "5" dan integer 5?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'true',
                'pilihan_b'     => 'false',
                'pilihan_c'     => '1',
                'pilihan_d'     => 'Error',
                'jawaban_benar' => 'false',
                'bobot_xp'      => 10,
            ],
            // ✅ PERBAIKAN #6
            [
                'level'         => 'Easy',
                'soal_text'     => 'Simbol komentar multi-baris yang benar di PHP adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '// komentar //',
                'pilihan_b'     => '# komentar',
                'pilihan_c'     => '/* komentar */',
                'pilihan_d'     => '** komentar **',
                'jawaban_benar' => '/* komentar */',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Pada ternary operator: ($umur >= 17) ? "Dewasa" : "Anak-anak", apa hasilnya jika nilai umur adalah 16?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Dewasa',
                'pilihan_b'     => 'Anak-anak',
                'pilihan_c'     => 'true',
                'pilihan_d'     => 'Error',
                'jawaban_benar' => 'Anak-anak',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Tipe data apa yang dihasilkan dari operasi pembagian: 10 / 3 di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'int',
                'pilihan_b'     => 'string',
                'pilihan_c'     => 'float',
                'pilihan_d'     => 'double',
                'jawaban_benar' => 'float',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Apakah hasil evaluasi logika dari kondisi (10 > 5 && 0 == 0) di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'false',
                'pilihan_b'     => 'true',
                'pilihan_c'     => 'null',
                'pilihan_d'     => 'Error',
                'jawaban_benar' => 'true',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Manakah pernyataan yang BENAR tentang match expression di PHP 8?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'match menggunakan perbandingan == seperti switch',
                'pilihan_b'     => 'match memerlukan keyword break di setiap case',
                'pilihan_c'     => 'match menggunakan perbandingan === dan mengembalikan nilai',
                'pilihan_d'     => 'match tidak mendukung nilai default',
                'jawaban_benar' => 'match menggunakan perbandingan === dan mengembalikan nilai',
                'bobot_xp'      => 10,
            ],
        ];

        foreach ($questions as $question) {
            QuestionBank::create(array_merge($commonAttributes, $question));
        }
    }
}