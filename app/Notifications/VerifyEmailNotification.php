<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Support\EmailContent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $content = EmailContent::load()['verify_email'];
        $branding = EmailContent::branding();

        return (new MailMessage())
            ->subject($content['subject'])
            ->view('emails.auth.verify-email', [
                ...$branding,
                'content' => $content,
                'user' => $notifiable,
                'verificationUrl' => $this->verificationUrl($notifiable),
            ]);
    }

    private function verificationUrl(object $notifiable): string
    {
        $rootUrl = rtrim((string) config('app.url'), '/');

        if ($rootUrl !== '') {
            URL::forceRootUrl($rootUrl);
        }

        try {
            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes((int) config('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1((string) $notifiable->getEmailForVerification()),
                ],
            );
        } finally {
            URL::forceRootUrl(null);
        }
    }
}
