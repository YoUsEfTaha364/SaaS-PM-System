<?php

namespace App\Services;

use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Notifications\AcceptWorkspaceInvitation;
use Illuminate\Support\Facades\Auth;

class WorkspaceInvitationService
{
    public function acceptRegisteredInvitation(string $token)
    {
        $invitation = WorkspaceInvitation::where("token", $token)->firstOrFail();

        $workspace = Workspace::where("id", $invitation->workspace_id)->firstOrFail();

        // check if invitation still permitted
        abort_if(now()->greaterThan($invitation->expires_at), 403, "invitation expired");

        // add user to the workspace
        $workspace->users()->attach(Auth::user()->id, [
            "role" => $invitation->role
        ]);

        // delete the invitation
        $invitation->delete();

        // mark notification as read
        Auth::user()->unreadNotifications
            ->where('data.token', $token)
            ->markAsRead();

        // get owner to re-notify
        $owner = $workspace->owner()->first();

        if ($owner) {
            $notificationData = [
                "name" => Auth::user()->name,
                "email" => Auth::user()->email,
                "type" => "accept_invitation",
            ];

            $owner->notify(new AcceptWorkspaceInvitation($notificationData));
        }

        return $workspace;
    }

    public function acceptNonRegisteredInvitation(string $token)
    {
        session(["workspace_invitation_token" => $token]);
    }
}
