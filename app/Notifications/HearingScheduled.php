<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Hearing;

class HearingScheduled extends Notification
{
    use Queueable;

    protected Hearing $hearing;

    public function __construct(Hearing $hearing)
    {
        $this->hearing = $hearing;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $scheduled = $this->hearing->scheduled_at ? $this->hearing->scheduled_at->format('M d, Y H:i') : 'TBD';
        $reference = optional($this->hearing->complaint)->reference ?? 'N/A';

        return (new MailMessage)
            ->subject('Hearing scheduled for your complaint')
            ->greeting('Hello ' . (optional($this->hearing->complaint)->first_name ?? ''))
            ->line("A hearing has been scheduled for your complaint (reference: {$reference}).")
            ->line('Scheduled at: ' . $scheduled)
            ->line('You can track your complaint using your reference code.')
            ->action('Track complaint', url('/Track'))
            ->line('If you have questions, please contact the barangay office.');
    }
}
