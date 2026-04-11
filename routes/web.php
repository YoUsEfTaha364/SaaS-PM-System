<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskFilterController;
use App\Http\Controllers\WorkspaceController;

use App\Http\Controllers\WorkspaceInvitationController;
use App\Http\Controllers\WorkspaceMemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name("welcome");

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::controller(WorkspaceController::class)->middleware("auth")->group(function () {
    Route::get("/workspaces/create", "create")->name("workspaces.create");

    Route::get(
        '/workspaces/{workspace}',
        'show'
    )->name('workspaces.show');

    Route::get("/workspaces", "index")->name("workspaces.index");
    Route::post("/workspaces", "store")->name("workspaces.store");
    Route::put('/workspaces/{workspace}', 'update')->name('workspaces.update');
});


Route::controller(WorkspaceMemberController::class)->middleware("auth")->group(function () {
    
    Route::post("/workspaces/{workspace}/members", "store")->name("workspaces.members.store");
    Route::delete("/workspaces/{workspace}/members/{user}", "delete")->name("workspaces.members.delete");

    Route::put("/workspaces/{workspace}/members/{user}", "update")->name("workspaces.members.update");
});


Route::controller(ProjectController::class)->middleware("auth")->group(function () {

     Route::get("/projects", "index")->name("workspaces.projects.index");
     
    Route::post("/workspaces/{workspace}/projects", "store")->name("workspaces.projects.store");

    Route::get("/workspaces/{workspace}/projects/{project}", "show")->name("workspaces.projects.show");
    Route::put("/workspaces/{workspace}/projects/{project}", "update")->name("workspaces.projects.update");
});
Route::controller(TaskController::class)->middleware("auth")->group(function () {


     
     Route::get("/tasks", "index")->name("tasks.index");
     Route::post("/projects/{project}/tasks", "store")->name("projects.tasks.store");

     Route::patch("/tasks/{task}/assign", "assignTask")->name("tasks.assign");
     
     Route::patch("/tasks/{task}/users/{user}/status", "changeStatus")->name("tasks.change-status");

     Route::delete("/tasks/{task}/assignees/{user}", "deleteAssignee")->name("tasks.assignees.delete");

     Route::get("/projects/{project}/tasks/{task}", "view")->name("projects.tasks.show");

    // Route::get("/workspaces/{workspace}/projects/{project}", "show")->name("workspaces.projects.show");

   
});


 Route::prefix("notifications")->controller(NotificationController::class)->middleware("auth")->group(function(){
   route::get("/","index")->name("notifications.index");
    Route::get('/reload', 'reload')->name('notifications.reload');
    Route::post('/mark-all-as-read', 'markAllAsRead')->name('notifications.markAllAsRead');
 });
  
  Route::prefix("workspace/invitation/")->controller(WorkspaceInvitationController::class)->middleware("auth")->group(function(){
 

    route::post("registered/accept","accept_invitation_registered")->name("workspace_registered_invitation_accept");

    
  });

  route::match(["get","post"],"workspace/invitation/non-registered/accept/{token}",[WorkspaceInvitationController::class,"accept_invitation_non_registered"])->name("workspace_non_registered_invitation_accept");


  Route::controller(CommentController::class)->prefix("tasks/")->name("tasks.comments.")->middleware("auth")->group(function(){

  Route::post("{task}/comments","store")->name("store");
  Route::post("{task}/comments/{comment}/replies","storeReplyComment")->name("replies.store");

  });
  Route::controller(AttachmentController::class)->prefix("attachments/")->name("attachments.")->middleware("auth")->group(function(){

    Route::get("download/{attachment}","download")->name("download");

  });
  Route::controller(TaskFilterController::class)->prefix("tasks/")->name("tasks.")->middleware("auth")->group(function(){

    Route::post("filter","filter")->name("filter");

  });


require __DIR__ . '/auth.php';
