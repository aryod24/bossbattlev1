<?php

namespace App\Models;

use App\Models\SessionSolo;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SoloRaid extends Model
{
    protected $table = 'solo_raid';
    protected $fillable = [
        'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 
        'status', 'created_by', 'info_node_1', 'info_node_2', 'info_node_3',
        'boss_easy_name', 'boss_medium_name', 'boss_hard_name',
        'easy_enabled', 'medium_enabled', 'hard_enabled',
        'easy_date_start', 'easy_date_end',
        'medium_date_start', 'medium_date_end',
        'hard_date_start', 'hard_date_end'
    ];
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'easy_enabled' => 'boolean',
        'medium_enabled' => 'boolean',
        'hard_enabled' => 'boolean',
    ];

    // Relationships
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function sessions() {
        return $this->hasMany(SessionSolo::class);
    }

    // Scopes
    public function scopeActive($query) {
        return $query->where('status', 'active')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now());
    }
}
