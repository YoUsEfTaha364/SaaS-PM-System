@extends('layouts.app')

@section('title', 'Dashboard')

@section('main-content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Welcome back, {{ Auth::user()->name }}!</h1>
            <p class="text-gray-500 mt-2">Here's what's happening with your projects today.</p>
        </div>
        <a href="{{ route('workspaces.create') }}" class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <span>New Workspace</span>
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-stat-card title="Workspaces" value="{{ $workspacesCount }}" icon="workspaces" color="blue" />
        <x-stat-card title="Projects" value="{{ $projectsCount }}" icon="projects" color="indigo" />
        <x-stat-card title="Tasks" value="{{ $tasksCount }}" icon="tasks" color="purple" />
        <x-stat-card title="Completed Tasks" value="{{ $completedTasksCount }}" icon="completed-tasks" color="green" />
    </div>

    @if ($workspacesCount > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- My Tasks -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">My Tasks</h3>
                <div class="space-y-4">
                    @forelse ($myTasks as $task)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <a href="{{ route('projects.tasks.show', [$task->project, $task]) }}" class="font-medium text-gray-800 hover:text-indigo-600">{{ $task->title }}</a>
                                <p class="text-sm text-gray-500">{{ $task->project->name }}</p>
                            </div>
                            <span class="text-xs font-medium px-2 py-1 rounded-full
                                {{ $task->status == 'todo' ? 'bg-yellow-100 text-yellow-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ str_replace('_', ' ', $task->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">You have no assigned tasks.</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                <div class="space-y-5">
                    @forelse ($recentActivities as $activity)
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex-shrink-0"></div>
                            <div>
                                <p class="text-sm text-gray-700">{{ $activity->description }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic">No recent activity.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center bg-white p-12 rounded-2xl shadow-sm border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <h2 class="text-xl font-semibold text-gray-700 mt-5 mb-3">
                No Workspaces Yet
            </h2>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                Workspaces are the foundation of your projects. Create your first one to get started.
            </p>
            <a href="{{ route('workspaces.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:bg-indigo-700 transition">
                Create First Workspace
            </a>
        </div>
    @endif
</div>
@endsection
