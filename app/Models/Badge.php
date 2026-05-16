<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'emoji',
        'description',
        'category',
        'threshold',
        'requirements',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'requirements' => 'array',
    ];

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }
}
