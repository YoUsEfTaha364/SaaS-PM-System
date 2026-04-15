<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Attachment $attachment)
    {
        if (!Storage::exists($attachment->path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        return Storage::download($attachment->path, $attachment->name);
    }
}
