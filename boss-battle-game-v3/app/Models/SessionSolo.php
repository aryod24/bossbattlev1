<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionSolo extends Model
{
    protected $table = 'session_solo';
    protected $fillable = [
        'user_id', 'solo_raid_id', 'level',
        'waktu_mulai', 'waktu_selesai', 'durasi_detik',
        'jumlah_soal', 'jumlah_benar', 'jumlah_salah',
        'boss_hp_awal', 'boss_hp_akhir', 'boss_kalah',
        'skor_akhir', 'xp_diperoleh',
        'attempt_number', 'is_counted_research', 'is_first_attempt'
    ];
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'boss_kalah' => 'boolean',
        'is_counted_research' => 'boolean',
        'is_first_attempt' => 'boolean',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function soloRaid() {
        return $this->belongsTo(SoloRaid::class);
    }
    public function answers() {
        return $this->hasMany(SessionAnswer::class, 'session_id')
            ->where('session_type', 'solo');
    }
}
