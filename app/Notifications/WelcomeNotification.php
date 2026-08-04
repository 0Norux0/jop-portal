<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $brand = (string) config('jobportal.brand_name');

        return (new MailMessage())
            ->subject("Welcome to {$brand}")
            ->greeting("Welcome, {$notifiable->name}!")
            ->line("Your {$brand} account is now active.")
            ->action('Go to your dashboard', url('/dashboard'));
    }

    /** @return array<string, string> */
    public function toArray(object $notifiable): array
    {
        return ['message' => 'Welcome — your account is now active.'];
    }
}
