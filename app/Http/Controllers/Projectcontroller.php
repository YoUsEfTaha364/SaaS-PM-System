<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Workspace;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('projects.index', $data);
    }

    public function store(Request $request, Workspace $workspace)
    {
        if (Auth::user()->cannot("manageWorkspace", $workspace)) {
            abort(403);
        }

        $validated = $request->validate([
            "name" => ["required", "string", Rule::unique("projects")->where(function ($q) use ($workspace) {
                return $q->where("workspace_id", $workspace->id);
            })]
        ]);

        $this->projectService->storeProject($validated, $workspace);

        return redirect()->back()->with("add-project", "project added successfully");
    }

    public function show(Workspace $workspace, Project $project)
    {
        $data = $this->projectService->getProjectViewData($workspace, $project);
        
        return view("projects.show", $data);
    }
}
