<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskActivity;
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


                // add activity 

                TaskActivity::create([
                    "task_id" => $task->id,
                    "user_id" => Auth::user()->id,
                    "event" => "created",
                    "description" => "created the task ",
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

        Db::transaction(function () use ($validated_data, $user_assigned, $task) {

            $task->users()->attach($validated_data["user_id"]);
            // add activity 

            TaskActivity::create([
                "task_id" => $task->id,
                "user_id" => Auth::user()->id,
                "event" => "assign",
                "description" => "assigned the task to " . $user_assigned->name,
                "new_values" => [
                    "assigned_to" => $user_assigned->id
                ]
            ]);
        });



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
        $oldStatus = $task->status;

        Db::transaction(function () use ($validated_data, $oldStatus, $task) {

            $task->update([
                "status" => $validated_data["status"]
            ]);

            // add activity 

            TaskActivity::create([
                "task_id" => $task->id,
                "user_id" => Auth::user()->id,
                "event" => "status_changed",
                "description" => "changed the status  ",
                "new_values" => [
                    "status" => $validated_data["status"]
                ],
                "old_values" => [
                    "status" => $oldStatus
                ]
            ]);
        });

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


        Db::transaction(function () use ($user, $task) {

            $task->users()->detach($user->id);

            // add activity 

            TaskActivity::create([
                "task_id" => $task->id,
                "user_id" => Auth::user()->id,
                "event" => "unassigned",
                "description" => "deleted the member " . $user->name,
                "old_values" => [
                    "unassigned_user_id" => $user->id
                ]

            ]);
        });






        $data = [
            "task_id" => $task->id,
            "message" => 'You have beeen removed from ' . $task->title . " task",
            'url' => "#",
            'type' => 'task_assignment',
        ];

        $user->notify(new TaskAssignedNotification($data));
    }

    public function getTaskViewData(Project $project, Task $task)
    {
        $members = $project->workspace->users()->wherePivot("role", "<>", "owner")->get();
        $task->load([
            'users',
            'attachments.user',
            'activities.user',
            'comments' => function ($query) {
                $query->whereNull('parent_id')->with([
                    'user',
                    'attachments',
                    'replyComments' => function ($replyQuery) {
                        $replyQuery->with('user', 'attachments')->orderBy('created_at', 'asc');
                    }
                ])->orderBy('created_at', 'desc');
            }
        ]);

        return compact('project', 'task', 'members');
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
