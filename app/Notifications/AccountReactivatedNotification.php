<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountReactivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Your account has been reactivated')
            ->line('Good news — your account is active again.')
            ->action('Sign in', url('/login'));
    }

    /** @return array<string, string> */
    public function toArray(object $notifiable): array
    {
        return ['message' => 'Your account has been reactivated.'];
    }
}
