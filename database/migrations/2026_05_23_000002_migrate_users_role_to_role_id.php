<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Pindahkan kolom enum `role` di tabel users menjadi foreign key
     * `role_id` ke tabel `roles`. Data lama dipindahkan terlebih dahulu
     * agar tidak ada user yang kehilangan role-nya.
     */
    public function up(): void
    {
        // 1. Tambah kolom role_id (nullable dulu supaya bisa diisi backfill)
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->after('kelas');
        });

        // 2. Backfill role_id dari kolom enum `role` lama
        $roles = DB::table('roles')->pluck('id', 'name'); // ['admin' => 1, ...]

        foreach ($roles as $name => $id) {
            DB::table('users')->where('role', $name)->update(['role_id' => $id]);
        }

        // 3. Pastikan tidak ada user tanpa role: fallback ke 'student'
        $studentId = $roles['student'] ?? null;
        if ($studentId) {
            DB::table('users')->whereNull('role_id')->update(['role_id' => $studentId]);
        }

        // 4. Drop index dan kolom `role` lama, lalu pasang FK + index baru
        Schema::table('users', function (Blueprint $table) {
            // Index lama dibuat saat tabel users dibuat: $table->index('role')
            $table->dropIndex(['role']);
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable(false)->change();
            $table->foreign('role_id')->references('id')->on('roles')->restrictOnDelete();
            $table->index('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tambah kembali kolom enum `role`
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dosen', 'student'])
                ->default('student')
                ->after('kelas');
        });

        // Backfill nilai enum dari role_id
        $roles = DB::table('roles')->pluck('name', 'id'); // [1 => 'admin', ...]
        foreach ($roles as $id => $name) {
            DB::table('users')->where('role_id', $id)->update(['role' => $name]);
        }

        // Lepas FK + kolom role_id
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropIndex(['role_id']);
            $table->dropColumn('role_id');
            $table->index('role');
        });
    }
};
