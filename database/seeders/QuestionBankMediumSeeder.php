<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionBank;

class QuestionBankMediumSeeder extends Seeder
{
    public function run(): void
    {
        $commonAttributes = [
            'bank_group'       => 3,
            'bank_name'        => 'Functions & Arrays - Medium',
            'bank_icon'        => '📚',
            'bank_description' => 'Bank soal PHP menengah (Medium Level): fungsi, array, dan loop dasar.',
        ];

        $questions = [
            [
                'level'         => 'Medium',
                'soal_text'     => 'Keyword apa yang digunakan untuk mengembalikan nilai dari sebuah fungsi PHP?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'return',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Fungsi PHP bawaan untuk menghitung jumlah elemen dalam sebuah array adalah (tulis nama fungsinya tanpa tanda kurung)',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'count',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Jika terdapat array $buah = ["apel", "mangga", "jeruk"], string apa yang ada pada pemanggilan $buah[1]?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'mangga',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Manakah cara yang benar untuk mengakses nilai "Budi" pada array associative: $mhs = ["nama" => "Budi", "umur" => 20]?',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '$mhs["nama"]',
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
                'soal_text'     => 'Pada array multidimensi $siswa = [["Budi", 85], ["Ani", 92]], apa hasil dari pemanggilan $siswa[1][0]?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Budi',
                'pilihan_b'     => '85',
                'pilihan_c'     => 'Ani',
                'pilihan_d'     => '92',
                'jawaban_benar' => 'Ani',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Berapa angka yang dihasilkan oleh fungsi strlen("Hello World")?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '10',
                'pilihan_b'     => '11',
                'pilihan_c'     => '12',
                'pilihan_d'     => '9',
                'jawaban_benar' => '11',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Fungsi PHP yang digunakan untuk memecah string menjadi array berdasarkan delimiter adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'implode()',
                'pilihan_b'     => 'chunk_split()',
                'pilihan_c'     => 'explode()',
                'pilihan_d'     => 'str_split()',
                'jawaban_benar' => 'explode()',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Pada perulangan for dengan kondisi awal $i = 1, kondisi $i <= 3, dan increment $i++, berapa kali body loop akan dieksekusi?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '2 kali',
                'pilihan_b'     => '3 kali',
                'pilihan_c'     => '4 kali',
                'pilihan_d'     => '1 kali',
                'jawaban_benar' => '3 kali',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Loop mana yang PASTI dieksekusi minimal satu kali meskipun kondisi bernilai false?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'for',
                'pilihan_b'     => 'while',
                'pilihan_c'     => 'foreach',
                'pilihan_d'     => 'do...while',
                'jawaban_benar' => 'do...while',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Apa string yang dihasilkan dari fungsi trim("  PHP Laravel  ")?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '  PHP Laravel  ',
                'pilihan_b'     => 'PHPLaravel',
                'pilihan_c'     => 'PHP Laravel',
                'pilihan_d'     => 'php laravel',
                'jawaban_benar' => 'PHP Laravel',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Jika sebuah fungsi mengembalikan nilai 7, berapa hasil dari pemanggilan fungsi tersebut jika dikalikan 2?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '7',
                'pilihan_b'     => '14',
                'pilihan_c'     => '9',
                'pilihan_d'     => '12',
                'jawaban_benar' => '14',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Dalam perulangan array [1, 2, 3, 4, 5], jika terdapat pengecekan "if ($val == 3) continue;", angka berapa saja yang akan tercetak?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '1 2 3 4 5',
                'pilihan_b'     => '1 2 4 5',
                'pilihan_c'     => '1 2',
                'pilihan_d'     => '3 4 5',
                'jawaban_benar' => '1 2 4 5',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Jika variabel $kata dihasilkan dari explode(",", "PHP,Laravel,MySQL"), apa string pada indeks $kata[2]?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'PHP',
                'pilihan_b'     => 'Laravel',
                'pilihan_c'     => 'MySQL',
                'pilihan_d'     => 'Error',
                'jawaban_benar' => 'MySQL',
                'bobot_xp'      => 20,
            ],
            [
                'level'         => 'Medium',
                'soal_text'     => 'Fungsi yang TEPAT digunakan untuk mengecek apakah sebuah nilai ada di dalam array adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'array_search()',
                'pilihan_b'     => 'in_array()',
                'pilihan_c'     => 'array_exists()',
                'pilihan_d'     => 'isset()',
                'jawaban_benar' => 'in_array()',
                'bobot_xp'      => 20,
            ],
        ];

        foreach ($questions as $question) {
            QuestionBank::create(array_merge($commonAttributes, $question));
        }
    }
}