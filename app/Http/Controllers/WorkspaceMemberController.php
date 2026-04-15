<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation as ModelsWorkspaceInvitation;
use App\Notifications\WorkspaceInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Services\WorkspaceMemberService;

class WorkspaceMemberController extends Controller
{

    private $workspaceService;

    public function __construct(WorkspaceMemberService $service)
    {

        $this->workspaceService = $service;
    }

    public function store(Request $request, Workspace $workspace)
    {

        Gate::authorize("manageWorkspace", $workspace);

        $validated = $request->validate([
            "email" => ["required", "email"],
            "role" => ["required", "string", "in:member,manager"],
        ]);


        $this->workspaceService->addMember($validated, $workspace);


        return back()->with(["success_invitation" => "invitation sent successfully"]);
    }

    public function delete(Workspace $workspace, User $user)
    {
        Gate::authorize("manageWorkspace", $workspace);
        $this->workspaceService->removeMember($workspace, $user);

        return back()->with(["delete-member" => "member deleted successfully"]);
    }
    public function update(Request $request, Workspace $workspace, User $user)
    {
        Gate::authorize("manageWorkspace", $workspace);

        $validated = $request->validate([
            "role" => "required|string|max:50|in:manager,member"
        ]);


        $this->workspaceService->updateMemberRole($workspace, $user, $validated["role"]);

        return back()->with(["update-member" => "member updated successfully"]);

    }
}
