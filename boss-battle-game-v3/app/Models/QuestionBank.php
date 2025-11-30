<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    protected $guarded = ['id'];

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public static function getRandomByLevel($level, $count)
    {
        return self::byLevel($level)->inRandomOrder()->take($count)->get();
    }
}
