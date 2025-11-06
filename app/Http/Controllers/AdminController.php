<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard(Request $request)
{
    if (!session()->has('current_role')) {
        return redirect()->route('admin.selectRole');
    }

    $total = \App\Models\Complaint::count();
    $pending = \App\Models\Complaint::where('status', 'pending')->count();
    $resolved = \App\Models\Complaint::where('status', 'resolved')->count();
    $in_progress = \App\Models\Complaint::where('status', 'in_progress')->count();
    $rejected = \App\Models\Complaint::whereIn('status', ['rejected', 'closed'])->count();

    // Recent complaints list with optional search and pagination (5 per page)
    $order = implode("','", \App\Models\Complaint::getSeverityOrder());
    $query = \App\Models\Complaint::orderByRaw("FIELD(type, '{$order}') ASC")
        ->orderBy('created_at', 'desc');

    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function($q) use ($s) {
            $q->where('reference', 'LIKE', "%{$s}%")
              ->orWhere('first_name', 'LIKE', "%{$s}%")
              ->orWhere('last_name', 'LIKE', "%{$s}%")
              ->orWhere('type', 'LIKE', "%{$s}%");
        });
    }

    $recentComplaints = $query->paginate(5)->withQueryString();

    return view('admin.dashboard', compact('total', 'pending', 'resolved', 'rejected', 'in_progress', 'recentComplaints'));
}


    public function complaints(Request $request)
    {
        $query = \App\Models\Complaint::whereIn('status', ['pending', 'in_progress'])
            ->orderByRaw("
                FIELD(
                    type,
                    'Barangay Official Conduct',
                    'Tanod Misconduct',
                    'Domestic Conflict',
                    'Land/Property Dispute',
                    'Noise Disturbance',
                    'Others'
                ) ASC
            ")
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$search}%");
            });
        }

        $complaints = $query->paginate(6)->withQueryString();
        return view('admin.complaintAdmin.index', compact('complaints'));
    }

    public function editComplaint($id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        // Log that the edit form was opened
        try {
            ActivityLog::create([
                'role' => session('current_role') ?? 'Unknown',
                'complaint_id' => $complaint->id,
                'action' => 'open_edit',
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
                'details' => null,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to record activity log (open_edit): ' . $e->getMessage());
        }
        return view('admin.complaintAdmin.edit', compact('complaint'));
    }

    public function showComplaint($id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        // Log that the complaint was viewed
        try {
            ActivityLog::create([
                'role' => session('current_role') ?? 'Unknown',
                'complaint_id' => $complaint->id,
                'action' => 'view',
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
                'details' => null,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to record activity log (view): ' . $e->getMessage());
        }
        return view('admin.complaintAdmin.show', compact('complaint'));
    }

    public function updateComplaint(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'type' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|string|max:255',
            'contact' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

    $complaint = \App\Models\Complaint::findOrFail($id);

        $oldStatus = $complaint->status ?? 'pending';
        $newStatus = $request->input('status');

        $complaint->fill($request->only([
            'status', 'type', 'first_name', 'middle_name', 'last_name',
            'email', 'contact', 'dob', 'address', 'description'
        ]))->save();

        if ($oldStatus !== $newStatus) {
            $changedAt = now();
            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn('complaints', 'status_changed_at')) {
                    $complaint->status_changed_at = $changedAt;
                    $complaint->save();
                }

                if (!empty($complaint->email)) {
                    $complaint->notify(new \App\Notifications\ComplaintStatusChanged(
                        $complaint,
                        $oldStatus,
                        $newStatus,
                        $changedAt
                    ));
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to notify complaint status change: ' . $e->getMessage());
            }
        }

            // Log the update action with basic metadata
            try {
                ActivityLog::create([
                    'role' => session('current_role') ?? 'Unknown',
                    'complaint_id' => $complaint->id,
                    'action' => 'update',
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),
                    'details' => json_encode([
                        'old_status' => $oldStatus,
                        'new_status' => $newStatus,
                    ]),
                ]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning('Failed to record activity log (update): ' . $e->getMessage());
            }

        return redirect()->route('admin.complaints')->with('success', 'Complaint updated.');
    }

    public function destroyComplaint($id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        $ref = $complaint->reference ?? null;

        // Log deletion BEFORE removing the complaint so foreign keys remain valid
        try {
            ActivityLog::create([
                'role' => session('current_role') ?? 'Unknown',
                'complaint_id' => $id,
                'action' => 'delete_complaint',
                'ip' => request()->ip(),
                'user_agent' => request()->header('User-Agent'),
                'details' => $ref,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to record activity log (delete_complaint): ' . $e->getMessage());
        }

        $complaint->delete();

        return redirect()->route('admin.complaints')->with('success', 'Complaint deleted.');
    }

    public function resetComplaints(Request $request)
    {
        if ($request->input('confirm') !== 'RESET') {
            return redirect()->route('admin.complaints')->with('error', 'Missing confirmation token. To reset, send confirm=RESET');
        }

        \App\Models\Complaint::truncate();
        return redirect()->route('admin.complaints')->with('success', 'All complaints have been deleted.');
    }

    public function allFile(Request $request)
    {
        $query = \App\Models\Complaint::orderByRaw("
            FIELD(
                type,
                'Barangay Official Conduct',
                'Tanod Misconduct',
                'Domestic Conflict',
                'Land/Property Dispute',
                'Noise Disturbance',
                'Others'
            ) ASC
        ")->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('type', 'LIKE', "%{$search}%");
            });
        }

        $complaints = $query->paginate(10)->withQueryString();
        return view('admin.all_files', compact('complaints'));
    }

    public function activityLogs(Request $request)
    {
        // only eager-load relations that exist on the model (we use role instead of user_id now)
        $query = ActivityLog::with(['complaint', 'hearing'])->orderBy('created_at', 'desc');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(10)->withQueryString();
        return view('admin.activity_logs.index', compact('logs'));
    }

    public function directContacts()
    {
        return view('admin.direct_contacts');
    }

    public function hearingSchedule()
    {
        return redirect()->route('admin.hearings.index');
    }

    public function selectRole()
    {
        return view('admin.select_role');
    }

    public function setRole(Request $request)
    {
        $request->validate([
            'role' => 'required|in:Secretary,Staff,Kagawad',
        ]);

        session(['current_role' => $request->role]);

        return redirect()->route('admin.dashboard')->with('success', "You are now using the system as {$request->role}.");
    }
}
