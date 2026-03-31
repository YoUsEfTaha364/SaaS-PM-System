<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\ChnageStatusNotification;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class TaskService
{

    public function storeTask(array $validated_data, Project $project)
    {
        $validated = $validated_data;


        DB::transaction(
            function () use ($validated, $project) {

                $task = Task::create([
                    "title" => $validated["title"],
                    "description" => $validated["description"] ?? null,
                    "project_id" => $project->id,
                    "due_date" => $validated["due_date"] ?? null,
                ]);

                if (!empty($validated["files"])) {

                    foreach ($validated["files"] as $file) {
                        $this->uploadFile($file, $task->id);
                    }
                }
            }

        );
    }

    public function assignTask(array $validated_data, Task $task)
    {
        $user_assigned = User::find($validated_data["user_id"]);
        $user = $task->users()->where("user_id", $validated_data["user_id"])->first();

        if ($user) {
            return false;
        }

        $task->users()->attach($validated_data["user_id"]);

        $data = [
            "task_id" => $task->id,
            "message" => 'You have been assigned to a new task: ' . $task->title,
            'url' => url('/projects/' . $task->project_id . '/tasks/' . $task->id),
            'type' => 'task_assignment',
        ];

        $user_assigned->notify(new TaskAssignedNotification($data));

        return true;
    }

    public function changeStatus(array $validated_data, Task $task, User $user)
    {
        $task->update([
            "status" => $validated_data["status"]
        ]);

        $assignees = $task->users()->where("user_id", "<>", Auth::user()->id)->get();
        $owner = $task->project->workspace->owner()->where("id", "<>", Auth::user()->id)->first();

        if ($owner) {
            $assignees->push($owner);
        }

        $data = [
            "task" => $task->title,
            "message" => $user->name . " has changed the " . $task->title . " task status ",
            'type' => 'membership_actions',
        ];

        Notification::send($assignees, new ChnageStatusNotification($data));
    }

    public function deleteAssignee(Task $task, User $user)
    {
        $task->users()->detach($user->id);

        $data = [
            "task_id" => $task->id,
            "message" => 'You have beeen removed from ' . $task->title . " task",
            'url' => "#",
            'type' => 'task_assignment',
        ];

        $user->notify(new TaskAssignedNotification($data));
    }

    protected function uploadFile($file, $task_id)
    {


        $current_name = $file->getClientOriginalName();
        $file_size = $file->getSize() / 1024;
        $new_name = Str::random(15) . "_" . $current_name;

        $file->storeAs("attachments", $new_name, "public");


        Attachment::create([
            "file_name" => $current_name,
            "file_path" => $new_name,
            "attachable_id" => $task_id,
            "attachable_type" => "App\Models\Task",
            "uploaded_by" => Auth::user()->id,
            "size" => $file_size,
        ]);
    }
}
