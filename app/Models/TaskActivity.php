<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskActivity extends Model
{
    protected $guarded = ["id"];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function task(){
        return $this->belongsTo(task::class);
    }

     protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}
