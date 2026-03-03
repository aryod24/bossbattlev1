<?php

namespace App\Models;

use App\Models\SessionSolo;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SoloRaid extends Model
{
    protected $table = 'solo_raid';
    protected $fillable = [
        'nama', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 
        'status', 'created_by', 'question_bank_id',
        'type', 'section', 'section_order',
        'boss_easy_name', 'boss_medium_name', 'boss_hard_name',
        'easy_enabled', 'medium_enabled', 'hard_enabled',
        'easy_date_start', 'easy_date_end',
        'medium_date_start', 'medium_date_end',
        'hard_date_start', 'hard_date_end'
    ];
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'easy_enabled' => 'boolean',
        'medium_enabled' => 'boolean',
        'hard_enabled' => 'boolean',
    ];

    // Relationships
    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function questionBank() {
        return $this->belongsTo(QuestionBank::class, 'question_bank_id');
    }

    public function sessions() {
        return $this->hasMany(SessionSolo::class);
    }

    public function nodes() {
        return $this->hasMany(RaidNode::class, 'solo_raid_id')->orderBy('order');
    }

    public function contentNodes() {
        return $this->nodes()->where('type', 'content');
    }

    public function quizNode() {
        return $this->nodes()->where('type', 'quiz');
    }

    // Scopes
    public function scopeActive($query) {
        return $query->where('status', 'active')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now());
    }

    public function scopeSection($query, string $section) {
        return $query->where('section', $section);
    }

    public function scopeLearning($query) {
        return $query->where('type', 'learning');
    }

    public function scopeBoss($query) {
        return $query->where('type', 'boss');
    }

    public function scopeOrdered($query) {
        return $query->orderBy('section_order');
    }

    // Helpers
    public function isLearning(): bool {
        return $this->type === 'learning';
    }

    public function isBoss(): bool {
        return $this->type === 'boss';
    }
}
