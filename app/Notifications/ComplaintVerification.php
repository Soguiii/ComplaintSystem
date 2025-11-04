<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Complaint;

class ComplaintVerification extends Notification
{
    use Queueable;

    protected Complaint $complaint;
    protected string $verificationUrl;

    public function __construct(Complaint $complaint, string $verificationUrl)
    {
        $this->complaint = $complaint;
        $this->verificationUrl = $verificationUrl;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Please verify your complaint email')
            ->greeting('Hello ' . ($this->complaint->first_name ?? ''))
            ->line('Thank you for submitting your complaint. Before we can process it we need to verify your email address.')
            ->action('Verify Email', $this->verificationUrl)
            ->line('If you did not submit this complaint, no action is required.');
    }
}
