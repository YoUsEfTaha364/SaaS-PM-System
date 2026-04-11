@extends('layouts.app')

@section('title', 'My Workspaces')

@section('main-content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Workspaces</h1>
            <p class="text-gray-500 mt-2">An overview of all your workspaces.</p>
        </div>
        <a href="{{ route('workspaces.create') }}" class="mt-4 sm:mt-0 inline-flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 text-sm font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <span>New Workspace</span>
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($workspaces->count() > 0)
        <!-- Workspace Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($workspaces as $workspace)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-lg transition-shadow duration-300 flex flex-col">
                    <div class="p-6 flex-grow">
                        <div class="flex justify-between items-start mb-4">
                            <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                                <a href="{{ route('workspaces.show', $workspace) }}" class="hover:text-indigo-600">{{ $workspace->name }}</a>
                                @can('manageWorkspace', $workspace)
                                    <button @click.prevent="openModal('editWorkspace-{{ $workspace->id }}')" class="text-gray-400 hover:text-indigo-600 transition-colors" title="Rename Workspace">
                                        <svg class="w-4 h-4 outline-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                @endcan
                            </h2>
                            <span class="text-xs font-medium px-3 py-1 rounded-full capitalize
                                {{ $workspace->pivot->role == 'owner' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $workspace->pivot->role }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mb-6">
                            Owned by: <span class="font-medium">{{ $workspace->owner->name }}</span>
                        </p>
                        
                        <div class="flex items-center text-sm text-gray-500 space-x-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 11l-5-5-5 5M12 16V6"></path></svg>
                                <span>{{ $workspace->projects_count }} Projects</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>{{ $workspace->users_count }} Members</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 rounded-b-2xl border-t border-gray-100">
                        <a href="{{ route('workspaces.show', $workspace) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center justify-center">
                            View Details
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center bg-white p-12 rounded-2xl shadow-sm border border-gray-100">
            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            <h2 class="text-xl font-semibold text-gray-700 mt-5 mb-3">
                You haven't created any workspaces yet.
            </h2>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                Workspaces help you organize your projects and collaborate with your team.
            </p>
            <a href="{{ route('workspaces.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold shadow-md hover:bg-indigo-700 transition">
                Create Your First Workspace
            </a>
        </div>
    @endif
</div>
@endsection

@push('modals')
    @foreach($workspaces as $workspace)
        @can('manageWorkspace', $workspace)
            <x-modal name="editWorkspace-{{ $workspace->id }}" title="Edit Workspace">
                <form method="POST" action="{{ route('workspaces.update', $workspace) }}">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="workspace_name_{{ $workspace->id }}" value="Workspace Name" />
                        <x-text-input id="workspace_name_{{ $workspace->id }}" name="name" class="block mt-1 w-full" type="text" value="{{ $workspace->name }}" required autofocus />
                    </div>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button @click="closeModal()">Cancel</x-secondary-button>
                        <x-primary-button class="ml-3">Update Workspace</x-primary-button>
                    </div>
                </form>
            </x-modal>
        @endcan
    @endforeach
@endpush
