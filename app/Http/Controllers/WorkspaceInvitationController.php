<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Notifications\AcceptWorkspaceInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceInvitationController extends Controller
{




    public function accept_invitation_registered(Request $request)
    {
        $token = $request->token;

        $invitation = WorkspaceInvitation::where("token", $token)->firstOrFail();

        $workspace = Workspace::where("id", $invitation->workspace_id)->firstOrFail();

        //chaeck if invitation still permitted

        abort_if(now()->greaterThan($invitation->expires_at), 403, "invitation expired");

        //add user to the workspace

        $workspace->users()->attach(Auth::user()->id, [
            "role" => $invitation->role
        ]);

        // delete the invitation

        $invitation->delete();


        //mark notification as read

        Auth::user()->unreadNotifications
            ->where('data.token', $token)
            ->markAsRead();

        // get owner to renotify

        $owner = $workspace->owner()->first();

        $notificationData = [
            "name" => Auth::user()->name,
            "email" => Auth::user()->email,
            "type" => "accept_invitation",
        ];

        $owner->notify(new AcceptWorkspaceInvitation($notificationData));



        return redirect()->route("workspaces.show", $workspace);
    }

    public function accept_invitation_non_registered($token)
    {

      
        
        session(["workspace_invitation_token"=>$token]);
       
        return redirect()->route("welcome");
    }


}
