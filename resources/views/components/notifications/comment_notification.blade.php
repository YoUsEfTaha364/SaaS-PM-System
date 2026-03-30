<div class="relative p-5 transition duration-200 border rounded-xl shadow-sm hover:shadow-md
            {{ $notification->read_at ? 'bg-white border-gray-100' : 'bg-blue-50 border-blue-200' }}">
    <div class="flex items-start gap-3">
        <!-- Icon -->
        <div class="flex items-center justify-center w-10 h-10 text-xl text-blue-600 bg-blue-100 rounded-full shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
        </div>
        <!-- Message -->
        <div>
            <p class="text-sm font-medium leading-relaxed text-gray-800">
                {!! $notification->data["message"] !!}
            </p>
            <p class="mt-1 text-sm italic text-gray-500">
                "comment"
            </p>
            <p class="mt-2 text-xs text-gray-400">
                {{ $notification->created_at->diffForHumans() }}
            </p>
        </div>
    </div>
    @if(!$notification->read_at)
        <span class="absolute w-2.5 h-2.5 bg-blue-600 rounded-full top-5 right-5"></span>
    @endif
</div>