<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TaskRequest extends FormRequest
{


    public function rules(): array
    {
        return [
            "title" => ["required", "string", "max:255"],
            "description" => ["nullable", "string", "max:255"],
            "due_date" => ["date", "nullable"],
            'files' => ['nullable', 'array'],
            'files.*' => [
                'file',
                'mimes:pdf,jpg,jpeg,png,zip,doc,docx,xlsx',
                'max:10240',
            ],
        ];
    }
}