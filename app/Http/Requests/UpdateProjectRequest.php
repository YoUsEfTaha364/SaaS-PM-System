<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $workspace = $this->route('workspace');
        $project = $this->route('project');

        return [
            "name" => [
                "required", 
                "string", 
                "max:255", 
                \Illuminate\Validation\Rule::unique("projects")->where(function ($q) use ($workspace) {
                    return $q->where("workspace_id", $workspace->id);
                })->ignore($project->id)
            ]
        ];
    }
}
