<?php

namespace App\Http\Controllers;
use App\Models\Complaint;
use App\Notifications\ComplaintVerification;
use App\Mail\ComplaintVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::latest()->get();
        return view('complaints.index', compact('complaints'));
    }

    protected $emailVerificationController;

    public function __construct(EmailVerificationController $emailVerificationController)
    {
        $this->emailVerificationController = $emailVerificationController;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'contact' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'address' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);


        $ref = null;
        do {
            $ref = 'CMP-'.date('Ymd').'-'.strtoupper(\Illuminate\Support\Str::random(6));
        } while (Complaint::where('reference', $ref)->exists());

        $validated['reference'] = $ref;
        $validated['email_verified'] = false;
        $validated['verification_token'] = \Illuminate\Support\Str::random(64);
        
        $complaint = Complaint::create($validated);
        
        // Create verification URL
        $verificationUrl = route('complaints.verify', ['id' => $complaint->id, 'token' => $validated['verification_token']]);
        
        // Send verification email via notification and also send a direct Mailable as a fallback
        try {
            $complaint->notify(new ComplaintVerification($complaint, $verificationUrl));
            Mail::to($complaint->email)->send(new ComplaintVerificationMail($complaint, $verificationUrl));

            Log::info('Verification email queued/sent for complaint ' . $complaint->reference . ' to ' . $complaint->email);
        } catch (\Exception $e) {
            Log::error('Error sending verification email: ' . $e->getMessage());
            // don't block response on mail failure; show verify page anyway
        }
        
        return view('verify-email')->with('reference', $ref);
    }

    public function track(Request $request)
    {
        $complaint = null;

        if ($request->isMethod('post')) {

            if ($request->filled('reference')) {
                $complaint = Complaint::with('hearing')->where('reference', $request->reference)->first();
                $request->validate([
                    'reference' => 'required|string|max:255'
                ]);
            } 
            elseif ($request->filled('first_name') || $request->filled('last_name')) {
                $request->validate([
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                ]);

                $query = Complaint::with('hearing');
                
                if ($request->filled('first_name')) {
                    $query->where('first_name', 'LIKE', '%' . $request->first_name . '%');
                }
                
                if ($request->filled('last_name')) {
                    $query->where('last_name', 'LIKE', '%' . $request->last_name . '%');
                }

                $complaint = $query->first();
            }
        }

        // Flash input to the session
        $request->flash();
        
        return view('track', compact('complaint'));
    }

}
