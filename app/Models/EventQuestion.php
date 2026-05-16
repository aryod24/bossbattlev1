<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EventQuestion extends Pivot
{
    protected $table = 'event_question';
    public $timestamps = false;
    
    protected $fillable = ['event_id', 'question_id', 'urutan'];
}
