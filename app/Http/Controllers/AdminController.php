<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        $total = \App\Models\Complaint::count();
        $pending = \App\Models\Complaint::where('status', 'pending')->count();
        $resolved = \App\Models\Complaint::where('status', 'resolved')->count();
        $in_progress = \App\Models\Complaint::where('status', 'in_progress')->count();
        $rejected = \App\Models\Complaint::whereIn('status', ['rejected', 'closed'])->count();

        // Recent complaints list with optional search and pagination (5 per page)
        $query = \App\Models\Complaint::latest();

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
        $query = \App\Models\Complaint::latest()->whereIn('status', ['pending', 'in_progress']);

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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
        return view('admin.complaintAdmin.edit', compact('complaint'));
    }

    public function showComplaint($id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        return view('admin.complaintAdmin.show', compact('complaint'));
    }

    public function updateComplaint(\Illuminate\Http\Request $request, $id)
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

    // detect status change
    $oldStatus = $complaint->status ?? 'pending';
    $newStatus = $request->input('status');

    $complaint->status = $newStatus;
    $complaint->type = $request->input('type');
    $complaint->first_name = $request->input('first_name');
    $complaint->middle_name = $request->input('middle_name');
    $complaint->last_name = $request->input('last_name');
    $complaint->email = $request->input('email');
    $complaint->contact = $request->input('contact');
    $complaint->dob = $request->input('dob');
    $complaint->address = $request->input('address');
    $complaint->description = $request->input('description');

    $complaint->save();

    // If status changed, record timestamp (if column exists) and notify user
    if ($oldStatus !== $newStatus) {
        $changedAt = now();
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('complaints', 'status_changed_at')) {
                $complaint->status_changed_at = $changedAt;
                $complaint->save();
            }

            if (!empty($complaint->email)) {
                // notify via notification and also send direct mail as fallback
                $complaint->notify(new \App\Notifications\ComplaintStatusChanged($complaint, $oldStatus, $newStatus, $changedAt));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to notify complaint status change: ' . $e->getMessage());
        }
    }

    return redirect()->route('admin.complaints')->with('success', 'Complaint updated.');
}

    public function destroyComplaint($id)
    {
        $complaint = \App\Models\Complaint::findOrFail($id);
        $complaint->delete();

        return redirect()->route('admin.complaints')->with('success', 'Complaint deleted.');
    }

    /**
     * Reset all complaints (truncate). Admin-only operation.
     */
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
        $query = \App\Models\Complaint::latest();

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%");
            });
        }

        $complaints = $query->paginate(10)->withQueryString();
        return view('admin.all_files', compact('complaints'));
    }

    public function directContacts()
    {
        return view('admin.direct_contacts');
    }

    public function hearingSchedule()
    {

        return redirect()->route('admin.hearings.index');
    }


}
