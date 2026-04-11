@extends('layouts.app')

@section('title', 'My Tasks')

@section('main-content')
    <div class="space-y-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">My Tasks</h1>
                <p class="text-gray-500 mt-2">All tasks assigned to you across all projects.</p>
            </div>
        </div>

        @php
            $hasFilters = request()->filled('workspace_id') || request()->filled('due_date') || request()->filled('status');
            $hasAssignedTasks = $assigned_tasks->count() > 0;
            $hasOwnedTasks = $owned_tasks->count() > 0;
            $hasTasks = $hasAssignedTasks || $hasOwnedTasks;
        @endphp

        @if ($hasTasks || $hasFilters)
            <!-- Filters -->
            <div class="flex flex-col sm:flex-row gap-4 items-center">
                <div class="flex-grow w-full sm:w-auto">
                    <input type="text" placeholder="Search tasks..."
                        class="form-input w-full text-sm border-gray-200 rounded-lg shadow-sm">
                </div>
                <form method="post" action="{{ route("tasks.filter") }}" class="flex gap-4">
                    @csrf
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
                </form>
            </div>
        @endif

        @if ($hasTasks)
            @if ($hasAssignedTasks)
                <h2 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Assigned To Me</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Task
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Project
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Due
                                        Date</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">View</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($assigned_tasks as $task)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ $task->title }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $task->project->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $task->project->workspace->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $task->status == 'done' ? 'bg-green-100 text-green-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ str_replace('_', ' ', $task->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            @if ($hasOwnedTasks)
                <h2 class="text-xl font-semibold text-gray-800 mt-8 mb-4">In My Workspaces</h2>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Task
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Project
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Due
                                        Date</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status
                                    </th>
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">View</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach ($owned_tasks as $task)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ $task->title }}</a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $task->project->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $task->project->workspace->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $task->status == 'done' ? 'bg-green-100 text-green-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ str_replace('_', ' ', $task->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}"
                                                class="text-indigo-600 hover:text-indigo-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
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