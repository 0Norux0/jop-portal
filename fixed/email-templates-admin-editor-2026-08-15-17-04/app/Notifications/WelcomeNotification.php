<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Support\EmailContent;
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
        $content = EmailContent::load()['welcome'];
        $branding = EmailContent::branding();

        return (new MailMessage())
            ->subject($content['subject'])
            ->view('emails.auth.welcome', [
                ...$branding,
                'content' => $content,
                'user' => $notifiable,
                'dashboardUrl' => url('/dashboard'),
            ]);
    }

    /** @return array<string, string> */
    public function toArray(object $notifiable): array
    {
        return ['message' => 'Welcome - your account is now active.'];
    }
}
