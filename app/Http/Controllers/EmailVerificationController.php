<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Notifications\ComplaintVerification;
use App\Mail\ComplaintVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use GuzzleHttp\Client;

class EmailVerificationController extends Controller
{
    protected $emailVerificationApiKey;
    protected $client;

    public function __construct()
    {
        // Get API key from config (you'll need to add this to your .env)
        $this->emailVerificationApiKey = config('services.email_verification.key');
        $this->client = new Client();
    }

    public function verifyEmail($email)
    {
        try {
            // Using Abstract's Email Validation API as an example
            // You can replace this with any email validation API
            $response = $this->client->get('https://emailvalidation.abstractapi.com/v1/', [
                'query' => [
                    'api_key' => $this->emailVerificationApiKey,
                    'email' => $email
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            // Check if email is valid and has a high quality score
            return [
                'is_valid' => $result['is_valid'] ?? false,
                'score' => $result['quality_score'] ?? 0,
                'message' => $result['message'] ?? null
            ];
        } catch (\Exception $e) {
            // Log the error but don't expose it to the user
            Log::error('Email verification failed: ' . $e->getMessage());
            return [
                'is_valid' => false,
                'score' => 0,
                'message' => 'Email verification service unavailable'
            ];
        }
    }

    public function sendVerification(Complaint $complaint)
    {
        // Generate verification token
        $token = Str::random(64);
        $complaint->verification_token = $token;
        $complaint->save();

        // Generate verification URL
        $verificationUrl = route('complaints.verify', [
            'id' => $complaint->id,
            'token' => $token
        ]);

        // Send notification (uses the Notification system)
        try {
            $complaint->notify(new ComplaintVerification($complaint, $verificationUrl));

            // Also send a direct mailable as a fallback so we can log/send explicitly
            Mail::to($complaint->email)->send(new ComplaintVerificationMail($complaint, $verificationUrl));

            Log::info('Verification email sent to: ' . $complaint->email . ' for complaint ' . $complaint->reference);
        } catch (\Exception $e) {
            // Log errors so they can be inspected; the HTTP response remains generic
            Log::error('Failed to send verification email: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to send verification email'], 500);
        }

        return response()->json(['message' => 'Verification email sent']);
    }

    public function verify(Request $request, $id, $token)
    {
        $complaint = Complaint::findOrFail($id);

        if ($complaint->verification_token !== $token) {
            return redirect()->route('complaint')->with('error', 'Invalid verification token');
        }

        if ($complaint->email_verified) {
            return redirect()->route('complaint')->with('info', 'Email already verified');
        }

        $complaint->email_verified = true;
        $complaint->email_verified_at = now();
        $complaint->verification_token = null;
        $complaint->save();

        // After verification, redirect back to the complaint form and show the reference code
        return redirect()->route('complaint')
            ->with('success', 'Email verified successfully. Please check your email for your reference code.')
            ->with('reference', $complaint->reference);
    }
}