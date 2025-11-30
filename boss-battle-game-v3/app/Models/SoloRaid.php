<?php

namespace App\Models;

use App\Models\SessionSolo;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SoloRaid extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'easy_enabled' => 'boolean',
        'medium_enabled' => 'boolean',
        'hard_enabled' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sessionSolos()
    {
        return $this->hasMany(SessionSolo::class);
    }
}
