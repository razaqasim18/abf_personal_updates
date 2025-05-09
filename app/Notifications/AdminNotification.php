<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;

class AdminNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    public $msg;
    public $type;
    public $link;
    public $detail;
    public $isvendor;
    public $adminId;

    public function __construct($msg, $type, $link, $detail, $isvendor = 0)
    {
        $this->msg = $msg;
        $this->type = $type;
        $this->link = $link;
        $this->detail = $detail;
        $this->isvendor = $isvendor;
    }

    public function via(object $notifiable): array
    {
        // Assign admin ID from notifiable
        $this->adminId = $notifiable->id;

        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->msg,
            'notify_type' => $this->type,
            'link' => $this->link,
            'detail' => $this->detail,
            'isvendor' => $this->isvendor,
        ];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->msg,
            'notify_type' => $this->type,
            'link' => $this->link,
            'detail' => $this->detail,
            'isvendor' => $this->isvendor,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel("adminchannel.{$this->adminId}");
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->msg,
            'notify_type' => $this->type,
            'link' => $this->link,
            'detail' => $this->detail,
            'isvendor' => $this->isvendor,
        ];
    }
}
