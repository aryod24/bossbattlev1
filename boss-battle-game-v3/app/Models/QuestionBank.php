<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    protected $table = 'question_bank';
    protected $fillable = [
        'bank_group',  // NEW: for multi-bank support
        'level', 'soal_text', 'tipe',
        'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d',
        'jawaban_benar', 'bobot_xp'
    ];

    // Scopes
    public function scopeByLevel($query, $level) {
        return $query->where('level', $level);
    }

    public function scopeInBank($query, $bankGroup) {
        return $query->where('bank_group', $bankGroup);
    }

    // Methods
    public static function getRandomByLevel($level, $count) {
        return self::where('level', $level)
            ->inRandomOrder()
            ->limit($count)
            ->get();
    }
}
