<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected   $guarded = ["id"];

    public function attachable(){
        return $this->morphTo();
    }
    public function user(){
        return $this->belongsTo(User::class,"uploaded_by");
    }


}
