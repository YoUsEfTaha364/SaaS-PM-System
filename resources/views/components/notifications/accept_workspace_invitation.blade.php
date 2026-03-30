<div class="relative p-5 transition duration-200 border rounded-xl shadow-sm hover:shadow-md
            {{ $notification->read_at ? 'bg-white border-gray-100' : 'bg-emerald-50 border-emerald-200' }}">
    <div class="flex items-start gap-3">
        <!-- Icon -->
        <div class="flex items-center justify-center w-10 h-10 text-xl text-emerald-600 bg-emerald-100 rounded-full shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <!-- Message -->
        <div>
            <p class="text-sm font-medium leading-relaxed text-gray-800">
                {!! $notification->data["message"] !!}
            </p>
            <p class="mt-2 text-xs text-gray-400">
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>
    </div>
    @if(!$notification->read_at)
        <span class="absolute w-2.5 h-2.5 bg-emerald-600 rounded-full top-5 right-5"></span>
    @endif
</div>