@extends("layouts.app")

@section("title","Notifications")
@section("notification-active","bg-indigo-50 text-indigo-600 font-medium")

@section("main-content")
<div class="max-w-2xl mx-auto mt-6">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-xl font-semibold text-gray-800">Notifications</h1>

        @if(Auth::user()->unreadNotifications->count() > 0)
        <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
            @csrf
            <button class="text-sm text-indigo-600 hover:underline">
                Mark all as read
            </button>
        </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div id="notifications-container">
        @include('components.notifications.list', ['notifications' => $notifications])
    </div>

</div>
@endsection