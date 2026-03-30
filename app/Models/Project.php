<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = ["id"];

    public function tasks(){
        return $this->hasMany(Task::class);
    }
    public function workspace(){
        return $this->belongsTo(Workspace::class);
    }

}
