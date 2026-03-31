<?php

namespace App\Services;

use App\Events\CommentEvent;
use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CommentService
{

    public function storeComment(array $validated_data, Task $task)
    {
        $validated = $validated_data;


        $comment=DB::transaction(
            function () use ($validated, $task) {

                $comment = Comment::create([
                    "content" => $validated["content"],
                    "user_id" => Auth::user()->id,
                    "parent_id" => null,
                    "task_id" => $task->id,
                ]);

                if (!empty($validated["files"])) {

                    foreach ($validated["files"] as $file) {
                        $this->uploadFile($file, $comment->id);
                    }
                }

                return $comment;
            }

            

        );

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

        return $comment;


    }
    public function storeReply(array $validated_data, Task $task,Comment $comment)
    {
        $validated = $validated_data;


        $reply=DB::transaction(
            function () use ($validated, $task,$comment) {

              $reply = Comment::create([
            "content" => $validated["content"],
            "user_id" => Auth::user()->id,
            "parent_id" => $comment->id,
            "task_id" => $task->id,
        ]);

                if (!empty($validated["files"])) {

                    foreach ($validated["files"] as $file) {
                        $this->uploadFile($file, $reply->id);
                    }
                }

                return $reply;
            }

            

        );

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

        return $reply;


    }

    protected function uploadFile($file, $comment_id)
    {


        $current_name = $file->getClientOriginalName();
        $file_size = $file->getSize() / 1024;
        $new_name = Str::random(15) . "_" . $current_name;

        $file->storeAs("attachments", $new_name, "public");


        Attachment::create([
            "file_name" => $current_name,
            "file_path" => $new_name,
            "attachable_id" => $comment_id,
            "attachable_type" => "App\Models\Comment",
            "uploaded_by" => Auth::user()->id,
            "size" => $file_size,
        ]);
    }
}
