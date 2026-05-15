<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionBank;

class QuestionBankEasySeeder extends Seeder
{
    public function run(): void
    {
        $commonAttributes = [
            'bank_group'       => 1,
            'bank_name'        => 'PHP Basics Week 1 - Easy',
            'bank_icon'        => '💻',
            'bank_description' => 'Bank soal PHP dasar (Easy Level): sintaks, variabel, dan tipe data.',
        ];

        $questions = [
            [
                'level'         => 'Easy',
                'soal_text'     => 'Tag pembuka yang digunakan untuk memulai kode PHP adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '<?php',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Huruf P kedua pada singkatan PHP merupakan singkatan dari kata apa?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'Preprocessor',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'PHP dieksekusi di sisi...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'Server',
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
                'soal_text'     => 'Manakah pernyataan yang BENAR tentang tipe data bool di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'bool hanya bisa bernilai 1 atau 0',
                'pilihan_b'     => 'bool bisa bernilai true atau false',
                'pilihan_c'     => 'bool adalah tipe data angka desimal',
                'pilihan_d'     => 'bool tidak bisa digunakan dalam kondisi if',
                'jawaban_benar' => 'bool bisa bernilai true atau false',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Jika variabel $nama bernilai "Budi", apa hasil dari: echo "Halo, $nama!"; (menggunakan kutip ganda)?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Halo, $nama!',
                'pilihan_b'     => 'Halo, Budi!',
                'pilihan_c'     => 'Halo, "Budi"!',
                'pilihan_d'     => 'Error',
                'jawaban_benar' => 'Halo, Budi!',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Jika menggunakan kutip tunggal seperti: echo \'Halo, $nama!\'; apa yang akan dicetak oleh PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Halo, Budi!',
                'pilihan_b'     => 'Halo, $nama!',
                'pilihan_c'     => 'Error',
                'pilihan_d'     => 'Halo, ""!',
                'jawaban_benar' => 'Halo, $nama!',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Perbedaan utama antara echo dan print di PHP adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'echo lebih lambat dari print',
                'pilihan_b'     => 'print selalu mengembalikan nilai 1, echo tidak',
                'pilihan_c'     => 'echo hanya bisa mencetak angka',
                'pilihan_d'     => 'print bisa menerima banyak argumen',
                'jawaban_benar' => 'print selalu mengembalikan nilai 1, echo tidak',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Operator mana yang digunakan untuk menggabungkan (konkatenasi) dua buah string di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '+',
                'pilihan_b'     => '&',
                'pilihan_c'     => '.',
                'pilihan_d'     => ',',
                'jawaban_benar' => '.',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Keyword yang digunakan untuk menambahkan kondisi tambahan setelah blok if di PHP adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'elif',
                'pilihan_b'     => 'else if only',
                'pilihan_c'     => 'elseif',
                'pilihan_d'     => 'when',
                'jawaban_benar' => 'elseif',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Manakah operator perbandingan yang membandingkan NILAI DAN TIPE DATA secara bersamaan?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '==',
                'pilihan_b'     => '!=',
                'pilihan_c'     => '===',
                'pilihan_d'     => '<>',
                'jawaban_benar' => '===',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Berapa hasil akhir dari variabel $x jika awalnya bernilai 5, lalu diberikan operasi assignment: $x += 3?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '5',
                'pilihan_b'     => '3',
                'pilihan_c'     => '53',
                'pilihan_d'     => '8',
                'jawaban_benar' => '8',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Fungsi bawaan PHP untuk mengetahui tipe data dan nilai sebuah variabel sekaligus adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'gettype()',
                'pilihan_b'     => 'typeof()',
                'pilihan_c'     => 'var_dump()',
                'pilihan_d'     => 'print_r()',
                'jawaban_benar' => 'var_dump()',
                'bobot_xp'      => 10,
            ],
            [
                'level'         => 'Easy',
                'soal_text'     => 'Keyword yang digunakan untuk mengakhiri setiap case dalam struktur switch di PHP adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'stop',
                'pilihan_b'     => 'end',
                'pilihan_c'     => 'break',
                'pilihan_d'     => 'exit',
                'jawaban_benar' => 'break',
                'bobot_xp'      => 10,
            ],
        ];

        foreach ($questions as $question) {
            QuestionBank::create(array_merge($commonAttributes, $question));
        }
    }
}