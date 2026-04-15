<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {   
        return [
            "comment"=>[
            "id"=>$this->id,
            "content"=>$this->content,
            "parent_id"=>$this->parent_id,
            ],
            
            "user"=>[
            "id"=>$this->user->id,
            "name"=>$this->user->name,
            ],
        ];
    }
}
