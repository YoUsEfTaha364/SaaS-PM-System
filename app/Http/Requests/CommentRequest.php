<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');
        return Gate::allows('addComment', $task);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "content" => ["required", "string", "max:255"],
            'files' => ['nullable', 'array'],
            'files.*' => [
                'file',
                'mimes:pdf,jpg,jpeg,png,zip,doc,docx,xlsx',
                'max:10240',
            ],
        ];
    }
}