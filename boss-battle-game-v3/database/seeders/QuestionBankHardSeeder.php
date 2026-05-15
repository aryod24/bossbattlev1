<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuestionBank;

class QuestionBankHardSeeder extends Seeder
{
    public function run(): void
    {
        $commonAttributes = [
            'bank_group'       => 5,
            'bank_name'        => 'OOP & Database - Hard',
            'bank_icon'        => '🗄️',
            'bank_description' => 'Bank soal PHP lanjutan (Hard Level): OOP dasar, inheritance, dan database dasar.',
        ];

        $questions = [
            [
                'level'         => 'Hard',
                'soal_text'     => 'Keyword yang digunakan untuk membuat instance (object) dari sebuah class di PHP adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'new',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Nama method konstruktor bawaan PHP yang otomatis dipanggil saat object dibuat adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '__construct',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Pseudo-variable yang digunakan di dalam method class untuk mengakses property object itu sendiri adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => '$this',
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
                'pilihan_a'     => 'base::method()',
                'pilihan_b'     => 'super::method()',
                'pilihan_c'     => 'parent::method()',
                'pilihan_d'     => 'this->parent->method()',
                'jawaban_benar' => 'parent::method()',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Berapa hasil dari proses casting integer (int) pada string "42abc" di PHP?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '0',
                'pilihan_b'     => '42',
                'pilihan_c'     => 'Error',
                'pilihan_d'     => '42abc',
                'jawaban_benar' => '42',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Berapa hasil yang dicetak dari penggabungan string dari dua fungsi pembulatan berikut: ceil(4.1) dan floor(4.9)?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '4 4',
                'pilihan_b'     => '5 5',
                'pilihan_c'     => '5 4',
                'pilihan_d'     => '4 5',
                'jawaban_benar' => '5 4',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Manakah cara yang BENAR dan AMAN untuk melakukan query INSERT menggunakan MySQLi agar terhindar dari SQL Injection?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '$conn->query("INSERT INTO users VALUES ($nama)")',
                'pilihan_b'     => 'Menggunakan prepared statement dengan bind_param()',
                'pilihan_c'     => 'Langsung memasukkan variabel ke dalam string query',
                'pilihan_d'     => 'Menggunakan raw_query() untuk keamanan',
                'jawaban_benar' => 'Menggunakan prepared statement dengan bind_param()',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Perbedaan utama PDO dibanding MySQLi adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'PDO hanya mendukung MySQL',
                'pilihan_b'     => 'PDO mendukung lebih dari 12 jenis database',
                'pilihan_c'     => 'PDO tidak mendukung prepared statement',
                'pilihan_d'     => 'PDO lebih lambat dari MySQLi',
                'jawaban_benar' => 'PDO mendukung lebih dari 12 jenis database',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Superglobal PHP mana yang digunakan untuk menerima data dari form dengan method POST?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '$_GET',
                'pilihan_b'     => '$_REQUEST',
                'pilihan_c'     => '$_POST',
                'pilihan_d'     => '$_FORM',
                'jawaban_benar' => '$_POST',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Fungsi PHP yang harus dipanggil SEBELUM bisa mengakses superglobal session adalah...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'session_init()',
                'pilihan_b'     => 'session_open()',
                'pilihan_c'     => 'session_create()',
                'pilihan_d'     => 'session_start()',
                'jawaban_benar' => 'session_start()',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Fungsi preg_match() mengembalikan nilai...',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'String hasil pencarian',
                'pilihan_b'     => '1 jika cocok, 0 jika tidak cocok',
                'pilihan_c'     => 'Array semua kecocokan',
                'pilihan_d'     => 'false jika terjadi error kompilasi regex',
                'jawaban_benar' => '1 jika cocok, 0 jika tidak cocok',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Jika string "Harga: Rp 50000" dibersihkan menggunakan fungsi preg_replace(\'/[^0-9]/\', \'\', ...), apa string hasil akhirnya?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => 'Harga Rp 50000',
                'pilihan_b'     => '50000',
                'pilihan_c'     => 'Rp50000',
                'pilihan_d'     => 'Error',
                'jawaban_benar' => '50000',
                'bobot_xp'      => 30,
            ],
            [
                'level'         => 'Hard',
                'soal_text'     => 'Berapa angka desimal yang dihasilkan dari fungsi pembulatan round(4.567, 2)?',
                'tipe'          => 'multiple_choice',
                'pilihan_a'     => '4.5',
                'pilihan_b'     => '4.56',
                'pilihan_c'     => '4.57',
                'pilihan_d'     => '5.0',
                'jawaban_benar' => '4.57',
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
                'soal_text'     => 'Keyword yang digunakan untuk mencegah sebuah class di PHP agar tidak bisa diwariskan adalah...',
                'tipe'          => 'short_answer',
                'jawaban_benar' => 'final',
                'bobot_xp'      => 30,
            ],
        ];

        foreach ($questions as $question) {
            QuestionBank::create(array_merge($commonAttributes, $question));
        }
    }
}