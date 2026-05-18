<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Admin
        User::create([
            'nim'      => '2241760001',
            'nama'     => 'Admin Utama',
            'email'    => 'admin@gmail.com',
            'kelas'    => null,
            'role'     => 'admin',
            'total_xp' => 0,
            'level'    => 1,
            'password' => bcrypt('password'),
        ]);

        // 2 Dosen
        User::create([
            'nim'      => 'NIDN001',
            'nama'     => 'dosen1',
            'email'    => 'dosen1@dosen.ac.id',
            'kelas'    => null,
            'role'     => 'dosen',
            'total_xp' => 0,
            'level'    => 1,
            'password' => bcrypt('password'),
        ]);

        User::create([
            'nim'      => 'NIDN002',
            'nama'     => 'dosen2',
            'email'    => 'dosen2@dosen.ac.id',
            'kelas'    => null,
            'role'     => 'dosen',
            'total_xp' => 0,
            'level'    => 1,
            'password' => bcrypt('password'),
        ]);

        // 25 Students
        $students = [
            ['nim' => '244107020078', 'nama' => 'ABIM MUSTAWA'],
            ['nim' => '244107020207', 'nama' => 'ADAM BAHY MAULANA'],
            ['nim' => '244107020109', 'nama' => 'AISYA ASWY NUR AIDHA'],
            ['nim' => '244107020079', 'nama' => 'AMIN AZIZ SUDJUD'],
            ['nim' => '244107020060', 'nama' => 'ARYAN ZUDA FIRDAUS'],
            ['nim' => '244107020216', 'nama' => 'BISMA ADHIAKSA'],
            ['nim' => '244107020093', 'nama' => 'DAVI AULIA MAGHFIRAH'],
            ['nim' => '244107020209', 'nama' => 'DIMAS HANDARHESKY IRIANTO'],
            ['nim' => '244107020072', 'nama' => 'DINA KUMALA SARI'],
            ['nim' => '244107020142', 'nama' => 'FAATIHURRIZKI PRASOJO'],
            ['nim' => '244107020168', 'nama' => 'FARREL ANDIKA CHANDRA'],
            ['nim' => '244107020150', 'nama' => 'GADUH PRAKOSO'],
            ['nim' => '244107020176', 'nama' => 'HAFIF NURRAHMAD'],
            ['nim' => '244107020220', 'nama' => 'ILHAM DHARMA ATMAJA'],
            ['nim' => '244107020139', 'nama' => 'MOKHAMMAD ILHAM PUTRA WIJAYA'],
            ['nim' => '244107020169', 'nama' => 'MUHAMMAD CHRISTIANO OLYVIAN BARINI'],
            ['nim' => '244107020025', 'nama' => 'MUHAMMAD FAIQ NABIL SAPUTRA'],
            ['nim' => '244107020148', 'nama' => 'RACHMAD APRISANDHY'],
            ['nim' => '244107020026', 'nama' => 'RAFI ADRIAN PRASETYA'],
            ['nim' => '244107020113', 'nama' => 'RAIHAN DAFFA IZZUDDIN'],
            ['nim' => '244107020102', 'nama' => 'SINGGIH WAHYU PERMANA'],
            ['nim' => '244107020143', 'nama' => 'SITI MUTMAINAH'],
            ['nim' => '244107020014', 'nama' => 'SITI NIKMATUS SHOLIHAH'],
            ['nim' => '244107020172', 'nama' => 'YANUAR RAMADHANI KUSWOKO'],
            ['nim' => '244107020086', 'nama' => 'ZACKY RIO ORLANDO'],
        ];

        foreach ($students as $student) {
            User::create([
                'nim'      => $student['nim'],
                'nama'     => $student['nama'],
                'email'    => $student['nim'] . '@gmail.com',
                'kelas'    => 'TI-2D',
                'role'     => 'student',
                'total_xp' => 0,
                'level'    => 1,
                'password' => bcrypt($student['nim']),
            ]);
        }
    }
}