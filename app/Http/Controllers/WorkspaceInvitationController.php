<?php

namespace App\Http\Controllers;

use App\Services\WorkspaceInvitationService;
use Illuminate\Http\Request;

class WorkspaceInvitationController extends Controller
{
    protected $invitationService;

    public function __construct(WorkspaceInvitationService $invitationService)
    {
        $this->invitationService = $invitationService;
    }

    public function accept_invitation_registered(Request $request)
    {
        $workspace = $this->invitationService->acceptRegisteredInvitation($request->token);

        return redirect()->route("workspaces.show", $workspace);
    }

    public function accept_invitation_non_registered($token)
    {
        $this->invitationService->acceptNonRegisteredInvitation($token);
       
        return redirect()->route("welcome");
    }
}
