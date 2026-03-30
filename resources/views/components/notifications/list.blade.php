@if(($notifications ?? collect())->count() > 0)
    <div id="notifications-container" class="space-y-4">
        @foreach ($notifications as $notification)
            @if(data_get($notification->data, 'type') === 'workspace_invitation')
                @include("components.notifications.workspace_invitation", ["notification" => $notification])
            @endif

            @if(data_get($notification->data, 'type') === 'accept_invitation')
                @include("components.notifications.accept_workspace_invitation", ["notification" => $notification])
            @endif

            @if(data_get($notification->data, 'type') === 'comment_notification')
                @include("components.notifications.comment_notification", ["notification" => $notification])
            @endif

            @if(data_get($notification->data, 'type') === 'task_assignment')
                @include("components.notifications.task_assignment", ["notification" => $notification])
            @endif
            @if(data_get($notification->data, 'type') === 'membership_actions')
                @include("components.notifications.membership_actions", ["notification" => $notification])
            @endif
        @endforeach
    </div>
@else
    <div class="flex flex-col items-center justify-center p-8 mt-6 border border-gray-200 border-dashed bg-gray-50 rounded-xl">
        <svg class="w-12 h-12 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        <h3 class="text-sm font-medium text-gray-900">No notifications yet</h3>
        <p class="mt-1 text-sm text-gray-500">When you receive new notifications, they will appear here.</p>
    </div>
@endif