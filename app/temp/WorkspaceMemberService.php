<?php

namespace App\Services;

use App\Events\WorkspaceInvitationEvent;
use App\Mail\InvoiceMail;
use App\Models\User;
use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use App\Notifications\MemberActionsNotification;

use App\Notifications\WorkspaceInvitation as WorkspaceInvitationNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class WorkspaceMemberService
{

    public function addMember(array $validated_data, Workspace $workspace)
    {


        $user = User::where("email", $validated_data["email"])->first();

           if (! $user) {
            $this->non_registered($validated_data, $workspace);
            return;
        }

        

        $this->alreadyMember($user,$workspace);

        $this->registered($validated_data, $workspace, $user);

     
    }

    private function non_registered(array $data,Workspace  $workspace)
    {

        // create invitation with token 

         $invitation=$this->create_invitation($data,$workspace);

        // url that user will user to make register

        $url = route(
            'workspace_non_registered_invitation_accept',
            $invitation->token
        );

        //data will be used in emai;l content

        $mailData = [
            "workspace" => $workspace->name,
            "email" => $data["email"],
            "url" => $url,
        ];

        Mail::to($mailData["email"])->send(new InvoiceMail($mailData));
    }
    private function registered(array $data,Workspace  $workspace,User $user)
    {

        // create invitation with token 
        $invitation=WorkspaceInvitation::where("email",$data["email"])->first();

        if ($invitation && now()->lessThan($invitation->expires_at) ) {

            throw ValidationException::withMessages([
                "email"=>"member already invited"

            ]);
        }

       $invitation=$this->create_invitation($data,$workspace);


        //data will be used in emai;l content

        $notification_data = [
            "workspace" => $workspace->name,
            "token" => $invitation->token,
            "email" => $data["email"],
            "type" => "workspace_invitation"
        ];
        
        //send notification
        $user->notify(new WorkspaceInvitationNotification($notification_data));
    }

    private function alreadyMember(User $user,Workspace $workspace)
    {
        $is_member=$workspace->users()->where("user_id",$user->id)->exists();

        if ($is_member) {

            throw ValidationException::withMessages([
                "email"=>"member already exists"

            ]);
        }
    }


    private function create_invitation(array $validated_data,Workspace $workspace){
       
         $invitation =  WorkspaceInvitation::create([
                "workspace_id" => $workspace->id,
                "email" => $validated_data["email"],
                "role" => $validated_data["role"],
                "token" => Str::random(30),
                "expires_at" => now()->addDays(7),
            ]);

            return $invitation;
        

    }

    public function removeMember(Workspace $workspace, User $user)
    {
        
        // Check if the user is a member of the workspace
        if (!$workspace->users()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                "email" => "User is not a member of this workspace."
            ]);
        }

        $workspace->users()->detach($user->id);

        foreach($workspace->projects as $project){

           foreach($project->tasks as $task){
              $task->users()->detach($user->id);

           }
        }
        $data=[
            "workspace"=>$workspace->name,
            "message"=>"You are removed from ".$workspace->name." workspace",
            "type"=>"membership_actions",
        ];

        $user->notify(new MemberActionsNotification($data));
    }

    public function updateMemberRole(Workspace $workspace, User $user, string $role)
    {
        // Check if the user is a member of the workspace
        if (!$workspace->users()->where('user_id', $user->id)->exists()) {
            throw ValidationException::withMessages([
                "email" => "User is not a member of this workspace."
            ]);
        }


        $workspace->users()->updateExistingPivot($user->id, ['role' => $role]);

         $data=[
            "workspace"=>$workspace->name,
            "message"=>"You became a {$role} in  ".$workspace->name." workspace",
            "type"=>"membership_actions",
        ];
        

        $user->notify(new MemberActionsNotification($data));

    }
}
