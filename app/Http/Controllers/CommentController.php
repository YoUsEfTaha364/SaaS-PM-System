<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{


    protected  $comment_service;

    public function __construct(CommentService $service)
    {
        $this->comment_service=$service;    
    }
    public function store(Request $request, Task $task)
    {

        Gate::authorize("addComment", $task);

        $validated = $request->validate([
            "content" => "required|string:max:255",
            'files' => ['nullable', 'array'],
            'files.*' => [
                'file',
                'mimes:pdf,jpg,jpeg,png,zip,doc,docx,xlsx',
                'max:10240',
            ],

        ]);

        $this->comment_service->storeComment($validated, $task);

        return back()->with("create-comment", "comment created successfully");
    }
    public function storeReplyComment(Request $request, Task $task, Comment $comment)
    {
       

        Gate::authorize("addComment", $task);


        $validated = $request->validate([
            "content" => "required|string:max:255",
            'files' => ['nullable', 'array'],
            'files.*' => [
                'file',
                'mimes:pdf,jpg,jpeg,png,zip,doc,docx,xlsx',
                'max:10240',
            ],

        ]);
  
        $this->comment_service->storeReply($validated, $task, $comment);


        return back()->with("create-comment", "reply created successfully");
    }
}
