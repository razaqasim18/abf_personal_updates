<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;

class VendorOrderNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $msg;
    public $type;
    public $link;
    public $detail;
    public $id;

    public function __construct($msg, $type, $link, $detail, public User $vendor)
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
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->msg,
            'notify_type' => $this->type, // 1 request, 2 user,3 ticket,4 order
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
            'message' => $this->msg,
            'notify_type' => $this->type, // 1 request, 2 user,3 ticket,4 order
            'link' => $this->link,
            'detail' => $this->detail,
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel("vendorchannel.{$this->vendor->id}");
    }

    public function broadcastAs(): string
    {
        return 'vendor.notification';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->msg,
            'notify_type' => $this->type, // 1 request, 2 user,3 ticket,4 order
            'link' => $this->link,
            'detail' => $this->detail,
        ];
    }
}
