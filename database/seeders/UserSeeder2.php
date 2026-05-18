<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder2 extends Seeder
{
    public function run(): void
    {
        // 26 Students
        $students = [
            ['nim' => '244107020001', 'nama' => 'ACHMAD NABIL AFGAREZA'],
            ['nim' => '244107020007', 'nama' => 'AFRIZAL RAFLI KUSUMA WARDANA'],
            ['nim' => '244107020125', 'nama' => 'AHMAD KEVIN MALIK ZAKARIA'],
            ['nim' => '244107020184', 'nama' => 'ALDI SURYA SAPUTRA'],
            ['nim' => '244107020158', 'nama' => 'ARJUNA SATRIA HUTAMA'],
            ['nim' => '244107020185', 'nama' => 'AYU ANNISA ARYANI NAGARI'],
            ['nim' => '244107020166', 'nama' => 'BAGAS ARDIANSA PUTRA'],
            ['nim' => '244107020103', 'nama' => 'EVAN RADITYA TARUNA PUTRA'],
            ['nim' => '244107020119', 'nama' => 'JASMINE NASYWA NABILAH'],
            ['nim' => '244107020210', 'nama' => 'KHOIRUL UMAM NOVALIDI'],
            ['nim' => '244107020164', 'nama' => 'MOCH. ADAM ARSYAD FAIZIN'],
            ['nim' => '244107020067', 'nama' => 'MOHAMAT FAUZI ROHMAN'],
            ['nim' => '244107020096', 'nama' => 'MUHAMMAD RIFKY PRADITYA'],
            ['nim' => '244107020091', 'nama' => 'NAJLA NURICIA LAUDY'],
            ['nim' => '244107020047', 'nama' => 'NAWAF AZRIL ANNAUFAL'],
            ['nim' => '244107020050', 'nama' => 'QULBI KHUTSI AZZUMI'],
            ['nim' => '244107020204', 'nama' => 'RADITYA RIEFKI'],
            ['nim' => '244107020100', 'nama' => 'RAFAZIAN ALIEF FATAH'],
            ['nim' => '244107020126', 'nama' => 'RAFI ABYANTARA PRATAMA'],
            ['nim' => '244107020087', 'nama' => 'RAIHAN AKBAR PUTRA PRASETYO'],
            ['nim' => '244107020153', 'nama' => 'RISKY ADI PRASETYA'],
            ['nim' => '244107020239', 'nama' => 'RIVAN FAHLUL FADILLAH'],
            ['nim' => '244107020029', 'nama' => 'SANDY KURNIAWAN'],
            ['nim' => '244107020221', 'nama' => 'SHAFIQA NABILA MAHARANI KHOIRUNNISA'],
            ['nim' => '244107020137', 'nama' => 'WAHYUDI SATRIAWAN HAMID'],
            ['nim' => '244107020146', 'nama' => 'YULIKE DWI NURCAHYANI'],
        ];

        foreach ($students as $student) {
            User::create([
                'nim'      => $student['nim'],
                'nama'     => $student['nama'],
                'email'    => $student['nim'] . '@gmail.com',
                'kelas'    => 'TI-2E',
                'role'     => 'student',
                'total_xp' => 0,
                'level'    => 1,
                'password' => bcrypt($student['nim']),
            ]);
        }
    }
}