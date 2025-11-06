<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Notifications\HearingScheduled;
use App\Notifications\HearingStatusChanged;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class HearingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Hearing::latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('title', 'LIKE', "%{$s}%")
                  ->orWhere('complainant', 'LIKE', "%{$s}%")
                  ->orWhere('type', 'LIKE', "%{$s}%");
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $hearings = $query->paginate(10)->withQueryString();
        return view('admin.hearings.index', compact('hearings'));
    }

    public function create()
    {
        
        $complaints = \App\Models\Complaint::whereNotIn('status', ['resolved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        
        $complaint = null;
        if (request()->filled('complaint_id')) {
            $complaint = \App\Models\Complaint::find(request()->complaint_id);
        }
        return view('admin.hearings.create', compact('complaints', 'complaint'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'complainant' => 'nullable|string|max:255',
            'complaint_id' => 'nullable|exists:complaints,id',
            'contact' => 'nullable|string|max:50',
            'scheduled_at' => 'nullable|date',
            'details' => 'nullable|string',
            'status' => 'required|string',
        ]);

        // Check for scheduled_at conflict
        if (!empty($data['scheduled_at'])) {
            $conflict = Hearing::where('scheduled_at', $data['scheduled_at'])->exists();
            if ($conflict) {
                return back()->withInput()->withErrors(['scheduled_at' => 'Selected date/time is already occupied. Please choose another time.']);
            }
        }

        $hearing = Hearing::create($data);

        // Log that a hearing was created (scheduled)
        try {
            ActivityLog::create([
                'role' => session('current_role') ?? 'Unknown',
                'hearing_id' => $hearing->id,
                'complaint_id' => $hearing->complaint_id ?? null,
                'action' => 'schedule_hearing',
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
                'details' => json_encode([
                    'scheduled_at' => $hearing->scheduled_at ? $hearing->scheduled_at->toDateTimeString() : null,
                    'title' => $hearing->title,
                ]),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to record activity log (schedule_hearing): ' . $e->getMessage());
        }

        // If the hearings table has a status_changed_at column, set it on creation
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('hearings', 'status_changed_at')) {
                $hearing->status_changed_at = now();
                $hearing->save();
            }
        } catch (\Exception $e) {
            Log::warning('Could not set status_changed_at for hearing: ' . $e->getMessage());
        }

        // Notify complainant if complaint_id present and has email
        try {
            if (!empty($hearing->complaint_id)) {
                $complaint = \App\Models\Complaint::find($hearing->complaint_id);
                if ($complaint && !empty($complaint->email)) {
                    $complaint->notify(new HearingScheduled($hearing));
                    Log::info('Hearing notification sent to: ' . $complaint->email . ' for hearing ' . $hearing->id);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify hearing scheduled: ' . $e->getMessage());
        }

        return redirect()->route('admin.hearings.index')->with('success', 'Hearing scheduled.');
    }

    public function show($id)
    {
        $hearing = Hearing::findOrFail($id);
        return view('admin.hearings.show', compact('hearing'));
    }

    public function edit($id)
    {
        $hearing = Hearing::findOrFail($id);
        // Provide complaints list so the form's complaint selector can render
        $complaints = \App\Models\Complaint::whereNotIn('status', ['resolved', 'rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.hearings.edit', compact('hearing', 'complaints'));
    }

    public function update(Request $request, $id)
    {
        $hearing = Hearing::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'complainant' => 'nullable|string|max:255',
            'complaint_id' => 'nullable|exists:complaints,id',
            'contact' => 'nullable|string|max:50',
            'scheduled_at' => 'nullable|date',
            'details' => 'nullable|string',
            'status' => 'required|string',
        ]);

        // Capture old values to detect changes
        $oldStatus = $hearing->status;
        $oldScheduledAt = $hearing->scheduled_at;

        // If scheduled_at changed, check conflict BEFORE updating
        if (!empty($data['scheduled_at'])) {
            $conflict = Hearing::where('scheduled_at', $data['scheduled_at'])->where('id', '!=', $hearing->id)->exists();
            if ($conflict) {
                return back()->withInput()->withErrors(['scheduled_at' => 'Selected date/time is already occupied. Please choose another time.']);
            }
        }

        $hearing->update($data);

        // If complaint exists, notify appropriately depending on what changed
        try {
            if (!empty($hearing->complaint_id)) {
                $complaint = \App\Models\Complaint::find($hearing->complaint_id);
                if ($complaint && !empty($complaint->email)) {
                    // If the status changed, record timestamp and send a status-changed notification
                    if ($oldStatus !== $hearing->status) {
                        if (\Illuminate\Support\Facades\Schema::hasColumn('hearings', 'status_changed_at')) {
                            $hearing->status_changed_at = now();
                            $hearing->save();
                        }

                        $complaint->notify(new HearingStatusChanged($hearing, $oldStatus ?? 'unknown', $hearing->status));
                        Log::info('Hearing status change notification sent to: ' . $complaint->email . ' for hearing ' . $hearing->id . ' (' . $oldStatus . ' -> ' . $hearing->status . ')');
                    } else {
                        // Otherwise send the updated schedule notification
                        $complaint->notify(new HearingScheduled($hearing));
                        Log::info('Hearing update notification sent to: ' . $complaint->email . ' for hearing ' . $hearing->id);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to notify hearing update: ' . $e->getMessage());
        }

        return redirect()->route('admin.hearings.index')->with('success', 'Hearing updated.');
    }

    public function destroy($id)
    {
        $hearing = Hearing::findOrFail($id);
        $title = $hearing->title ?? null;

        // Log deletion BEFORE removing the hearing so FK references are valid
        try {
            ActivityLog::create([
                'role' => session('current_role') ?? 'Unknown',
                'hearing_id' => $id,
                'complaint_id' => $hearing->complaint_id ?? null,
                'action' => 'delete_hearing',
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
                'details' => $title,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to record activity log (delete_hearing): ' . $e->getMessage());
        }

        $hearing->delete();

        return redirect()->route('admin.hearings.index')->with('success', 'Hearing deleted.');
    }
}
