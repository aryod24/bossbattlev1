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
        // Create 5 admins
        User::factory()->count(5)->sequence(fn ($sequence) => [
            'name' => 'Admin ' . ($sequence->index + 1),
            'email' => 'admin' . ($sequence->index + 1) . '@example.com',
            'role' => 'admin',
        ])->create();

        // Create 10 students
        User::factory()->count(10)->sequence(fn ($sequence) => [
            'name' => 'Student ' . ($sequence->index + 1),
            'email' => 'student' . ($sequence->index + 1) . '@example.com',
            'role' => 'student',
            'nim' => '22417200' . str_pad($sequence->index + 1, 2, '0', STR_PAD_LEFT),
            'kelas' => 'TI-3A',
        ])->create();
    }
}
