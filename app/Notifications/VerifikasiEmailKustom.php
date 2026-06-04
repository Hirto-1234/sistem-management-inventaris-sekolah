<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class VerifikasiEmailKustom extends Notification
{

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify', 
            now()->addMinutes(60),
            ['id' => $notifiable->getKey(), 'hash' => sha1($notifiable->getEmailForVerification())]
        );
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
        $url = $this->verificationUrl($notifiable);
        
        return (new MailMessage)
            ->subject('Selamat Datang di Inventaris SMKN 1 Kota Bekasi!')
            ->greeting('Halo ' . $notifiable->nama_pengguna . '!')
            ->line('Terima kasih telah mendaftar. Silakan klik tombol di bawah untuk memverifikasi email Anda.')
            ->action('Verifikasi Email', $url)
            ->line('Jika Anda tidak membuat akun, abaikan email ini.')
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
