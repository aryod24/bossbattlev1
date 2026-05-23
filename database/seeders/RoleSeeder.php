<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Pastikan tabel roles selalu memiliki tiga role default.
     *
     * Migrasi `create_roles_table` sudah memasukkan baris yang sama,
     * sehingga seeder ini bersifat idempoten dan aman dijalankan ulang.
     */
    public function run(): void
    {
        $roles = [
            ['name' => Role::ADMIN,   'display_name' => 'Administrator', 'description' => 'Akses penuh ke sistem.'],
            ['name' => Role::DOSEN,   'display_name' => 'Dosen',         'description' => 'Mengelola event dan bank soal.'],
            ['name' => Role::STUDENT, 'display_name' => 'Student',       'description' => 'Peserta pembelajaran.'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
