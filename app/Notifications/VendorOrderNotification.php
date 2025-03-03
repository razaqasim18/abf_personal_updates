<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorOrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $msg;
    public $type;
    public $link;
    public $detail;
    public function __construct($msg, $type, $link, $detail)
    {
        $this->msg = $msg;
        $this->type = $type; // 1 request, 2 user,3 ticket,4 order
        $this->link = $link;
        $this->detail = $detail;
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

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->msg,
            'type' => $this->type, // 1 request, 2 user,3 ticket,4 order
            'link' => $this->link,
            'detail' => $this->detail,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
