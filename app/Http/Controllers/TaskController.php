<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    protected  $task_service;

    public function __construct(TaskService $service)
    {
        $this->task_service=$service;    
    }
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
    public function store(TaskRequest $request, Project $project)
    {

            Gate::authorize("manageWorkspace", $project->workspace);

        $validated = $request->validated();

        $this->task_service->storeTask($validated, $project);

        return redirect()->back()->with("add-task", "task added successfully");
    }

    public function assignTask(Request $request, Task $task)
    {

        Gate::authorize("manageWorkspace", $task->project->workspace);

        $validated = $request->validate([
            "user_id" => ["required", "exists:users,id"]
        ]);
        
        $assigned = $this->task_service->assignTask($validated, $task);
        
        if (!$assigned) {
            return redirect()->back()->with("assigned-user", "user already assigned");
        }

        return redirect()->back()->with("assign-task", "task assigned successfully");
    }

    public function changeStatus(Request $request, Task $task, User $user)
    {

        Gate::authorize("change_status", $task);

        $validated = $request->validate([
            "status" => ["required", "in:in_progress,todo,done"]
        ]);

        $this->task_service->changeStatus($validated, $task, $user);

        return redirect()->back()->with("change-status", "status changed successfully");
    }

    public function deleteAssignee(Task $task, User $user)
    {
        Gate::authorize("manageWorkspace", $task->project->workspace);

        $this->task_service->deleteAssignee($task, $user);

        return redirect()->back()->with("delete-assignee", "assignee deleted  successfully");
    }

    public function view(Project $project, Task $task)
    {
        $data = $this->task_service->getTaskViewData($project, $task);
        
        return view("tasks.show", $data);
    }
}
