<?php

namespace App\Notifications;

use App\Models\Admin;
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


    public function __construct($msg, $type, $link, $detail, $isvendor = 0, public Admin $admin)
    {
        $this->msg = $msg;
        $this->type = $type;
        $this->link = $link;
        $this->detail = $detail;
        $this->isvendor = $isvendor;
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        return $this->payload();
    }

    public function toArray(object $notifiable): array
    {
        return $this->payload();
    }

    public function broadcastOn()
    {
        return new PrivateChannel("adminchannel.{$this->admin->id}");
    }

    public function broadcastWith()
    {
        return $this->payload();
    }

    public function broadcastAs(): string
    {
        return 'admin.notification';
    }

    protected function payload(): array
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
