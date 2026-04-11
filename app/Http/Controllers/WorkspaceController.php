<?php

namespace App\Http\Controllers;

use App\Http\Requests\createWorkspaceRequest;
use App\Models\Workspace;
use App\Services\WorkspaceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(createWorkspaceRequest $request)
    {
   
        $validated = $request->validated();

        $this->workspaceService->storeWorkspace($validated);

        return redirect()->back()->with("create-workspace", "workspace created successfully");
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
