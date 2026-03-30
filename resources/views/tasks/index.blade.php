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

    @if ($tasks->count() > 0)
        <!-- Filters -->
        <div class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="flex-grow w-full sm:w-auto">
                <input type="text" placeholder="Search tasks..." class="form-input w-full text-sm border-gray-200 rounded-lg shadow-sm">
            </div>
            <div class="flex gap-4">
                <div class="relative">
                    <select class="form-select pl-3 pr-8 py-2 text-sm border-gray-200 rounded-lg">
                        <option value="">All Workspaces</option>
                        @foreach ($workspaces as $workspace)
                            <option value="{{ $workspace->id }}">{{ $workspace->name }}</option>
                        @endforeach
                    </select>
                     <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
                <div class="relative">
                    <select class="form-select pl-3 pr-8 py-2 text-sm border-gray-200 rounded-lg">
                        <option value="">All Statuses</option>
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                    </select>
                     <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Task Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Task</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Project</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">View</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($tasks as $task)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="text-sm font-medium text-gray-900 hover:text-indigo-600">{{ $task->title }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $task->project->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $task->project->workspace->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $task->status == 'done' ? 'bg-green-100 text-green-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center bg-white p-12 rounded-2xl shadow-sm border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
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
