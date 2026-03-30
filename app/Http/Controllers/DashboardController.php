<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $workspacesCount = $user->workspaces()->count();
        $projectsCount = Project::whereIn('workspace_id', $user->workspaces->pluck('id'))->count();
        $tasks = Task::whereIn('project_id', function ($query) use ($user) {
            $query->select('id')->from('projects')->whereIn('workspace_id', $user->workspaces->pluck('id'));
        });

        $tasksCount = $tasks->count();
        $completedTasksCount = (clone $tasks)->where('status', 'done')->count();
        
        $myTasks = $user->tasks()->with('project')->latest()->take(5)->get();

        $recentActivities = collect(); // Placeholder for recent activities

        $unreadNotificationsCount = $user->unreadNotifications()->count();

        return view('dashboard', compact(
            'workspacesCount',
            'projectsCount',
            'tasksCount',
            'completedTasksCount',
            'myTasks',
            'recentActivities',
            'unreadNotificationsCount'
        ));
    }
}
