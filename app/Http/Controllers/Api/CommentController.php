<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Task;
use App\Services\api\ApiResponseService;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    private $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(CommentRequest $request, Task $task)
    {

      Gate::authorize("addComment", $task);


        $validated = $request->validated();

        $comment = $this->commentService->storeComment($validated, $task);

                return ApiResponseService::response(200, "comment created successfully",new CommentResource($comment));

    }

    public function storeReplyComment(Request $request, Task $task, Comment $comment)
    {
   
        Gate::authorize("addComment", $task);

        $validated = $request->validate([
            "content" => ["required", "string"]
        ]);

        $reply = $this->commentService->storeReply($validated, $task, $comment);

        return ApiResponseService::response(200, "comment created successfully",new CommentResource($reply));
    }
}
