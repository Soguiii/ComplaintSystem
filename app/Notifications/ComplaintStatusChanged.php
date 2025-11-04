<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Complaint;

class ComplaintStatusChanged extends Notification
{
    use Queueable;

    protected Complaint $complaint;
    protected string $oldStatus;
    protected string $newStatus;
    protected $changedAt;

    public function __construct(Complaint $complaint, string $oldStatus, string $newStatus, $changedAt)
    {
        $this->complaint = $complaint;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedAt = $changedAt;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $time = $this->changedAt instanceof \DateTime ? $this->changedAt->format('M d, Y H:i') : (string)$this->changedAt;

        return (new MailMessage)
            ->subject('Your complaint status has changed')
            ->greeting('Hello ' . ($this->complaint->first_name ?? ''))
            ->line('The status of your complaint (reference: ' . ($this->complaint->reference ?? 'N/A') . ') has changed.')
            ->line('Previous status: ' . ucfirst(str_replace('_', ' ', $this->oldStatus)))
            ->line('Current status: ' . ucfirst(str_replace('_', ' ', $this->newStatus)))
            ->line('Changed at: ' . $time)
            ->action('Track your complaint', url('/Track'))
            ->line('If you have any questions, reply to this email.');
    }
}
