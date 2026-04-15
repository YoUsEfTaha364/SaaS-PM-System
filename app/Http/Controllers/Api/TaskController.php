<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskDetailsResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Services\api\ApiResponseService;
use App\Services\api\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    protected TaskService $task_service;

    public function __construct(TaskService $service)
    {
        $this->task_service = $service;
    }

    public function index()
    {
        $owned_tasks = Task::whereHas("project.workspace", function ($query) {
            $query->where("owner_id", Auth::user()->id);
        })->with(["project:id,name,workspace_id","project.workspace:id,name"])->get();

        

        $user = Auth::user();

        $assigned_tasks = $user->tasks()
            ->with(["project:id,name,workspace_id","project.workspace:id,name"])
            ->orderBy('updated_at', 'desc')
            ->get();

        $workspaces = $user->workspaces;

        

        return ApiResponseService::response(200, "get all tasks", ["owned_tasks" =>TaskResource::collection($owned_tasks),"assigned_tasks"=>TaskResource::collection($assigned_tasks)]);
    }

    public function store(TaskRequest $request, Project $project)
    {
        
        Gate::authorize("manageWorkspace", $project->workspace);

        $validated = $request->validated();

       $task= $this->task_service->storeTask($validated, $project);

        return ApiResponseService::response(201, "task created successfully", ["task"=>$task]);
    }

    public function assignTask(Request $request, Task $task)
    {
        Gate::authorize("manageWorkspace", $task->project->workspace);

        $validated = $request->validate([
            "user_id" => ["required", "exists:users,id"]
        ]);

       

        $assigned = $this->task_service->assignTask($validated, $task);

        if (!$assigned) {
        return ApiResponseService::response(200, "user already a member", []);
        }

        return ApiResponseService::response(201, "user added as member successfully");
    }

    public function changeStatus(Request $request, Task $task, User $user)
    {

        Gate::authorize("change_status", $task);

        $validated = $request->validate([
            "status" => ["required", "in:in_progress,todo,done"]
        ]);
      
        $this->task_service->changeStatus($validated, $task, $user);

        return ApiResponseService::response(201, "status changed correctly");
    }

    public function deleteAssignee(Task $task, User $user)
    {
   
        Gate::authorize("manageWorkspace", $task->project->workspace);

        $this->task_service->deleteAssignee($task, $user);

      return ApiResponseService::response(201, "member deleted successfully");

    }

    public function view(Project $project, Task $task)
    {
        $data = $this->task_service->getTaskViewData($project, $task);

        return ApiResponseService::response(200, "get all tasks",new TaskDetailsResource($data));
    }
}
