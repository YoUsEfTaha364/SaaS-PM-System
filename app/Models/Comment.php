<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    
    protected $guarded = ["id"];

    public function replyComments(){
        return $this->hasMany(Comment::class,"parent_id");
    }
    public function parentComment(){
        return $this->belongsTo(Comment::class,"parent_id");
    }
    public function task(){
        return $this->belongsTo(Task::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }

       public function attachments(){
        return $this->morphMany(Attachment::class,"attachable");
    }


}
