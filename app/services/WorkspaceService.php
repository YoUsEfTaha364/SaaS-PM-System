<?php

namespace App\Services;

use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;

class WorkspaceService
{
    public function getWorkspacesData()
    {
        $user = Auth::user();
        return $user->workspaces()
            ->with(['owner', 'projects', 'users'])
            ->withCount(['projects', 'users'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function storeWorkspace(array $validated_data)
    {
        $workspace = Workspace::create([
            "name" => $validated_data["name"],
            "owner_id" => Auth::user()->id,
        ]);

        $workspace->users()->attach(Auth::user()->id, [
            "role" => "owner"
        ]);

        return $workspace;
    }

    public function getWorkspaceViewData(Workspace $workspace)
    {
        return $workspace->load(['owner', 'users', 'projects' => function ($query) {
            $query->withCount('tasks');
        }]);
    }
}
