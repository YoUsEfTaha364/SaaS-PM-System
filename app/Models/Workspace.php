<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
     protected $guarded = ["id"];

     public function owner(){
        return $this->belongsTo(User::class);
     }

     public function users(){
        return $this->belongsToMany(User::class)->withPivot("role");
     }

     public function projects(){
        return $this->hasMany(project::class);
     }


}
