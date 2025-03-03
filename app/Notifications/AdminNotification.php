<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $msg;
    public $type;
    public $link;
    public $detail;
    public $isvendor;
    public function __construct($msg, $type, $link, $detail, $isvendor = 0)
    {
        $this->msg = $msg;
        $this->type = $type; // 1 request, 2 user,3 ticket,4 order
        $this->link = $link;
        $this->detail = $detail;
        $this->isvendor = $isvendor;
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
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->msg,
            'type' => $this->type, // 1 request, 2 user,3 ticket,4 order
            'link' => $this->link,
            'detail' => $this->detail,
            'isvendor' => $this->isvendor,
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
