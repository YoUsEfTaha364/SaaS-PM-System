<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function getDashboardData()
    {
        $user = Auth::user();

        $workspacesCount = $user->workspaces()->count();
        $projectsCount = Project::whereIn('workspace_id', $user->workspaces->pluck('id'))->count();
        $tasks = Task::whereIn('project_id', function ($query) use ($user) {
            $query->select('id')->from('projects')->whereIn('workspace_id', $user->workspaces->pluck('id'));
        });

        $tasksCount = $tasks->count();
        $completedTasksCount = (clone $tasks)->where('status', 'done')->count();
        
        $overdueTasks = $user->tasks()->with('project')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->where('status', '!=', 'done')
            ->latest()->take(3)->get();
            
        $dueTodayTasks = $user->tasks()->with('project')
            ->whereNotNull('due_date')
            ->whereDate('due_date', now()->toDateString())
            ->where('status', '!=', 'done')
            ->latest()->take(3)->get();

        $upcomingTasks = $user->tasks()->with('project')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '>', now()->toDateString())
            ->where('status', '!=', 'done')
            ->orderBy('due_date', 'asc')->take(4)->get();

        $recentActivities = collect(); // Placeholder for recent activities

        $unreadNotificationsCount = $user->unreadNotifications()->count();

        return compact(
            'workspacesCount',
            'projectsCount',
            'tasksCount',
            'completedTasksCount',
            'overdueTasks',
            'dueTodayTasks',
            'upcomingTasks',
            'recentActivities',
            'unreadNotificationsCount'
        );
    }
}