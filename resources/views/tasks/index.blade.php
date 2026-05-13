@extends('layouts.app')

@section('title', 'My Tasks')

@section('main-content')
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">My Tasks</h1>
                <p class="text-gray-500 mt-2">All tasks assigned to you or in workspaces you manage.</p>
            </div>
        </div>

        @php
            $hasFilters = request()->filled('workspace_id') || request()->filled('due_date') || request()->filled('status') || request()->filled('search');
            
            // Merge and remove duplicates
            $allTasks = $assigned_tasks->concat($owned_tasks)->unique('id');
            $hasTasks = $allTasks->count() > 0;

            // Group tasks
            $overdueTasks = $allTasks->filter(function($task) {
                return $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && !\Carbon\Carbon::parse($task->due_date)->isToday() && $task->status !== 'done';
            });
            
            $dueTodayTasks = $allTasks->filter(function($task) {
                return $task->due_date && \Carbon\Carbon::parse($task->due_date)->isToday() && $task->status !== 'done';
            });
            
            $upcomingTasks = $allTasks->filter(function($task) {
                return $task->due_date && \Carbon\Carbon::parse($task->due_date)->isFuture() && !\Carbon\Carbon::parse($task->due_date)->isToday() && $task->status !== 'done';
            });

            $noDateTasks = $allTasks->filter(function($task) {
                return !$task->due_date && $task->status !== 'done';
            });

            $completedTasks = $allTasks->filter(function($task) {
                return $task->status === 'done';
            });

            $taskGroups = [
                ['title' => 'Overdue', 'tasks' => $overdueTasks, 'color' => 'red'],
                ['title' => 'Due Today', 'tasks' => $dueTodayTasks, 'color' => 'yellow'],
                ['title' => 'Upcoming', 'tasks' => $upcomingTasks, 'color' => 'blue'],
                ['title' => 'No Due Date', 'tasks' => $noDateTasks, 'color' => 'gray'],
                ['title' => 'Completed', 'tasks' => $completedTasks, 'color' => 'green'],
            ];
        @endphp

        @if ($hasTasks || $hasFilters)
            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <form method="post" action="{{ route("tasks.filter") }}" class="flex flex-col sm:flex-row gap-4 w-full">
                    @csrf
                    <div class="flex-grow w-full sm:w-auto relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks by name or project..."
                            class="form-input w-full text-sm border-gray-200 rounded-lg shadow-sm pl-10" onkeypress="if(event.key === 'Enter') this.form.submit()">
                    </div>
                    
                    <div class="flex flex-wrap gap-4">
                        <div class="relative">
                        <select name="workspace_id" onchange="this.form.submit()"
                            class="form-select pl-3 pr-8 py-2 text-sm border-gray-200 rounded-lg">
                            <option value="">All Workspaces</option>
                            @foreach ($workspaces as $workspace)
                                <option value="{{ $workspace->id }}" {{ request('workspace_id') == $workspace->id ? 'selected' : '' }}>{{ $workspace->name }}</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4">
                            </path>
                        </svg>
                    </div>

                    <div class="relative">
                        <select name="due_date" onchange="this.form.submit()"
                            class="form-select pl-3 pr-8 py-2 text-sm border-gray-200 rounded-lg">
                            <option value="">All Due Dates</option>
                            <option value="overdue" {{ request('due_date') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            <option value="today" {{ request('due_date') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="tomorrow" {{ request('due_date') == 'tomorrow' ? 'selected' : '' }}>Tomorrow</option>
                            <option value="this_week" {{ request('due_date') == 'this_week' ? 'selected' : '' }}>This Week
                            </option>
                            <option value="this_month" {{ request('due_date') == 'this_month' ? 'selected' : '' }}>This Month
                            </option>
                            <option value="no_due_date" {{ request('due_date') == 'no_due_date' ? 'selected' : '' }}>No Due Date
                            </option>
                        </select>
                        <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4">
                            </path>
                        </svg>
                    </div>

                    <div class="relative">
                        <select name="status" onchange="this.form.submit()"
                            class="form-select pl-3 pr-8 py-2 text-sm border-gray-200 rounded-lg">
                            <option value="">All Statuses</option>
                            <option value="todo" {{ request('status') == 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress
                            </option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                        <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4">
                            </path>
                        </svg>
                    </div>
                    </div>
                </form>
            </div>
        @endif

        @if ($hasTasks)
            <div class="space-y-10">
                @foreach ($taskGroups as $group)
                    @if ($group['tasks']->count() > 0)
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <h2 class="text-lg font-bold text-gray-800">{{ $group['title'] }}</h2>
                                <span class="bg-{{ $group['color'] }}-100 text-{{ $group['color'] }}-700 py-0.5 px-2.5 rounded-full text-xs font-bold">{{ $group['tasks']->count() }}</span>
                            </div>
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-100">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Task</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Project</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">View</span></th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-50">
                                            @foreach ($group['tasks'] as $task)
                                                <tr class="hover:bg-gray-50 transition-colors group cursor-pointer" onclick="window.location='{{ route('projects.tasks.show', [$task->project, $task]) }}'">
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center">
                                                            @if($group['title'] === 'Completed')
                                                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            @else
                                                                <div class="w-5 h-5 rounded-full border-2 border-gray-300 mr-3 group-hover:border-indigo-400 transition-colors"></div>
                                                            @endif
                                                            <div>
                                                                <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                                    class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $task->title }}</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $task->project->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $task->project->workspace->name }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($task->due_date)
                                                            <span class="text-sm {{ $group['title'] === 'Overdue' ? 'text-red-600 font-medium' : 'text-gray-500' }}">
                                                                {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                                            </span>
                                                        @else
                                                            <span class="text-sm text-gray-400 italic">No date</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $task->status == 'done' ? 'bg-green-100 text-green-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                            {{ str_replace('_', ' ', $task->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                            class="text-gray-400 hover:text-indigo-600 transition-colors">
                                                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @elseif ($hasFilters)
            <!-- Empty State for Filters -->
            <div class="text-center bg-white p-12 rounded-2xl shadow-sm border border-gray-100 mt-6">
                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mt-5 mb-3">
                    No tasks found
                </h3>
                <p class="text-gray-500 max-w-sm mx-auto mb-6">
                    We couldn't find any tasks matching your current filters.
                </p>
                <a href="{{ route('tasks.index') }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-50 hover:bg-indigo-100">
                    Clear Filters
                </a>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center bg-white p-12 rounded-2xl shadow-sm border border-gray-100 mt-6">
                <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mt-5 mb-3">
                    You're all caught up!
                </h3>
                <p class="text-gray-500 max-w-sm mx-auto">
                    You have no tasks assigned to you. Enjoy the peace and quiet!
                </p>
            </div>
        @endif
    </div>
@endsection