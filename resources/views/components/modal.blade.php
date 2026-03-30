@props(['name', 'title' => null, 'show' => false])

<div
    x-data="{ show: @js($show), name: @js($name) }"
    x-show="show"
    x-on:close.stop="show = false"
    x-on:open-modal.window="
        show = ($event.detail && typeof $event.detail === 'object' && $event.detail.name === name)
            || $event.detail === name
    "
    x-on:close-modal.window="
        if (!$event.detail || $event.detail === name || (typeof $event.detail === 'object' && $event.detail && $event.detail.name === name)) { show = false }
    "
    x-on:keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto"
    @if(!empty($title)) aria-labelledby="modal-title" @endif
    role="dialog"
    aria-modal="true"
    x-cloak
>
    <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
             @click="show = false"
             aria-hidden="true">
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">

            @if(!empty($title))
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                    {{ $title }}
                </h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="mt-6">
            @endif
                {{ $slot }}
            @if(!empty($title))
            </div>
            @endif
        </div>
    </div>
</div>
