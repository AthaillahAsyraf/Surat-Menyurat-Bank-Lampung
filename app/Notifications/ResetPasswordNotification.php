<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
        
        // Set queue untuk pengiriman email asynchronous
        $this->onQueue('emails');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        // Generate URL reset password
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Get expiry time dalam menit
        $expiry = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        // Jika ada custom email template, gunakan itu
        if (view()->exists('emails.reset-password')) {
            return (new MailMessage)
                ->subject('Reset Password - Bank Lampung')
                ->view('emails.reset-password', [
                    'actionUrl' => $resetUrl,
                    'user' => $notifiable,
                    'expiry' => $expiry
                ]);
        }

        // Fallback ke default Laravel template
        return (new MailMessage)
            ->subject('Reset Password - Bank Lampung')
            ->greeting('Halo!')
            ->line('Anda menerima email ini karena kami menerima permintaan reset password untuk akun Anda.')
            ->action('Reset Password', $resetUrl)
            ->line("Link reset password ini akan kedaluwarsa dalam {$expiry} menit.")
            ->line('Jika Anda tidak meminta reset password, tidak ada tindakan lebih lanjut yang diperlukan.')
            ->salutation('Terima kasih, Tim IT Bank Lampung');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            'reset_token' => $this->token,
            'email' => $notifiable->email,
            'requested_at' => now(),
        ];
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addMinutes(5);
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return array
     */
    public function backoff()
    {
        return [1, 5, 10];
    }
}