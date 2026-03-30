<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Workspace;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $projects = Project::whereIn('workspace_id', $user->workspaces->pluck('id'))
            ->with(['workspace', 'tasks'])
            ->withCount('tasks')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $workspaces = $user->workspaces;

        return view('projects.index', compact('projects', 'workspaces'));
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

        $project = Project::create([
            "name" => $validated["name"],
            "workspace_id" => $workspace->id,
        ]);


        return redirect()->back()->with("add-project", "project added successfully");
    }
    public function show(Workspace $workspace, Project $project)
    {
        $members=$workspace->users()->wherePivot("role","<>","owner")->get();

        

        return view("projects.show", get_defined_vars());
    }
}
