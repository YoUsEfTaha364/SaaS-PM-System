<div class="relative p-5 transition duration-200 border rounded-xl shadow-sm hover:shadow-md
            {{ $notification->read_at ? 'bg-white border-gray-100' : 'bg-indigo-50 border-indigo-200' }}">

    <div class="flex items-start justify-between gap-4">

        <!-- Left Side -->
        <div class="flex items-start gap-3">

            <!-- Icon -->
            <div class="flex items-center justify-center w-10 h-10 text-xl text-indigo-600 bg-indigo-100 rounded-full shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>

            <!-- Message -->
            <div>
                <p class="text-sm font-medium leading-relaxed text-gray-800">
                    {!! $notification->data["message"] !!}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Click below to join the workspace.
                </p>

                <!-- Time -->
                <p class="mt-2 text-xs text-gray-400">
                    {{ $notification->created_at->diffForHumans() }}
                </p>
            </div>
        </div>

        <!-- Right Side (Button) -->
        <form action="{{ route('workspace_registered_invitation_accept') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $notification->data['token'] }}">

            <button 
                type="submit"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white transition bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap">
                Accept
            </button>
        </form>

    </div>
    @if(!$notification->read_at)
        <span class="absolute w-2.5 h-2.5 bg-indigo-600 rounded-full top-5 right-5"></span>
    @endif
</div>