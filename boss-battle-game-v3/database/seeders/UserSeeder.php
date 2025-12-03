<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 5 Admin users
        User::create([
            'nim' => '2241760001',
            'nama' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'kelas' => null,
            'role' => 'admin',
            'total_xp' => 0,
            'level' => 1,
            'password' => bcrypt('password')
        ]);
        User::create([
            'nim' => '2241760002',
            'nama' => 'Admin Dosen',
            'email' => 'dosen@gmail.com',
            'kelas' => null,
            'role' => 'admin',
            'total_xp' => 0,
            'level' => 1,
            'password' => bcrypt('password')
        ]);

        // 10 Student users
        $students = [
            ['nim' => '2241760101', 'nama' => 'aryod', 'kelas' => 'TI-3A'],
            ['nim' => '2241760102', 'nama' => 'Siti Nurhaliza', 'kelas' => 'TI-3A'],
            ['nim' => '2241760103', 'nama' => 'Budi Santoso', 'kelas' => 'TI-3B'],
            ['nim' => '2241760104', 'nama' => 'Dewi Lestari', 'kelas' => 'TI-3B'],
            ['nim' => '2241760105', 'nama' => 'Eko Prasetyo', 'kelas' => 'SIB-2A'],
            ['nim' => '2241760106', 'nama' => 'Fitri Rahmawati', 'kelas' => 'SIB-2A'],
            ['nim' => '2241760107', 'nama' => 'Gilang Ramadhan', 'kelas' => 'SIB-2B'],
            ['nim' => '2241760108', 'nama' => 'Hana Pertiwi', 'kelas' => 'SIB-2B'],
            ['nim' => '2241760109', 'nama' => 'Indra Wijaya', 'kelas' => 'TI-3A'],
            ['nim' => '2241760110', 'nama' => 'Joko Susilo', 'kelas' => 'TI-3B'],
        ];

        foreach ($students as $student) {
            User::create([
                'nim' => $student['nim'],
                'nama' => $student['nama'],
                'email' => strtolower(str_replace(' ', '.', $student['nama'])) . '@gmail.com',
                'kelas' => $student['kelas'],
                'role' => 'student',
                'total_xp' => 0,
                'level' => 1,
                'password' => bcrypt('password')
            ]);
        }
    }
}
