<div class="flex gap-3 mt-4">

    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 text-xs flex items-center justify-center">
        {{ strtoupper(substr($comment->user->name,0,1)) }}
    </div>

    <div class="flex-1">

        <!-- Comment Header -->
        <div class="flex justify-between items-center mb-1">
            <div class="text-xs text-gray-500">
                <span class="font-semibold text-gray-700">{{ $comment->user->name }}</span>
                • {{ $comment->created_at->diffForHumans() }}
            </div>

            <!-- Reply Button -->
            <button
                onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('hidden')"
                class="text-xs text-indigo-600 hover:text-indigo-700">
                Reply
            </button>
        </div>

        <!-- Comment Content -->
        <div class="text-sm bg-gray-50 border border-gray-100 p-2 rounded-lg">
            {{ $comment->content }}
        </div>

        <!-- Reply Form -->
        <form id="reply-form-{{ $comment->id }}"
              method="POST"
              action="{{ route('tasks.comments.replies.store', [$comment->task_id, $comment]) }}"
              class="hidden mt-3">

            @csrf

            <textarea name="content"
                rows="2"
                placeholder="Write a reply..."
                class="w-full border-gray-200 rounded-lg px-3 py-2 text-sm"></textarea>

            <div class="mt-2 flex justify-end">
                <button
                    class="bg-indigo-600 text-white px-3 py-1 text-xs rounded-md hover:bg-indigo-700">
                    Reply
                </button>
            </div>

        </form>

        <!-- Replies -->
        @if($comment->replyComments->count())
            <div class="ml-8">
                @foreach ($comment->replyComments as $reply)
                    <x-comment :comment="$reply"/>
                @endforeach
            </div>
        @endif

    </div>

</div>