<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Domain\Portal\Models\JobAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class JobAlertDigestNotification extends Notification
{
    use Queueable;

    /**
     * @param  Collection<int, array{title: string, company: string, city: string, country: string, slug: string}>  $jobs
     */
    public function __construct(
        private readonly JobAlert $alert,
        private readonly Collection $jobs,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Job alert: '.$this->alert->name)
            ->greeting('New matching jobs')
            ->line('We found jobs matching your alert: '.$this->alert->name);

        foreach ($this->jobs->take(5) as $job) {
            $message->line($job['title'].' at '.$job['company'].' - '.$job['city'].', '.$job['country']);
        }

        return $message->action('View jobs', url('/jobs'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'alert_id' => $this->alert->id,
            'alert_name' => $this->alert->name,
            'jobs' => $this->jobs->take(10)->values()->all(),
        ];
    }
}
