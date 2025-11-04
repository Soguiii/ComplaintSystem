<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Complaint;

class ComplaintVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Complaint $complaint;
    public string $verificationUrl;

    public function __construct(Complaint $complaint, string $verificationUrl)
    {
        $this->complaint = $complaint;
        $this->verificationUrl = $verificationUrl;
    }

    public function build()
    {
        return $this->subject('Please verify your complaint email')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view('emails.complaint_verification')
                    ->with([
                        'complaint' => $this->complaint,
                        'verificationUrl' => $this->verificationUrl,
                    ]);
    }
}
