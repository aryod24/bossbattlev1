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
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Default boss name per-section. Sebelumnya disimpan per kolom
     * (`boss_easy_name`, dst.) dan tidak pernah benar-benar diubah,
     * jadi sekarang dikonstanta saja.
     */
    public const BOSS_NAMES = [
        'Easy'   => 'Goblin King',
        'Medium' => 'Array Arachnid',
        'Hard'   => 'MVC Monarch',
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

    /**
     * Nama boss berdasarkan level / section.
     * Menggantikan kolom `boss_easy_name` / `boss_medium_name` / `boss_hard_name`.
     */
    public function bossName(?string $level = null): string
    {
        $key = $level ?? $this->section ?? 'Easy';
        return self::BOSS_NAMES[$key] ?? 'Boss';
    }
}
