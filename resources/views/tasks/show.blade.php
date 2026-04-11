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
                        In project: <a href="{{ route('workspaces.projects.show', [$project->workspace, $project]) }}"
                            class="font-medium text-indigo-600 hover:underline">{{ $project->name }}</a>
                    </p>
                </div>
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back
                </a>
            </div>

            <!-- Flash Messages -->
            @if (
                $errors->any() ||
                    session('change-status') ||
                    session('create-comment') ||
                    session('assign-task') ||
                    session('delete-assignee'))
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
                            {!! $task->description
                                ? nl2br(e($task->description))
                                : '<p class="text-gray-400 italic">No description provided.</p>' !!}
                        </div>
                    </div>

                    <!-- Attachments -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Attachments</h3>
                        <div class="space-y-4">
                            @forelse ($task->attachments as $file)
                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <div>
                                            <a href="{{ asset('storage/attachments/' . $file->file_path) }}" target="_blank"
                                                class="text-sm font-medium text-indigo-600 hover:underline">{{ $file->file_name }}</a>
                                            <p class="text-xs text-gray-500">
                                                Uploaded by {{ $file->user->name ?? 'Unknown' }} &middot;
                                                {{ round($file->size / 1024, 1) }} KB
                                            </p>
                                        </div>
                                    </div>
                                    <a href="{{ route('attachments.download', $file) }}"
                                        class="text-gray-400 hover:text-gray-600" title="Download">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                    </a>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic">No attachments for this task.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Comments -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-800">Comments</h3>
                            <button onclick="document.getElementById('commentForm').classList.toggle('hidden')"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Comment
                            </button>
                        </div>

                        <form method="POST" action="{{ route('tasks.comments.store', $task) }}" id="commentForm"
                            class="hidden mb-8 transition-all" enctype="multipart/form-data">
                            @csrf
                            <textarea name="content" rows="4" placeholder="Write a comment..."
                                class="w-full border-gray-200 rounded-xl shadow-sm px-4 py-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>
                            <div class="mt-3 flex items-center justify-between">
                                <input type="file" name="files[]" multiple
                                    class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors" />
                                <button
                                    class="bg-indigo-600 text-white px-5 py-2 text-sm font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    Post Comment
                                </button>
                            </div>
                        </form>

                        <div class="space-y-6">
                            @forelse ($task->comments as $comment)
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 text-xs flex items-center justify-center font-bold flex-shrink-0 mt-1">
                                        {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-baseline justify-between">
                                            <div>
                                                <span
                                                    class="font-semibold text-gray-800 text-sm">{{ $comment->user->name }}</span>
                                                <span
                                                    class="text-xs text-gray-500 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="prose prose-sm max-w-none text-gray-600 mt-1">
                                            {!! nl2br(e($comment->content)) !!}
                                        </div>

                                        @if ($comment->attachments->count() > 0)
                                            <div class="mt-3 space-y-2">
                                                @foreach ($comment->attachments as $file)
                                                    <div class="flex items-center gap-3 px-3 py-2 bg-gray-50 rounded-lg">
                                                        <svg class="w-5 h-5 text-gray-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.415a6 6 0 108.486 8.486L20 13">
                                                            </path>
                                                        </svg>
                                                        <a href="{{ asset('storage/attachments/' . $file->file_path) }}"
                                                            target="_blank"
                                                            class="text-sm font-medium text-indigo-600 hover:underline">{{ $file->file_name }}</a>
                                                        <span
                                                            class="text-xs text-gray-500">({{ round($file->size / 1024, 1) }}
                                                            KB)</span>
                                                        <a href="{{ route('attachments.download', $file) }}"
                                                            class="ml-auto text-gray-400 hover:text-gray-600"
                                                            title="Download">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                                </path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Reply Button & Nested Form -->
                                        <div class="mt-3">
                                            <button type="button"
                                                onclick="document.getElementById('replyForm-{{ $comment->id }}').classList.toggle('hidden')"
                                                class="text-xs font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
                                                Reply
                                            </button>

                                            <form method="POST"
                                                action="{{ route('tasks.comments.replies.store', [$task, $comment]) }}"
                                                id="replyForm-{{ $comment->id }}" class="hidden mt-3 transition-all"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <textarea name="content" rows="2" placeholder="Write a reply..."
                                                    class="w-full border-gray-200 rounded-xl shadow-sm px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition"></textarea>

                                                <input type="file" name="files[]" multiple
                                                    class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-colors" />
                                                <div class="mt-2 flex justify-end">
                                                    <button type="submit"
                                                        class="bg-indigo-600 text-white px-4 py-1.5 text-xs font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">Post
                                                        Reply</button>
                                                </div>
                                            </form>
                                        </div>

                                        <!-- Replies -->
                                        @if ($comment->replyComments->count() > 0)
                                            <div class="mt-6 pl-8 border-l-2 border-gray-100 space-y-5">
                                                @foreach ($comment->replyComments as $reply)
                                                    <div class="flex items-start gap-4">
                                                        <div
                                                            class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 text-xs flex items-center justify-center font-bold flex-shrink-0 mt-1">
                                                            {{ strtoupper(substr($reply->user->name, 0, 2)) }}
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="flex items-baseline justify-between">
                                                                <div>
                                                                    <span
                                                                        class="font-semibold text-gray-800 text-sm">{{ $reply->user->name }}</span>
                                                                    <span
                                                                        class="text-xs text-gray-500 ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="prose prose-sm max-w-none text-gray-600 mt-1">
                                                                {!! nl2br(e($reply->content)) !!}
                                                            </div>

                                                            @if ($reply->attachments->count() > 0)
                                                                <div class="mt-3 space-y-2">
                                                                    @foreach ($reply->attachments as $file)
                                                                        <div
                                                                            class="flex items-center gap-3 px-3 py-2 bg-gray-50 rounded-lg">
                                                                            <svg class="w-5 h-5 text-gray-400"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.415a6 6 0 108.486 8.486L20 13">
                                                                                </path>
                                                                            </svg>
                                                                            <a href="{{ asset('storage/attachments/' . $file->file_path) }}"
                                                                                target="_blank"
                                                                                class="text-sm font-medium text-indigo-600 hover:underline">{{ $file->file_name }}</a>
                                                                            <span
                                                                                class="text-xs text-gray-500">({{ round($file->size / 1024, 1) }}
                                                                                KB)</span>
                                                                            <a href="{{ route('attachments.download', $file) }}"
                                                                                class="ml-auto text-gray-400 hover:text-gray-600"
                                                                                title="Download">
                                                                                <svg class="w-4 h-4" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4">
                                                                                    </path>
                                                                                </svg>
                                                                            </a>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                        </path>
                                    </svg>
                                    <p class="text-gray-500 text-sm mt-4">No comments yet. Be the first to share your
                                        thoughts!</p>
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
                                    <form method="POST" action="{{ route('tasks.change-status', [$task, Auth::user()]) }}"
                                        class="relative">
                                        @csrf @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="text-sm border-gray-200 rounded-lg pl-3 pr-8 py-1.5 appearance-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                        {{ $task->status == 'todo' ? 'bg-yellow-100 text-yellow-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                            <option value="todo" {{ $task->status == 'todo' ? 'selected' : '' }}>To Do
                                            </option>
                                            <option value="in_progress"
                                                {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done
                                            </option>
                                        </select>
                                        <svg class="w-4 h-4 absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                        </svg>
                                    </form>
                                @else
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium uppercase
                                    {{ $task->status == 'todo' ? 'bg-yellow-100 text-yellow-800' : ($task->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                        {{ str_replace('_', ' ', $task->status) }}
                                    </span>
                                @endcan
                            </div>
                            <!-- Due Date -->
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-600">Due Date</span>
                                <span
                                    class="text-gray-800">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'Not set' }}</span>
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
                                            @if (!$task->users->contains($member))
                                                <option value="{{ $member->id }}">{{ $member->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <svg class="w-3 h-3 absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </form>
                            @endcan
                        </div>
                        <div class="space-y-3">
                            @forelse ($task->users as $user)
                                <div class="flex items-center justify-between group">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 text-xs flex items-center justify-center font-bold">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">{{ $user->name }}</span>
                                    </div>
                                    @can('manageWorkspace', $project->workspace)
                                        <form method="POST" action="{{ route('tasks.assignees.delete', [$task, $user]) }}">
                                            @csrf @method('DELETE')
                                            <button
                                                class="text-gray-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm italic">No users assigned yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-6">Activity Log</h3>

                        <div class="relative pl-6 space-y-6 max-h-[500px] overflow-y-auto pr-2 pb-2 custom-scrollbar">
                            <!-- Vertical line -->
                            <div class="absolute top-2 bottom-0 left-[11px] w-px bg-gray-200"></div>

                            @forelse ($task->activities->sortByDesc('created_at') as $activity)
                                <div class="relative flex flex-col group">
                                    <!-- Dynamic Icon Background -->
                                    @php
                                        $iconColor = match($activity->event) {
                                            'created', 'completed' => 'text-emerald-600 bg-emerald-100 ring-white',
                                            'status_changed' => 'text-blue-600 bg-blue-100 ring-white',
                                            'assigned', 'unassigned' => 'text-purple-600 bg-purple-100 ring-white',
                                            'comment_added', 'comment_replied' => 'text-yellow-600 bg-yellow-100 ring-white',
                                            'deleted' => 'text-red-600 bg-red-100 ring-white',
                                            default => 'text-gray-600 bg-gray-100 ring-white'
                                        };
                                    @endphp
                                    <div class="absolute -left-[30px] top-1 w-6 h-6 rounded-full flex items-center justify-center z-10 shadow-sm ring-4 {{ $iconColor }}">
                                        @if(in_array($activity->event, ['created', 'completed']))
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        @elseif(in_array($activity->event, ['status_changed']))
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                        @elseif(in_array($activity->event, ['assigned', 'unassigned']))
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        @elseif(in_array($activity->event, ['comment_added', 'comment_replied']))
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        @else
                                            <div class="w-1.5 h-1.5 rounded-full bg-current"></div>
                                        @endif
                                    </div>

                                    <div class="text-xs text-gray-400 mb-1 flex items-center gap-2">
                                        <time datetime="{{ $activity->created_at->toIso8601String() }}">
                                            {{ $activity->created_at->format('M d, Y') }} at {{ $activity->created_at->format('g:i A') }}
                                        </time>
                                        <span class="text-gray-300 opacity-0 group-hover:opacity-100 transition-opacity">&bull; {{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600 leading-snug bg-gray-50 border border-gray-100 p-2.5 rounded-lg inline-block self-start mt-0.5">
                                        <strong class="font-semibold text-gray-900">{{ $activity->user->name ?? 'Unknown' }}</strong>
                                        
                                        @switch($activity->event)
                                            @case('created')
                                                created the task
                                            @break
                                            
                                            @case('status_changed')
                                                changed status from 
                                                @php $oldStatus = $activity->old_values['status'] ?? 'todo'; @endphp
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider {{ $oldStatus == 'done' ? 'bg-green-100 text-green-800' : ($oldStatus == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ str_replace('_', ' ', $oldStatus) }}
                                                </span>
                                                to 
                                                @php $newStatus = $activity->new_values['status'] ?? 'todo'; @endphp
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider {{ $newStatus == 'done' ? 'bg-green-100 text-green-800' : ($newStatus == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ str_replace('_', ' ', $newStatus) }}
                                                </span>
                                            @break

                                            @case('assigned')
                                                assigned the task to 
                                                <strong class="font-medium text-gray-900">{{ \App\Models\User::find($activity->new_values['assigned_to'])->name ?? 'User' }}</strong>
                                            @break

                                            @case('unassigned')
                                                removed
                                                <strong class="font-medium text-gray-900">{{ \App\Models\User::find($activity->old_values['unassigned_user_id'])->name ?? 'User' }}</strong>
                                                from the task
                                            @break

                                            @case('comment_added')
                                                added a comment
                                            @break

                                            @case('comment_replied')
                                                replied to a comment
                                            @break

                                            @case('deleted')
                                                deleted the task
                                            @break

                                            @case('completed')
                                                marked the task as completed
                                            @break

                                            @default
                                                {{ $activity->description }}
                                        @endswitch
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-4 relative z-20">
                                    <p class="text-gray-500 text-sm italic">No activity yet. Things have been quiet here!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection
