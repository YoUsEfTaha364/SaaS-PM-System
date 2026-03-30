@extends('layouts.app')

@section('title', 'Task Details')
@section('tasks-active', 'bg-indigo-50 text-indigo-600 font-medium')

@section('main-content')
<div class="p-4 sm:p-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-800">{{ $task->title }}</h1>
                <p class="text-sm text-gray-500 mt-2">
                    In project: <a href="{{ route('workspaces.projects.show', [$project->workspace, $project]) }}" class="font-medium text-indigo-600 hover:underline">{{ $project->name }}</a>
                </p>
            </div>
            <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Back
            </a>
        </div>

        <!-- Flash Messages -->
        @if ($errors->any() || session('change-status') || session('create-comment') || session('assign-task') || session('delete-assignee'))
        <div class="mb-6 space-y-2">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @foreach (['change-status', 'create-comment', 'assign-task', 'delete-assignee'] as $msg)
                @if (session($msg))
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
                        {{ session($msg) }}
                    </div>
                @endif
            @endforeach
        </div>
        @endif

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Column (Task Description & Comments) -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Description -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Description</h3>
                    <div class="prose prose-sm max-w-none text-gray-600">
                        {!! $task->description ? nl2br(e($task->description)) : '<p class="text-gray-400 italic">No description provided.</p>' !!}
                    </div>
                </div>

                <!-- Comments -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-800">Comments</h3>
                        <button onclick="document.getElementById('commentForm').classList.toggle('hidden')"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Add Comment
                        </button>
                    </div>

                    <form method="POST" action="{{ route('tasks.comments.store', $task) }}" id="commentForm" class="hidden mb-8 transition-all">
                        @csrf
                        <textarea name="content" rows="4" placeholder="Write a comment..."
                            class="w-full border-gray-200 rounded-xl shadow-sm px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
                        <div class="mt-3 flex justify-end">
                            <button class="bg-indigo-600 text-white px-5 py-2 text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Post Comment
                            </button>
                        </div>
                    </form>

                    <div class="space-y-6">
                        @forelse ($task->comments->whereNull("parent_id")->sortByDesc('created_at') as $comment)
                            <x-comment :comment="$comment" />
                        @empty
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                <p class="text-gray-500 text-sm mt-4">No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column (Meta Info) -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Details</h3>
                    <div class="space-y-4 text-sm">
                        <!-- Status -->
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-600">Status</span>
                            @can('change_status', $task)
                                <form method="POST" action="{{ route('tasks.change-status', [$task,Auth::user()]) }}" class="relative">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()"
                                        class="text-sm border-gray-200 rounded-lg pl-3 pr-8 py-1.5 appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                        {{ $task->status == 'todo' ? 'bg-yellow-100 text-yellow-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To Do</option>
                                        <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
                                    </select>
                                    <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg>
                                </form>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium uppercase
                                    {{ $task->status == 'todo' ? 'bg-yellow-100 text-yellow-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ str_replace('_', ' ', $task->status) }}
                                </span>
                            @endcan
                        </div>
                        <!-- Due Date -->
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-600">Due Date</span>
                            <span class="text-gray-800">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'Not set' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Assignees -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Assignees</h3>
                        @can('manageWorkspace', $project->workspace)
                        <form method="POST" action="{{ route('tasks.assign', $task) }}" class="relative">
                            @csrf @method('PATCH')
                            <select name="user_id" onchange="this.form.submit()"
                                class="text-xs border-gray-200 rounded-lg pl-2 pr-7 py-1 appearance-none focus:ring-1 focus:ring-indigo-500">
                                <option value="">+ Assign User</option>
                                @foreach ($members as $member)
                                    @if(!$task->users->contains($member))
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <svg class="w-3 h-3 absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </form>
                        @endcan
                    </div>
                    <div class="space-y-3">
                        @forelse ($task->users as $user)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 text-xs flex items-center justify-center font-bold">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">{{ $user->name }}</span>
                                </div>
                                @can('manageWorkspace', $project->workspace)
                                    <form method="POST" action="{{ route('tasks.assignees.delete', [$task, $user]) }}">
                                        @csrf @method('DELETE')
                                        <button class="text-gray-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        @empty
                            <p class="text-gray-500 text-sm italic">No users assigned yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
