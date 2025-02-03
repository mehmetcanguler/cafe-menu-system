<?php

namespace App\Notifications;

use App\Models\Verification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerifyNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Verification $verification
    )
    {
        //
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
        ->greeting('Merhaba '.$notifiable->name.',')
        ->subject('E-Posta Doğrulama maili')
        ->line('E posta adresinizi onaylamak için bu e-postayı aldınız.')
        ->action('E-Postanızı Doğrulamak için linke tıklayın', url('/email/verify/'.$this->verification->hash.'/'.$this->verification->code))
        ->line('İyi günler');
    }

}
