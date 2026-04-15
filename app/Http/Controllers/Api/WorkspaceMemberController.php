<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Workspace;
use App\Services\api\ApiResponseService;
use App\Services\api\WorkspaceMemberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorkspaceMemberController extends Controller
{
    private WorkspaceMemberService $workspaceService;

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

       $status= $this->workspaceService->addMember($validated, $workspace);

       $response_message=[
           "non_registered"=>"email invitation sent successfully",
           "already_member"=>"user already member",
           "already_invited"=>"user already notified to join",
           "notified"=>"user notified to join successfully",
           "empty"=>"unknown action",
       ];



        return ApiResponseService::response(200,$response_message[$status]);
    }

    public function delete(Workspace $workspace, User $user)
    {
        Gate::authorize("manageWorkspace", $workspace);
       $message= $this->workspaceService->removeMember($workspace, $user);

            $response_message=[
           "user_not_amember"=>"undefinded user",
           "deleted"=>"workspace member deleted successfully",
          
       ];

        return ApiResponseService::response(200,$response_message[$message]);
    }

    public function update(Request $request, Workspace $workspace, User $user)
    {
       
        Gate::authorize("manageWorkspace", $workspace);

        $validated = $request->validate([
            "role" => "required|string|max:50|in:manager,member"
        ]);

        

       $message= $this->workspaceService->updateMemberRole($workspace, $user, $validated["role"]);

        
    
            $response_message=[
           "user_not_amember"=>"undefinded user",
           "updated"=>"workspace member updated successfully",
          
       ];

        return ApiResponseService::response(200,$response_message[$message]);
    }
}
