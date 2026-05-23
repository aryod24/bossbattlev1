<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * Konstanta nama role agar tidak ada "magic strings" di seluruh aplikasi.
     */
    public const ADMIN   = 'admin';
    public const DOSEN   = 'dosen';
    public const STUDENT = 'student';

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Setiap role dapat dimiliki oleh banyak user.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Helper kecil untuk mengambil id role berdasarkan nama.
     */
    public static function idByName(string $name): ?int
    {
        $role = static::where('name', $name)->first();
        return $role?->id;
    }
}
