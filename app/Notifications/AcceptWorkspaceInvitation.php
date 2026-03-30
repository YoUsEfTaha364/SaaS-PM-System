<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AcceptWorkspaceInvitation extends Notification
{
    use Queueable;

 public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            "name"=>$this->data["name"],
            "email"=>$this->data["email"],
            "type"=>$this->data["type"],
            "message"=>"user ".$this->data["name"]." accepted the  invitation",
        ];
    }
}
