<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Workspace;
use App\Services\api\ApiResponseService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        $data = $this->projectService->getProjectsData();

        return ApiResponseService::response(200, "get workspace projects", $data);
    }

    public function store(Request $request, Workspace $workspace)
    {
       
        Gate::authorize("manageWorkspace", $workspace);
   

        $validated = $request->validate([
            "name" => [
                "required",
                "string",
                Rule::unique("projects")->where(function ($q) use ($workspace) {
                    return $q->where("workspace_id", $workspace->id);
                })
            ]
        ]);

        $project = $this->projectService->storeProject($validated, $workspace);

        return ApiResponseService::response(201, "project created successfully", $project);
    }

    public function update(UpdateProjectRequest $request, Workspace $workspace, Project $project)
    {
        Gate::authorize("manageWorkspace", $workspace);

        $validated = $request->validated();

        $project = $this->projectService->updateProject($validated, $project);

        return ApiResponseService::response(200, "project updated successfully", ["project"=>$project]);
    }

    public function show(Workspace $workspace, Project $project)
    {
        $data = $this->projectService->getProjectViewData($workspace, $project);

        return ApiResponseService::response(200, "get project", $data);
    }
}
