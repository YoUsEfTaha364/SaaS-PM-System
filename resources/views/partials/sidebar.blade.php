<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r shadow-sm transition-transform duration-300 transform lg:translate-x-0 lg:static"
       :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-6 border-b">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">
            {{ config('app.name', 'SaaS Project') }}
        </a>
        <!-- Close button for mobile -->
        <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="p-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 font-semibold' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('tasks.index') }}" class="block px-4 py-2.5 text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('tasks.*') ? 'bg-gray-100 font-semibold' : '' }}">
            My Tasks
        </a>
        <a href="{{ route('workspaces.index') }}" class="block px-4 py-2.5 text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('workspaces.*') && !request()->routeIs('workspaces.projects.*') ? 'bg-gray-100 font-semibold' : '' }}">
            Workspaces
        </a>
        <a href="{{ route('workspaces.projects.index') }}" class="block px-4 py-2.5 text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('workspaces.projects.*') ? 'bg-gray-100 font-semibold' : '' }}">
            Projects
        </a>
        <a href="{{ route('notifications.index') }}" class="flex items-center justify-between px-4 py-2.5 text-gray-700 rounded-md hover:bg-gray-100 {{ request()->routeIs('notifications.*') ? 'bg-gray-100 font-semibold' : '' }}">
            <span>Notifications</span>
            @php $unreadCount = auth()->user()->unreadNotifications->count() ?? 0; @endphp
            @if($unreadCount > 0)
                <span class="bg-indigo-100 text-indigo-600 py-0.5 px-2 rounded-full text-xs font-bold">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>
    </nav>
</aside>

<!-- Backdrop for mobile -->
<div x-cloak x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"></div>