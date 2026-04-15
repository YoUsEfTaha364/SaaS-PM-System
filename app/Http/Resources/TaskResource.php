<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "task"=>[
            "id"=>$this->id,
            "title"=>$this->title,
            "description"=>$this->description,
            "due_data"=>$this->due_data,
            "status"=>$this->status,
            ],

            "project"=>[
               "id"=> $this->project->id,
               "name"=> $this->project->name,
                "workspace_id"=> $this->project->workspace_id,
            ],
            "workspace"=>[
               "id"=> $this->project->workspace->id,
               "name"=> $this->project->workspace->name,
              
            ],
        ];
    }
}
