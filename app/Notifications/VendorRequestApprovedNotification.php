<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorRequestApprovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $subject, $mailmsg, $link, $linktext;
    public function __construct($subject, $mailmsg, $link, $linktext)
    {
        $this->subject = $subject;
        $this->mailmsg = $mailmsg;
        $this->link = $link;
        $this->linktext = $linktext;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject($this->subject)
            ->markdown('mail.vendor_request_success', [
                'mailmsg' => $this->mailmsg,
                'link' =>  $this->link,
                'linktext' => $this->linktext
            ]);
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
