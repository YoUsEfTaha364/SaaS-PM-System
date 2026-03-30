<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChnageStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $data;
    public function __construct($data)
    {
        $this->data=$data;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
 
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
     public function toArray(object $notifiable): array
    {
        return [
            "task"=>$this->data["task"],
            "message"=>$this->data["message"],
            "type"=>$this->data["type"],
        ];
    }
}
