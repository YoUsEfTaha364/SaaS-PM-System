<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\createWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Models\Workspace;
use App\Services\api\ApiResponseService;
use App\Services\WorkspaceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WorkspaceController extends Controller
{
    protected $workspaceService;

    public function __construct(WorkspaceService $workspaceService)
    {
        $this->workspaceService = $workspaceService;
    }

    public function index()
    {
        $workspaces = $this->workspaceService->getWorkspacesData();
        $data = [
            'workspaces' => $workspaces
        ];
        return ApiResponseService::response(201, 'created workspace  successfully', $data);
    }

    public function store(createWorkspaceRequest $request)
    {
        $validated = $request->validated();
        $workspace = $this->workspaceService->storeWorkspace($validated);
        $data = [
            'workspace' => $workspace
        ];
        return ApiResponseService::response(201, 'created workspace  successfully', $data);
    }

    public function update(UpdateWorkspaceRequest $request, Workspace $workspace)
    {
      
         Gate::authorize("manageWorkspace", $workspace);

        $validated = $request->validated();

        if (!Auth::guard("api")->user()->workspaces()->where("workspace_id", $workspace->id)->exists()) {
            return ApiResponseService::response(403, 'unauthorized', []);
        }

        $workspace = $this->workspaceService->updateWorkspace($validated, $workspace);

        return ApiResponseService::response(200, 'workspace updated successfully', [
            "workspace" => $workspace
        ]);
    }

    public function show(Workspace $workspace)
    {


        if (!Auth::guard("api")->user()->workspaces()->where("workspace_id", $workspace->id)->exists()) {
            return ApiResponseService::response(403, 'unauthorized', []);
        }

        $workspaceData = $this->workspaceService->getWorkspaceViewData($workspace);

        $data = [
            "workspace" => $workspace
        ];

        return ApiResponseService::response(403, 'get a workspace', $data);
    }
}
