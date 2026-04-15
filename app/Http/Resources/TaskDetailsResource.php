<?php

namespace App\Http\Resources;

use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [
    "id" => $this->id,
    "title" => $this->title,
    "description" => $this->description,
    "due_date" => $this->due_date,
    "status" => $this->status,

    "project" => [
        "id" => $this->project->id,
        "name" => $this->project->name,
    ],

    "workspace" => [
        "name" => $this->project->workspace->name,
    ],

    "comments" => CommentResource::collection($this->comments),
    "users" => UserResource::collection($this->users),
];
    }
}
