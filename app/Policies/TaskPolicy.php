<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

       public function addComment(User $user,Task $task){
        $is_permitted=$task->users()->where("user_id",$user->id)->exists();
        $is_owner=$task->project->workspace->owner()->first()->id==$user->id;

        return $is_permitted || $is_owner ;


    }


     public function change_status(User $user ,Task $task){
        return $task->users()->where("user_id",$user->id)->exists() || $task->project->workspace->owner()->first()->id==$user->id;
    }
}
