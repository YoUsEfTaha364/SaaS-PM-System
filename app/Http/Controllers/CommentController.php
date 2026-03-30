<?php

namespace App\Http\Controllers;

use App\Events\CommentEvent;
use App\Models\Comment;
use App\Models\Task;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    public function store(Request $request, Task $task)
    {

        Gate::authorize("addComment", $task);


        $validated = $request->validate([
            "content" => "required|string:max:255"
        ]);

        $comment = Comment::create([
            "content" => $validated["content"],
            "user_id" => Auth::user()->id,
            "parent_id" => null,
            "task_id" => $task->id,
        ]);


        $notf_data = [
            "name" => $comment->user->name,
            "content" => $comment->content,
            "task" => $task->title,
            "message" => "the member {$comment->user->name} in task {$comment->task->title} has made a comment ",
            "type" => "comment_notification",
        ];

        $users = $task->users()->where("user_id", "!=", Auth::user()->id)->get();
        Notification::send($users, new CommentNotification($notf_data));

        $users_id = $task->users->pluck("id");
        $users_id->push($task->project->workspace->owner->id);

        foreach ($users_id as $id) {
            if (Auth::user()->id != $id) {
                CommentEvent::dispatch($notf_data, $id);
            }
        }




        return back()->with("create-comment", "comment created successfully");
    }
    public function storeReplyComment(Request $request, Task $task, Comment $comment)
    {

        Gate::authorize("addComment", $task);


        $validated = $request->validate([
            "content" => "required|string:max:255"
        ]);

        $reply = Comment::create([
            "content" => $validated["content"],
            "user_id" => Auth::user()->id,
            "parent_id" => $comment->id,
            "task_id" => $task->id,
        ]);

        $notf_data = [
            "name" => Auth::user()->name,
            "content" => $validated["content"],
            "task" => $task->title,
            "message" => "the member " .Auth::user()->name ."  in task {$task->title} has replied on your comment ",
            "type" => "comment_notification",
        ];

        // make the notification 
        Notification::send($comment->user, new CommentNotification($notf_data));

        // real time channel
        CommentEvent::dispatch($notf_data, $comment->user->id);


        return back()->with("create-comment", "reply created successfully");
    }
}
