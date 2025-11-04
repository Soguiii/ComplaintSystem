<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {

        $total = \App\Models\Complaint::count();
        $pending = \App\Models\Complaint::where('status', 'pending')->count();
        $resolved = \App\Models\Complaint::where('status', 'resolved')->count();
        $in_progress = \App\Models\Complaint::where('status', 'in_progress')->count();
        $rejected = \App\Models\Complaint::whereIn('status', ['rejected', 'closed'])->count();

        $recentComplaints = \App\Models\Complaint::latest()->limit(5)->get();

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

    $complaint->status = $request->input('status');
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
