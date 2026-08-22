<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Support\EmailContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly string $token)
    {
        //
    }

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $content = EmailContent::load()['reset_password'];
        $branding = EmailContent::branding();

        return (new MailMessage())
            ->subject($content['subject'])
            ->view('emails.auth.reset-password', [
                ...$branding,
                'content' => $content,
                'user' => $notifiable,
                'resetUrl' => url(route('password.reset', [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)),
            ]);
    }
}
