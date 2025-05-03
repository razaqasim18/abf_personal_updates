<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class AdminNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $msg;
    public $type;
    public $link;
    public $detail;
    public $isvendor;
    public $adminid;
    public function __construct($msg, $type, $link, $detail, $isvendor = 0, $adminid = 1)
    {
        $this->msg = $msg;
        $this->type = $type; // 1 request, 2 user, 3 ticket, 4 order
        $this->link = $link;
        $this->detail = $detail;
        $this->isvendor = $isvendor;
        $this->adminid = $adminid;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->msg,
            'type' => $this->type,
            'link' => $this->link,
            'detail' => $this->detail,
            'isvendor' => $this->isvendor,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->msg,
            'type' => $this->type,
            'link' => $this->link,
            'detail' => $this->detail,
            'isvendor' => $this->isvendor,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel("adminchannel.{$this->adminid}");
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->msg,
            'type' => $this->type,
            'link' => $this->link,
            'detail' => $this->detail,
            'isvendor' => $this->isvendor,
        ];
    }

    public function broadcastAs()
    {
        return 'admin-notification'; // Explicit event name
    }
}
