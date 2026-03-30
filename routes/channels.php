<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('comment-notf.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
