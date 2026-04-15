<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\AttachmentController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TaskFilterController;
use App\Http\Controllers\Api\WorkspaceController;
use App\Http\Controllers\Api\WorkspaceMemberController;

Route::post('/register', [ApiAuthController::class, 'register']);//
Route::post('/login', [ApiAuthController::class, 'login']);

Route::middleware('auth:api')->group(function () { 
    Route::delete('/logout', [ApiAuthController::class, 'logout']);
    Route::get('/me', [ApiAuthController::class, 'me']);
    Route::get('/refresh', [ApiAuthController::class, 'refresh']);

     // done
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // done

    Route::controller(WorkspaceController::class)->group(function () {
        Route::get('/workspaces', 'index');  // done
        Route::post('/workspaces', 'store'); // done 
        Route::get('/workspaces/{workspace}', 'show')->whereNumber("workspace");  // done
        Route::put('/workspaces/{workspace}', 'update');  // done
    });

    // done

    Route::controller(WorkspaceMemberController::class)->group(function () {
        Route::post('/workspaces/{workspace}/members', 'store'); //done 
        Route::delete('/workspaces/{workspace}/members/{user}', 'delete'); // done
        Route::put('/workspaces/{workspace}/members/{user}', 'update');  // done
    });


    // done
    Route::controller(ProjectController::class)->group(function () {
        Route::get('/workspaces/projects', 'index');
        Route::post('/workspaces/{workspace}/projects', 'store'); 
        Route::get('/workspaces/{workspace}/projects/{project}', 'show');
        Route::put('/workspaces/{workspace}/projects/{project}', 'update');
    });


    //  done

    Route::controller(TaskController::class)->group(function () {
        Route::get('/tasks', 'index'); // done
        Route::post('/projects/{project}/tasks', 'store'); // done
        Route::put('/tasks/{task}/assign', 'assignTask'); // done
        Route::patch('/tasks/{task}/users/{user}/status', 'changeStatus'); // done
        Route::delete('/tasks/{task}/assignees/{user}', 'deleteAssignee');  // done
        Route::get('/projects/{project}/tasks/{task}', 'view');  // done
    });

    // done

    Route::prefix("notifications")->controller(NotificationController::class)->group(function(){
        Route::get("/", "index");
        Route::put('/mark-all-as-read', 'markAllAsRead');
     });

     // todo



    Route::controller(CommentController::class)->prefix("tasks/")->group(function(){
        Route::post("{task}/comments", "store"); // done
        Route::post("{task}/comments/{comment}/replies", "storeReplyComment");
    });

    Route::controller(AttachmentController::class)->prefix("attachments/")->group(function(){
        Route::get("download/{attachment}", "download");
    });



     // done
    Route::controller(TaskFilterController::class)->prefix("tasks/")->group(function(){
        Route::post("filter", "filter");
    });
});
