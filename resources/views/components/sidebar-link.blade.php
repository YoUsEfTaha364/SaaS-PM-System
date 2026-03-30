@props(['href', 'active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-4 py-2 text-sm font-medium text-indigo-700 bg-indigo-50 rounded-lg'
            : 'flex items-center gap-3 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-50 hover:text-gray-900 transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} href="{{ $href }}">
    {{ $slot }}
</a>
