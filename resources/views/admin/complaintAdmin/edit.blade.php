@extends('layouts.appAdmin')
@section('title', 'Admin | Edit') 
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header brand-green">Edit Complaint</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.complaints.update', $complaint->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label" >First Name</label>
                                <input id="first_name" name="first_name" class="form-control" value="{{ $complaint->first_name ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input id="middle_name" name="middle_name" class="form-control" value="{{ $complaint->middle_name ?? '' }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input id="last_name" name="last_name" class="form-control" value="{{ $complaint->last_name ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" name="email" type="text" class="form-control" value="{{ $complaint->email ?? '' }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact" class="form-label">Contact</label>
                                <input id="contact" name="contact" class="form-control" value="{{ $complaint->contact ?? '' }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input id="dob" name="dob" type="date" class="form-control" value="{{ $complaint->dob ?? '' }}">
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input id="address" name="address" class="form-control" value="{{ $complaint->address ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type of Complaint</label>
                            @php
                                $types = ['Domestic Conflict','Tanod Misconduct','Land/Property Dispute','Noise Disturbance','Barangay Official Concern','Other'];
                                if ($complaint->type && !in_array($complaint->type, $types)) {
                                    array_unshift($types, $complaint->type);
                                }
                            @endphp
                            <select id="type" name="type" class="form-select">
                                @foreach($types as $t)
                                    <option value="{{ $t }}" @if(($complaint->type ?? '') === $t) selected @endif>{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Complaint / Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4">{{ $complaint->description ?? '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                @php $options = ['pending','in_progress','resolved','rejected']; @endphp
                                @foreach($options as $opt)
                                    <option value="{{ $opt }}" @if(($complaint->status ?? 'pending') === $opt) selected @endif>{{ ucwords(str_replace('_',' ',$opt)) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.complaints') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-green">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
