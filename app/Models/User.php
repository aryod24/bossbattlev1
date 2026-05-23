<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * `role` (string) tetap dipertahankan supaya kode lama yang
     * memanggil `User::create(['role' => 'admin'])` masih berjalan.
     * Mutator `setRoleAttribute()` akan menerjemahkannya ke `role_id`.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nim', 'nama', 'email', 'kelas', 'role', 'role_id',
        'total_xp', 'level', 'password',
        'pretest_score', 'current_section'
    ];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Selalu eager-load relasi role agar accessor `role` tidak
     * memicu N+1 query ketika data user di-iterasi.
     */
    protected $with = ['roleModel'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ================================================================
    // Relationships
    // ================================================================

    /**
     * Relasi ke tabel `roles`. Diberi nama `roleModel` agar tidak
     * bertabrakan dengan accessor virtual `role` (string).
     */
    public function roleModel()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function soloRaidsCreated() {
        return $this->hasMany(SoloRaid::class, 'created_by');
    }

    public function sessionSolos()
    {
        return $this->hasMany(SessionSolo::class);
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function eventProgress()
    {
        return $this->hasMany(UserEventProgress::class);
    }

    // ================================================================
    // Role accessor / mutator (kompatibilitas dengan kode lama)
    // ================================================================

    /**
     * Mengembalikan nama role sebagai string, misal 'admin' / 'student'.
     * Membuat ekspresi lama seperti `$user->role === 'admin'` tetap valid.
     */
    public function getRoleAttribute(): ?string
    {
        return $this->roleModel?->name;
    }

    /**
     * Menerima nama role (string), id (int), atau null lalu menulisnya
     * ke kolom `role_id`. Dipakai oleh seeder, controller, dan factory
     * yang masih memakai `'role' => 'admin'`.
     */
    public function setRoleAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['role_id'] = null;
            return;
        }

        if (is_numeric($value)) {
            $this->attributes['role_id'] = (int) $value;
            return;
        }

        if (is_string($value)) {
            $id = Role::where('name', $value)->value('id');
            // Jika nama role tidak ditemukan, set null agar tidak menabrak
            // FK constraint dengan nilai sembarangan.
            $this->attributes['role_id'] = $id;
        }
    }

    /**
     * Helper: cek apakah user memiliki salah satu role yang diberikan.
     */
    public function hasRole(string|array $names): bool
    {
        $name = $this->roleModel?->name;
        if ($name === null) {
            return false;
        }

        return in_array($name, (array) $names, true);
    }

    /**
     * Query scope: `User::whereRoleName('student')` untuk filter
     * by nama role tanpa harus menulis whereHas berulang.
     */
    public function scopeWhereRoleName($query, string|array $names)
    {
        return $query->whereHas('roleModel', function ($q) use ($names) {
            $q->whereIn('name', (array) $names);
        });
    }

    // ================================================================
    // Helpers
    // ================================================================

    public function dashboardRouteName(): string
    {
        if ($this->role === 'admin') {
            return 'admin.dashboard';
        }

        if ($this->role === 'dosen') {
            return 'dosen.dashboard';
        }

        return 'dashboard';
    }

    public function hasCompletedPretest(): bool
    {
        return $this->pretest_score !== null;
    }

    public function needsPretest(): bool
    {
        return $this->role === 'student' && !$this->hasCompletedPretest();
    }

    public function getRankLabelAttribute(): string
    {
        return match(true) {
            $this->level >= 5 => 'Master',
            $this->level >= 4 => 'Advanced',
            $this->level >= 3 => 'Gold',
            $this->level >= 2 => 'Silver',
            default => 'Novice'
        };
    }
}
