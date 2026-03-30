<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\ChnageStatusNotification;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tasks = $user->tasks()
            ->with('project.workspace')
            ->orderBy('updated_at', 'desc')
            ->get();

        $workspaces = $user->workspaces;

        return view('tasks.index', compact('tasks', 'workspaces'));
    }
    public function store(Request $request, Project $project)
    {

        Gate::authorize("manageWorkspace", $project->workspace);

        $validated = $request->validate([
            "title" => ["required", "string", "max:255"],
            "description" => ["string", "max:255"],
            "due_date" => ["date", "nullable"],
        ]);

        $task = Task::create([
            "title" => $validated["title"],
            "description" => $validated["description"],
            "project_id" => $project->id,
            "due_date" => $validated["due_date"],
        ]);

        return redirect()->back()->with("add-task", "task added successfully");
    }

    public function assignTask(Request $request, Task $task)
    {

        Gate::authorize("manageWorkspace", $task->project->workspace);

        $validated = $request->validate([
            "user_id" => ["required", "exists:users,id"]
        ]);
        $user_assigned = User::find($validated["user_id"]);
        $user = $task->users()->where("user_id", $validated["user_id"])->first();

        if ($user) {
            return redirect()->back()->with("assigned-user", "user already assigned");
        }

        $task->users()->attach($validated["user_id"]);

        $data = [
            "task_id" => $task->id,
            "message" => 'You have been assigned to a new task: ' . $task->title,
            'url' => url('/projects/' . $task->project_id . '/tasks/' . $task->id),
            'type' => 'task_assignment',
        ];

        $user_assigned->notify(new TaskAssignedNotification($data));

        return redirect()->back()->with("assign-task", "task assigned successfully");
    }

    public function changeStatus(Request $request, Task $task ,User $user)
    {
         
        Gate::authorize("change_status", $task);

        $validated = $request->validate([
            "status" => ["required", "in:in_progress,todo,done"]
        ]);

        $task->update([
            "status" => $validated["status"]
        ]);

        $assignees=$task->users()->where("user_id","<>",Auth::user()->id)->get();
        $owner=$task->project->workspace->owner()->where("id","<>",Auth::user()->id)->first();

        if($owner){
        $assignees->push($owner);
        }

         $data = [
            "task" => $task->name,
            "message" => $user->name ." has changed the " . $task->title . " task status ",
            'type' => 'membership_actions',
        ];

        Notification::send($assignees,new ChnageStatusNotification($data));
        
        return redirect()->back()->with("change-status", "status changed successfully");
    }

    public function deleteAssignee(Task $task, User $user)
    {
        Gate::authorize("manageWorkspace", $task->project->workspace);

        $task->users()->detach($user->id);


        $data = [
            "task_id" => $task->id,
            "message" => 'You have beeen removed from ' . $task->title . " task",
            'url' => "#",
            'type' => 'task_assignment',
        ];

        $user->notify(new TaskAssignedNotification($data));

        return redirect()->back()->with("delete-assignee", "assignee deleted  successfully");
    }

    public function view(Project $project, Task $task)
    {
        $members = $project->workspace->users()->wherePivot("role", "<>", "owner")->get();

        return view("tasks.show", get_defined_vars());
    }
}
