<?php

namespace App\Http\Controllers;

use App\Http\Requests\createWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Models\Workspace;
use App\Services\WorkspaceService;

use Illuminate\Http\Request;
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

        return view('workspaces.index', compact('workspaces'));
    }

    public function create()
    {
        return view("workspaces.create");
    }

    public function store(\App\Http\Requests\createWorkspaceRequest $request)
    {
   
        $validated = $request->validated();

        $this->workspaceService->storeWorkspace($validated);

        return redirect()->back()->with("create-workspace", "workspace created successfully");
    }

    public function update(UpdateWorkspaceRequest $request, Workspace $workspace)
    {
        Gate::authorize("manageWorkspace", $workspace);

        $validated = $request->validated();

        $this->workspaceService->updateWorkspace($validated, $workspace);

        return redirect()->back()->with("update-workspace", "Workspace updated successfully");
    }


    public function show(Workspace $workspace)
    {
        if (!Auth::user()->workspaces->contains($workspace->id)) {
            abort(403);
        }

        $workspace = $this->workspaceService->getWorkspaceViewData($workspace);

        return view('workspaces.show', compact('workspace'));
    }
}
