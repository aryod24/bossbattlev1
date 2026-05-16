<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaidNode extends Model
{
    protected $table = 'raid_nodes';
    protected $fillable = [
        'solo_raid_id', 'type', 'title', 'content', 'order'
    ];

    // Relationships
    public function soloRaid()
    {
        return $this->belongsTo(SoloRaid::class);
    }

    // Scopes
    public function scopeContent($query)
    {
        return $query->where('type', 'content');
    }

    public function scopeQuiz($query)
    {
        return $query->where('type', 'quiz');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
