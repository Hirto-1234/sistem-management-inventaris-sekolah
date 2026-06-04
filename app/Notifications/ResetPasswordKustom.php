<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordKustom extends Notification
{
    public $token;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        //
        $this->token = $token;
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
        $url = url('/reset/password/' . $this->token . '?email=' . $notifiable->email);

        return (new MailMessage)
            ->subject('Reset Password Inventaris SMKN 1 Kota Bekasi')
            ->greeting('Halo '.$notifiable->nama_pengguna.'!')
            ->line('Kamu menerima email ini karena kami menerima permintaan reset password akun kamu.')
            ->action('Reset Password', $url)
            ->line('Jika kamu tidak meminta reset password, abaikan email ini.')
            ->salutation('Salam, Tim Inventaris SMKN 1 Kota Bekasi');
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
