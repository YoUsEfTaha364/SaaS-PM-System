@extends('layouts.app')

@section('title', $project->name)

@section('main-content')
<div class="p-4 sm:p-8 space-y-8">
    <!-- Project Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $project->name }}</h1>
            <p class="text-gray-500 mt-2">
                In workspace: <a href="{{ route('workspaces.show', $workspace) }}" class="font-medium text-indigo-600 hover:underline">{{ $workspace->name }}</a>
            </p>
        </div>
        @can('manageWorkspace', $workspace)
            <div class="flex space-x-2 mt-4 sm:mt-0">
                <button @click="openModal('editProject')"
                    class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-2 text-sm font-semibold rounded-lg shadow-sm hover:bg-indigo-100 transition-colors border border-indigo-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    <span>Edit</span>
                </button>
                <button @click="openModal('addTask')" class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span>New Task</span>
                </button>
            </div>
        @endcan
    </div>

    <!-- Flash Messages -->
    @if ($errors->any() || session('add-task') || session('assign-task'))
        <div class="space-y-2">
            @if(session('add-task'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('add-task') }}</div>
            @endif
            @if(session('assign-task'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('assign-task') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'] as $status => $statusLabel)
            <div class="bg-gray-50 rounded-2xl p-4">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4">{{ $statusLabel }}</h3>
                <div class="space-y-4">
                    @forelse ($project->tasks->where('status', $status) as $task)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="font-semibold text-gray-800 hover:text-indigo-600">{{ $task->title }}</a>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($task->description, 50) }}</p>
                            <div class="flex justify-between items-center mt-3">
                                <div class="flex items-center -space-x-2">
                                    @foreach ($task->users->take(3) as $user)
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 text-xs flex items-center justify-center border-2 border-white" title="{{ $user->name }}">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endforeach
                                    @if($task->users->count() > 3)
                                        <div class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 text-xs flex items-center justify-center border-2 border-white">+{{ $task->users->count() - 3 }}</div>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d') : '' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 italic px-2">No tasks in this column.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('modals')
    <!-- Add Task Modal -->
    <x-modal name="addTask" title="Create New Task">
        <form method="POST" action="{{ route('projects.tasks.store', $project) }}" enctype="multipart/form-data">
            @csrf
            <div>
                <x-input-label for="title" value="Task Title" />
                <x-text-input id="title" name="title" class="block mt-1 w-full" type="text" required autofocus />
            </div>
            <div class="mt-4">
                <x-input-label for="description" value="Description" />
                <textarea id="description" name="description" rows="4" class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"></textarea>
            </div>
            <div class="mt-4">
                <x-input-label for="due_date" value="Due Date" />
                <x-text-input id="due_date" name="due_date" class="block mt-1 w-full" type="date" />
            </div>
            <div class="mt-4">
                <x-input-label for="files" value="Attachments" />
                <x-text-input id="files" name="files[]" class="block mt-1 w-full" type="file" multiple />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button @click="closeModal()">Cancel</x-secondary-button>
                <x-primary-button class="ml-3">Create Task</x-primary-button>
            </div>
        </form>
    </x-modal>
    <!-- Edit Project Modal -->
    <x-modal name="editProject" title="Edit Project">
        <form method="POST" action="{{ route('workspaces.projects.update', [$workspace, $project]) }}">
            @csrf
            @method('PUT')
            <div>
                <x-input-label for="project_name" value="Project Name" />
                <x-text-input id="project_name" name="name" class="block mt-1 w-full" type="text" value="{{ $project->name }}" required autofocus />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button @click="closeModal()">Cancel</x-secondary-button>
                <x-primary-button class="ml-3">Update Project</x-primary-button>
            </div>
        </form>
    </x-modal>
@endpush
