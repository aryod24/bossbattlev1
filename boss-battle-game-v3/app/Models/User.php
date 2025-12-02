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
     * @var list<string>
     */
    protected $fillable = [
        'nim', 'nama', 'email', 'kelas', 'role',
        'total_xp', 'level', 'password'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function soloRaidsCreated() {
        return $this->hasMany(SoloRaid::class, 'created_by');
    }

    public function sessionSolos()
    {
        return $this->hasMany(SessionSolo::class);
    }

    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }
}
