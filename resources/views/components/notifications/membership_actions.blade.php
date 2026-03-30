<div class="relative p-5 transition duration-200 border rounded-xl shadow-sm hover:shadow-md
            {{ $notification->read_at ? 'bg-white border-gray-100' : 'bg-blue-50 border-blue-200' }}">

    <div class="flex items-start justify-between gap-4">

        <!-- Left Side -->
        <div class="flex items-start gap-3">

            <!-- Icon -->
            <div class="flex items-center justify-center w-10 h-10 text-xl text-blue-600 bg-blue-100 rounded-full shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>

            <!-- Message -->
            <div>
                <p class="text-sm font-medium leading-relaxed text-gray-800">
                    {!! $notification->data["message"] !!}
                </p>


                <!-- Time -->
                <p class="mt-2 text-xs text-gray-400">
                    {{ $notification->created_at->diffForHumans() }}
                </p>
            </div>
        </div>

    </div>
    @if(!$notification->read_at)
        <span class="absolute w-2.5 h-2.5 bg-blue-600 rounded-full top-5 right-5"></span>
    @endif
</div>
