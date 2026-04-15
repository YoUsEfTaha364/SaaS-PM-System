<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\api\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskFilterController extends Controller
{
    public function filter(Request $request)
    {
      
        $user = Auth::user();

        $query = Task::whereHas('project.workspace', function ($q) use ($user) {
            $q->whereHas('users', function ($q2) use ($user) {
                $q2->where('users.id', $user->id);
            });
        });

        if ($request->has('status') && in_array($request->status, ['todo', 'in_progress', 'done'])) {
            $query->where('status', $request->status);
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->orderBy('updated_at', 'desc')->get();

        return ApiResponseService::response(200, "get filtered tasks",TaskResource::collection($tasks));
    }
}
