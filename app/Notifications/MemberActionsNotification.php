<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberActionsNotification extends Notification
{
    use Queueable;

  
    public array $data;
    public function __construct(array $data)
    {
        $this->data=$data;
    }


    public function via(object $notifiable): array
    {
        return ['database'];
    }




    public function toArray(object $notifiable): array
    {
        return [
            "workspace"=>$this->data["workspace"],
            "message"=>$this->data["message"],
            "type"=>$this->data["type"],
        ];
    }
}
