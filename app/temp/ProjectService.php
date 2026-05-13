<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    public function getProjectsData()
    {
        $user = Auth::user();
        $projects = Project::whereIn('workspace_id', $user->workspaces->pluck('id'))
            ->with(['workspace', 'tasks'])
            ->withCount('tasks')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        $workspaces = $user->workspaces;

        return compact('projects', 'workspaces');
    }

    public function storeProject(array $validated_data, Workspace $workspace)
    {
        return Project::create([
            "name" => $validated_data["name"],
            "workspace_id" => $workspace->id,
        ]);
    }

    public function updateProject(array $validated_data, Project $project)
    {
        $project->update([
            "name" => $validated_data["name"],
        ]);

        return $project;
    }

    public function getProjectViewData(Workspace $workspace, Project $project): array
    {
        return compact('project');
    }
}