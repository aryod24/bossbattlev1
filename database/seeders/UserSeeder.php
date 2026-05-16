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

        // 4 Students
        $students = [
            ['nim' => '2241760101', 'nama' => 'aryod1', 'kelas' => 'TI-3A'],
            ['nim' => '2241760102', 'nama' => 'aryod2', 'kelas' => 'TI-3A'],
            ['nim' => '2241760103', 'nama' => 'aryod3', 'kelas' => 'TI-3B'],
            ['nim' => '2241760104', 'nama' => 'aryod4', 'kelas' => 'TI-3B'],
        ];

        foreach ($students as $student) {
            User::create([
                'nim'      => $student['nim'],
                'nama'     => $student['nama'],
                'email'    => $student['nama'] . '@gmail.com',
                'kelas'    => $student['kelas'],
                'role'     => 'student',
                'total_xp' => 0,
                'level'    => 1,
                'password' => bcrypt('password'),
            ]);
        }
    }
}