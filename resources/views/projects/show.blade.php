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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="kanbanBoard()">
        @foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'] as $status => $statusLabel)
            <div class="bg-gray-50 rounded-2xl p-4 flex flex-col h-full">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">{{ $statusLabel }}</h3>
                    <span class="bg-gray-200 text-gray-600 py-0.5 px-2 rounded-full text-xs font-bold">{{ $project->tasks->where('status', $status)->count() }}</span>
                </div>
                
                <div class="space-y-4 flex-1 kanban-column min-h-[150px]" data-status="{{ $status }}">
                    @forelse ($project->tasks->where('status', $status) as $task)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 cursor-grab active:cursor-grabbing hover:border-indigo-300 transition-colors kanban-item" data-id="{{ $task->id }}">
                            <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="font-semibold text-gray-800 hover:text-indigo-600 block mb-2">{{ $task->title }}</a>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $task->description }}</p>
                            
                            <div class="flex justify-between items-center mt-auto">
                                <div class="flex items-center -space-x-2">
                                    @foreach ($task->users->take(3) as $user)
                                        <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 text-xs flex items-center justify-center border-2 border-white font-bold" title="{{ $user->name }}">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                    @endforeach
                                    @if($task->users->count() > 3)
                                        <div class="w-7 h-7 rounded-full bg-gray-200 text-gray-600 text-xs flex items-center justify-center border-2 border-white font-bold">+{{ $task->users->count() - 3 }}</div>
                                    @endif
                                </div>
                                
                                @if($task->due_date)
                                    <div class="flex items-center text-xs font-medium text-gray-500 bg-gray-50 px-2 py-1 rounded-md">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="hidden empty-placeholder"></div>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    function kanbanBoard() {
        return {
            init() {
                const columns = document.querySelectorAll('.kanban-column');
                columns.forEach(col => {
                    new Sortable(col, {
                        group: 'kanban', // set both lists to same group
                        animation: 150,
                        ghostClass: 'bg-indigo-50',
                        onEnd: (evt) => {
                            const itemEl = evt.item;  // dragged HTMLElement
                            const toCol = evt.to;    // target list
                            
                            const taskId = itemEl.getAttribute('data-id');
                            const newStatus = toCol.getAttribute('data-status');
                            const userId = '{{ Auth::id() }}';
                            
                            // Send AJAX request to update status
                            fetch(`/tasks/${taskId}/users/${userId}/status`, {
                                method: 'PATCH',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ status: newStatus })
                            })
                            .then(response => response.json())
                            .then(data => {
                                console.log('Status updated:', data);
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                // Revert position if needed
                            });
                        },
                    });
                });
            }
        }
    }
</script>
@endpush
