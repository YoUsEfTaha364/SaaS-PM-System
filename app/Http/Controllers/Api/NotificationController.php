<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\api\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::guard("api")->user();

        $notifications = $user->notifications;
        $unreadnotifications = $user->unreadNotifications;

        $data = [
            'notifications' => $notifications,
            'unreadnotifications' => $unreadnotifications
        ];

        return ApiResponseService::response(200, 'get notifications', $data);
    }


    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return ApiResponseService::response(200, 'marked notifications as read', []);
    }
}
