<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Task;
use App\Services\CommentService;

class CommentController extends Controller
{

    protected  $comment_service;

    public function __construct(CommentService $service)
    {
        $this->comment_service=$service;    
    }
    public function store(CommentRequest $request, Task $task)
    {
        $validated = $request->validated();

        $this->comment_service->storeComment($validated, $task);

        return back()->with("create-comment", "comment created successfully");
    }
    public function storeReplyComment(CommentRequest $request, Task $task, Comment $comment)
    {
        
        $validated = $request->validated();
  
        $this->comment_service->storeReply($validated, $task, $comment);


        return back()->with("create-comment", "reply created successfully");
    }
}
