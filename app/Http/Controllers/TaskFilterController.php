<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskFilterController extends Controller
{
    public function filter(Request $request)
    {
        $user = Auth::user();
        
        $assigned_query = $user->tasks()->with('project.workspace');
        
        $owned_query = Task::whereHas('project.workspace', function ($q) use ($user) {
            $q->where('owner_id', $user->id);
        })
        ->with('project.workspace');

        $applyFilters = function ($query) use ($request) {
            if ($request->filled('workspace_id')) {
                $query->whereHas('project', function ($q) use ($request) {
                    $q->where('workspace_id', $request->workspace_id);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('due_date')) {
                switch ($request->due_date) {
                    case 'overdue':
                        $query->whereNotNull('due_date')->whereDate('due_date', '<', now()->toDateString());
                        break;
                    case 'today':
                        $query->whereDate('due_date', now()->toDateString());
                        break;
                    case 'tomorrow':
                        $query->whereDate('due_date', now()->addDay()->toDateString());
                        break;
                    case 'this_week':
                        $query->whereBetween('due_date', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()]);
                        break;
                    case 'this_month':
                        $query->whereBetween('due_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()]);
                        break;
                    case 'no_due_date':
                        $query->whereNull('due_date');
                        break;
                }
            }
        };

        $applyFilters($assigned_query);
        $applyFilters($owned_query);

        $assigned_tasks = $assigned_query->orderBy('updated_at', 'desc')->get();
        $owned_tasks = $owned_query->orderBy('updated_at', 'desc')->get();
        
        $workspaces = $user->workspaces;

        return view('tasks.index', compact('assigned_tasks', 'owned_tasks', 'workspaces'));
    }
}
