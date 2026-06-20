<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationVerificationCode extends Notification
{
    use Queueable;

    public function __construct(
        public string $code,
        public int $expiresInMinutes = 15,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify your email address')
            ->line('Use this verification code to finish creating your account.')
            ->line("Verification code: {$this->code}")
            ->line("This code expires in {$this->expiresInMinutes} minutes.");
    }
}
