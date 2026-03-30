@props(['title', 'value', 'icon', 'color' => 'gray'])

@php
$iconMap = [
    'workspaces' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>',
    'projects' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 11l-5-5-5 5M12 16V6"></path></svg>',
    'tasks' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>',
    'completed-tasks' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
];

$colorClasses = [
    'blue' => 'bg-blue-100 text-blue-600',
    'indigo' => 'bg-indigo-100 text-indigo-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'green' => 'bg-green-100 text-green-600',
    'gray' => 'bg-gray-100 text-gray-600',
];
@endphp

<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-6">
    <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $colorClasses[$color] ?? $colorClasses['gray'] }}">
        {!! $iconMap[$icon] ?? $iconMap['workspaces'] !!}
    </div>
    <div>
        <p class="text-sm text-gray-500">{{ $title }}</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $value }}</p>
    </div>
</div>
