<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Hearing;

class HearingStatusChanged extends Notification
{
    use Queueable;

    protected Hearing $hearing;
    protected string $oldStatus;
    protected string $newStatus;

    public function __construct(Hearing $hearing, string $oldStatus, string $newStatus)
    {
        $this->hearing = $hearing;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
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
            ->subject('Hearing status updated for your complaint')
            ->greeting('Hello ' . (optional($this->hearing->complaint)->first_name ?? ''))
            ->line("The status for the hearing regarding your complaint (reference: {$reference}) has changed.")
            ->line("Previous status: " . ucfirst(str_replace('_', ' ', $this->oldStatus)))
            ->line("New status: " . ucfirst(str_replace('_', ' ', $this->newStatus)))
            ->line('Scheduled at: ' . $scheduled)
            ->action('Track complaint', url('/Track'))
            ->line('If you have questions, please contact the barangay office.');
    }
}
