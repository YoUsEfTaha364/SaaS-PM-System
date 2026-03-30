@extends('layouts.app')

@section('title', 'Create New Workspace')

@section('main-content')
<div class="max-w-4xl mx-auto p-4 sm:p-8">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Create a New Workspace</h1>
            <p class="text-gray-500 mt-2">Workspaces help you organize your projects and team.</p>
        </div>
        <a href="{{ route('workspaces.index') }}" class="mt-4 sm:mt-0 inline-flex items-center gap-2 text-sm text-gray-600 hover:text-gray-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Workspaces
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
        <form method="POST" action="{{ route('workspaces.store') }}">
            @csrf

            <!-- Workspace Name -->
            <div>
                <x-input-label for="name" :value="__('Workspace Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus placeholder="e.g. My Awesome Company" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-primary-button>
                    {{ __('Create Workspace') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
