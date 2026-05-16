<?php

namespace App\Models;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $table = 'event_participant';
    protected $primaryKey = 'event_participant_id';
    protected $fillable = [
        'event_id', 'user_id',
        'waktu_mulai', 'waktu_selesai', 'durasi_detik',
        'jumlah_soal', 'jumlah_benar', 'jumlah_salah',
        'boss_hp_awal', 'boss_hp_akhir', 'boss_kalah',
        'skor_akhir', 'xp_diperoleh', 'peringkat_leaderboard',
        'status'
    ];
    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'boss_kalah' => 'boolean',
    ];

    // Relationships
    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function answers() {
        return $this->hasMany(SessionAnswer::class, 'session_id')
            ->where('session_type', 'event');
    }
}
