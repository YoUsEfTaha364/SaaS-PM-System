@extends('layouts.app')

@section('title', 'All Projects')

@section('main-content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">All Projects</h1>
            <p class="text-gray-500 mt-2">A comprehensive list of all projects across your workspaces.</p>
        </div>
    </div>

    @if($projects->count() > 0)
        <!-- Filters -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-700">Filter by:</span>
                <div class="relative">
                    <select class="form-select pl-3 pr-8 py-1.5 text-sm border-gray-200 rounded-lg">
                        <option value="">All Workspaces</option>
                        @foreach ($workspaces as $workspace)
                            <option value="{{ $workspace->id }}">{{ $workspace->name }}</option>
                        @endforeach
                    </select>
                    <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-700">Sort by:</span>
                <div class="relative">
                     <select class="form-select pl-3 pr-8 py-1.5 text-sm border-gray-200 rounded-lg">
                        <option value="updated_at">Last Updated</option>
                        <option value="name">Name</option>
                    </select>
                    <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Project Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($projects as $project)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300 flex flex-col">
                    <div class="p-6 flex-grow">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            <a href="{{ route('workspaces.projects.show', [$project->workspace, $project]) }}" class="hover:text-indigo-600">{{ $project->name }}</a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-4">
                            In <a href="{{ route('workspaces.show', $project->workspace) }}" class="font-medium text-indigo-500 hover:underline">{{ $project->workspace->name }}</a>
                        </p>
                        
                        <!-- Progress Bar -->
                        @php
                            $totalTasks = $project->tasks_count;
                            $completedTasks = $project->tasks->where('status', 'done')->count();
                            $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center text-sm mb-1">
                                <span class="text-gray-600">Progress</span>
                                <span class="font-medium">{{ round($progress) }}%</span>
                            </div>
                            <div class="bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 rounded-full h-2" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-100 flex justify-between items-center">
                        <span class="text-sm text-gray-500">{{ $project->tasks_count }} Tasks</span>
                        <a href="{{ route('workspaces.projects.show', [$project->workspace, $project]) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                            View Project
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center bg-white p-12 rounded-2xl shadow-sm border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
            <h3 class="text-xl font-semibold text-gray-700 mt-5 mb-3">
                No Projects Found
            </h3>
            <p class="text-gray-500 max-w-sm mx-auto">
                Once you create projects within your workspaces, they will appear here.
            </p>
        </div>
    @endif
</div>
@endsection
