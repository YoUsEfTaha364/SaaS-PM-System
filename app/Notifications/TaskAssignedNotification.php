<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public array  $data;
    public function __construct(array $data)
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
            'task_id' => $this->data["task_id"],
            'message' =>  $this->data["message"],
            'url' =>  $this->data["url"],
            'type' =>  $this->data["type"],

        ];
    }
}
