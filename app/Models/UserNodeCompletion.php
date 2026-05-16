<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNodeCompletion extends Model
{
    protected $table = 'user_node_completions';
    protected $fillable = ['user_id', 'raid_node_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function node()
    {
        return $this->belongsTo(RaidNode::class, 'raid_node_id');
    }
}
