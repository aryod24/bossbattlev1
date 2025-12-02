<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionAnswer extends Model
{
    protected $table = 'session_answer';
    protected $fillable = [
        'session_id', 'session_type', 'question_id',
        'urutan_soal', 'jawaban_user', 'is_correct',
        'waktu_jawab_detik', 'attempt_number', 'is_counted_research',
        'answered_at'
    ];
    protected $casts = [
        'is_correct' => 'boolean',
        'is_counted_research' => 'boolean',
        'answered_at' => 'datetime',
    ];

    // Relationships
    public function question() {
        return $this->belongsTo(QuestionBank::class);
    }
}
