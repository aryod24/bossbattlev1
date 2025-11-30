<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionSolo extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soloRaid()
    {
        return $this->belongsTo(SoloRaid::class);
    }

    public function sessionAnswers()
    {
        return $this->hasMany(SessionAnswer::class, 'session_id')->where('session_type', 'solo');
    }
}
