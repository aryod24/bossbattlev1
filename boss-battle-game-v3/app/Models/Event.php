<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $primaryKey = 'event_id';
    protected $fillable = [
        'nama_event', 'level', 'tanggal_mulai', 'jam_mulai',
        'jam_mulai_actual', 'kode_event', 'status', 'created_by'
    ];
    protected $casts = [
        'tanggal_mulai' => 'date',
        'jam_mulai_actual' => 'datetime',
    ];

    // Relationships
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function participants() {
        return $this->hasMany(EventParticipant::class, 'event_id');
    }
    public function questions() {
        return $this->belongsToMany(QuestionBank::class, 'event_question', 'event_id', 'question_id')
            ->withPivot('urutan')
            ->orderBy('urutan');
    }

    // Scopes
    public function scopeActive($query) {
        return $query->whereIn('status', ['draft', 'ongoing']);
    }
}
