<?php

namespace App\Http\Controllers;

use App\Http\Requests\createWorkspaceRequest;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $workspaces = $user->workspaces()
            ->with(['owner', 'projects', 'users'])
            ->withCount(['projects', 'users'])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        return view('workspaces.index', compact('workspaces'));
    }

    public function create(){
      return view("workspaces.create");
    }


    
    public function store(createWorkspaceRequest $request){

      $validated=$request->validated();


     // create workspace


     $workspace=Workspace::create([
        "name"=>$validated["name"],
        "owner_id"=>Auth::user()->id,
     ]);


     // create workspace_user with role owner

         $workspace->users()->attach(Auth::user()->id,[
        "role"=>"owner"
     ]);


       
       return redirect()->back()->with("create-workspace","workspace created successfully");
    }


    public function show(Workspace $workspace)
    {
        if (!Auth::user()->workspaces->contains($workspace->id)) {
            abort(403);
        }

        $workspace->load(['owner', 'users', 'projects' => function ($query) {
            $query->withCount('tasks');
        }]);

        return view('workspaces.show', compact('workspace'));
    }
}
