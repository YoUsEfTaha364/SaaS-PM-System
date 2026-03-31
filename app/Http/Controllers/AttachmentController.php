<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{


public function download(Attachment $attachment)
{
    if (!Storage::disk('public')->exists("attachments/" . $attachment->file_path)) {
        abort(404, 'File not found.');
    }

    return Storage::disk('public')->download("attachments/" . $attachment->file_path, $attachment->file_name);
}
}
