<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    
    protected $guarded = ["id"];
    public function users(){
        return $this->belongsToMany(User::class);
    }
    public function project(){
        return $this->belongsTo(project::class);
    }
    public function comments(){
        return $this->hasmany(Comment::class);
    }
    public function attachments(){
        return $this->hasmany(Attachment::class);
    }
}
