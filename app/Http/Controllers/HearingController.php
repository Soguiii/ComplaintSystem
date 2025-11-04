<?php

namespace App\Http\Controllers;

use App\Models\Hearing;
use Illuminate\Http\Request;

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

        Hearing::create($data);

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

        $hearing->update($data);

        return redirect()->route('admin.hearings.index')->with('success', 'Hearing updated.');
    }

    public function destroy($id)
    {
        $hearing = Hearing::findOrFail($id);
        $hearing->delete();

        return redirect()->route('admin.hearings.index')->with('success', 'Hearing deleted.');
    }
}
