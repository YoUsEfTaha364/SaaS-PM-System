@extends('layouts.app')

@section('title', $workspace->name)

@section('main-content')
    <div class="p-4 sm:p-8 space-y-8">
        <!-- Workspace Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $workspace->name }}</h1>
                <p class="text-gray-500 mt-2">
                    Owned by: <span class="font-medium">{{ $workspace->owner->name }}</span>
                </p>
            </div>
            @can('manageWorkspace', $workspace)
                <div class="flex space-x-2 mt-4 sm:mt-0">
                    <button @click="openModal('editWorkspace')"
                        class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-700 px-4 py-2 text-sm font-semibold rounded-lg shadow-sm hover:bg-indigo-100 transition-colors border border-indigo-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        <span>Edit</span>
                    </button>
                    <button @click="openModal('addProject')"
                        class="inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>New Project</span>
                    </button>
                    <button @click="openModal('addMember')"
                        class="inline-flex items-center gap-2 bg-white text-gray-700 px-4 py-2 text-sm font-semibold rounded-lg border border-gray-300 shadow-sm hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <span>Add Member</span>
                    </button>
                </div>
            @endcan
        </div>

        <!-- Flash Messages -->
        @if (session('update-member') || session('delete-member') ||session('success_invitation') || session('add-project') || session('update-workspace') || session('update-project') || $errors->any())
            <div class="space-y-2">
                @if (session('success_invitation'))
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
                        {{ session('success_invitation') }}</div>
                @endif
                @if (session('add-project'))
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
                        {{ session('add-project') }}</div>
                @endif
                @if (session('update-project'))
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
                        {{ session('update-project') }}</div>
                @endif
                @if (session('update-workspace'))
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
                        {{ session('update-workspace') }}</div>
                @endif
                @if (session('delete-member'))
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
                        {{ session('delete-member') }}</div>
                @endif
                @if (session('update-member'))
                    <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
                        {{ session('update-member') }}</div>
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

        <div x-data="{ tab: 'projects' }">
            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button @click="tab = 'projects'"
                        :class="{ 'border-indigo-500 text-indigo-600': tab === 'projects', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'projects' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Projects
                    </button>
                    <button @click="tab = 'members'"
                        :class="{ 'border-indigo-500 text-indigo-600': tab === 'members', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'members' }"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Members
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-8">
                <!-- Projects Tab -->
                <div x-show="tab === 'projects'" x-cloak>
                    @if ($workspace->projects->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                            @foreach ($workspace->projects as $project)
                                <div
                                    class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300 flex flex-col">
                                    <div class="p-6 flex-grow">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-semibold text-gray-800">
                                                <a href="{{ route('workspaces.projects.show', [$workspace, $project]) }}"
                                                    class="hover:text-indigo-600">{{ $project->name }}</a>
                                            </h3>
                                        @can('manageWorkspace', $workspace)
                                            <button @click.prevent="openModal('editProject-{{ $project->id }}')" class="text-gray-400 hover:text-indigo-600 transition-colors" title="Rename Project">
                                                <svg class="w-4 h-4 outline-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        @endcan
                                    </div>
                                    <p class="text-sm text-gray-500">
                                            {{ $project->tasks_count }} Tasks
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-100">
                                        <a href="{{ route('workspaces.projects.show', [$workspace, $project]) }}"
                                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center justify-center">
                                            View Project
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center bg-white p-12 rounded-2xl shadow-sm border border-gray-100">
                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                                </path>
                            </svg>
                            <h3 class="text-xl font-semibold text-gray-700 mt-5 mb-3">No Projects Yet</h3>
                            <p class="text-gray-500 mb-6 max-w-sm mx-auto">Get started by creating the first project for
                                this workspace.</p>
                            @can('manageWorkspace', $workspace)
                                <button @click="openModal('addProject')"
                                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:bg-indigo-700 transition">
                                    Create First Project
                                </button>
                            @endcan
                        </div>
                    @endif
                </div>

                <!-- Members Tab -->
                <div x-show="tab === 'members'" x-cloak>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <ul class="divide-y divide-gray-100">
                            @foreach ($workspace->users as $user)
                                <li class="p-4 sm:p-6 flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold text-sm">
                                            {{ mb_strtoupper(mb_substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3">
                                        <!-- Role -->
                                        <span
                                            class="text-xs font-medium px-3 py-1 rounded-full capitalize
            {{ $user->pivot->role == 'owner' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $user->pivot->role }}
                                        </span>

                                        <!-- Delete Button -->
                                        @if (auth()->id() == $workspace->owner_id && $user->pivot->role != 'owner')
                                            <form
                                                action="{{ route('workspaces.members.delete', [$workspace, $user]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Remove
                                                </button>
                                            </form>
                                        @endif
                                        @if (auth()->id() == $workspace->owner_id && $user->pivot->role != 'owner')
                                         
                                                <button @click="openModal('editMember-{{ $user->id }}')" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                    Update
                                                </button>
                                           
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    <!-- Edit Workspace Modal -->
    <x-modal name="editWorkspace" title="Edit Workspace">
        <form method="POST" action="{{ route('workspaces.update', $workspace) }}">
            @csrf
            @method('PUT')
            <div>
                <x-input-label for="workspace_name" value="Workspace Name" />
                <x-text-input id="workspace_name" name="name" class="block mt-1 w-full" type="text" value="{{ $workspace->name }}" required autofocus />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button @click="closeModal()">Cancel</x-secondary-button>
                <x-primary-button class="ml-3">Update Workspace</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Add Project Modal -->
    <x-modal name="addProject" title="Create New Project">
        <form method="POST" action="{{ route('workspaces.projects.store', $workspace) }}">
            @csrf
            <div>
                <x-input-label for="name" value="Project Name" />
                <x-text-input id="name" name="name" class="block mt-1 w-full" type="text" required autofocus />
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button @click="closeModal()">Cancel</x-secondary-button>
                <x-primary-button class="ml-3">Create Project</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Project Modals -->
    @foreach ($workspace->projects as $project)
        @can('manageWorkspace', $workspace)
            <x-modal name="editProject-{{ $project->id }}" title="Edit Project">
                <form method="POST" action="{{ route('workspaces.projects.update', [$workspace, $project]) }}">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="project_name_{{ $project->id }}" value="Project Name" />
                        <x-text-input id="project_name_{{ $project->id }}" name="name" class="block mt-1 w-full" type="text" value="{{ $project->name }}" required autofocus />
                    </div>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button @click="closeModal()">Cancel</x-secondary-button>
                        <x-primary-button class="ml-3">Update Project</x-primary-button>
                    </div>
                </form>
            </x-modal>
        @endcan
    @endforeach

    <!-- Add Member Modal -->
    <x-modal name="addMember" title="Add New Member">
        <form method="POST" action="{{ route('workspaces.members.store', $workspace) }}">
            @csrf
            <div>
                <x-input-label for="email" value="User Email" />
                <x-text-input id="email" name="email" class="block mt-1 w-full" type="email" required />
            </div>
            <div class="mt-4">
                <x-input-label for="role" value="Role" />
                <select id="role" name="role"
                    class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="member">Member</option>
                    <option value="manager">Manager</option>
                </select>
            </div>
            <div class="mt-6 flex justify-end">
                <x-secondary-button @click="closeModal()">Cancel</x-secondary-button>
                <x-primary-button class="ml-3">Add Member</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Member Modals -->
    @foreach ($workspace->users as $user)
        @if ($user->pivot->role != 'owner')
            <x-modal name="editMember-{{ $user->id }}" title="Update Role: {{ $user->name }}">
                <form method="POST" action="{{ route('workspaces.members.update', [$workspace, $user]) }}">
                    @csrf
                    @method('PUT')
                    <div class="mt-4">
                        <x-input-label for="role_{{ $user->id }}" value="Role" />
                        <select id="role_{{ $user->id }}" name="role"
                            class="block w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="member" {{ $user->pivot->role == 'member' ? 'selected' : '' }}>Member</option>
                            <option value="manager" {{ $user->pivot->role == 'manager' ? 'selected' : '' }}>Manager</option>
                        </select>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button @click="closeModal()">Cancel</x-secondary-button>
                        <x-primary-button class="ml-3">Update Role</x-primary-button>
                    </div>
                </form>
            </x-modal>
        @endif
    @endforeach
@endpush
