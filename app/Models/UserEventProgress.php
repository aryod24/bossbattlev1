<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEventProgress extends Model
{
    protected $table = 'user_event_progress';
    protected $fillable = [
        'user_id', 'solo_raid_id', 'status', 'completed_at'
    ];
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soloRaid()
    {
        return $this->belongsTo(SoloRaid::class);
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Helpers
    public function markCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
