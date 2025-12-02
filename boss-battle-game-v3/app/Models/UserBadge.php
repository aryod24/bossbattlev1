<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBadge extends Model
{
    protected $table = 'user_badge';
    protected $fillable = ['user_id', 'badge_id', 'unlock_date'];
    protected $casts = [
        'unlock_date' => 'date',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }
}
