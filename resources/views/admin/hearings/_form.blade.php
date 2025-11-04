<div class="mb-3">
    <label class="form-label">Select Complaint</label>
    <select id="complaint_selector" class="form-select @error('complaint_id') is-invalid @enderror" required>
        <option value="">Select a complaint...</option>
        @foreach($complaints as $c)
            <option value="{{ $c->id }}" 
                    data-reference="{{ $c->reference }}"
                    data-type="{{ $c->type }}"
                    data-name="{{ $c->first_name }} {{ $c->last_name }}"
                    data-contact="{{ $c->contact }}"
                    {{ (old('complaint_id', $hearing->complaint_id ?? ($complaint->id ?? '')) == $c->id) ? 'selected' : '' }}>
                {{ $c->reference }} - {{ $c->first_name }} {{ $c->last_name }} ({{ $c->type }})
            </option>
        @endforeach
    </select>
    @error('complaint_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<input type="hidden" name="complaint_id" id="complaint_id" value="{{ old('complaint_id', $hearing->complaint_id ?? ($complaint->id ?? '')) }}">

<div class="mb-3">
    <label class="form-label">Hearing Title</label>
    <input type="text" id="hearing_title" name="title" class="form-control @error('title') is-invalid @enderror" readonly required>
    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Type of Complaint</label>
        <input type="text" id="complaint_type" name="type" class="form-control" readonly>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Complainant</label>
        <input type="text" id="complainant_name" name="complainant" class="form-control" readonly>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Contact No.</label>
        <input type="text" id="contact_number" name="contact" class="form-control" readonly>
    <div class="col-md-6 mb-3">
        <label class="form-label">Set Date</label>
        <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at', isset($hearing) && $hearing->scheduled_at ? $hearing->scheduled_at->format('Y-m-d\TH:i') : '') }}">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Hearing Details</label>
    <textarea name="details" class="form-control" rows="5">{{ old('details', $hearing->details ?? '') }}</textarea>
    
</div>

{{-- Conflict modal: shows when scheduled_at validation error exists --}}
<div class="modal fade" id="conflictModal" tabindex="-1" aria-labelledby="conflictModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="conflictModalLabel">Scheduling conflict</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="conflictMessage">The selected date/time is already occupied. Please choose another time.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select">
        <option value="scheduled" {{ old('status', $hearing->status ?? '') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
        <option value="resolved" {{ old('status', $hearing->status ?? '') == 'resolved' ? 'selected' : '' }}>Resolved</option>
        <option value="cancelled" {{ old('status', $hearing->status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
</div>

<script>
    // Wait for the DOM to be fully loaded
    window.addEventListener('load', function() {
        // Get the complaint selector
        const complaintSelector = document.getElementById('complaint_selector');
        
        // Function to update all fields
        function updateFields() {
            const selectedOption = complaintSelector.options[complaintSelector.selectedIndex];
            const complaintId = complaintSelector.value;
            
            // Get all the required elements
            const hearingTitleInput = document.getElementById('hearing_title');
            const complaintTypeInput = document.getElementById('complaint_type');
            const complainantNameInput = document.getElementById('complainant_name');
            const contactNumberInput = document.getElementById('contact_number');
            const complaintIdInput = document.getElementById('complaint_id');
            
            // Update hidden complaint_id
            complaintIdInput.value = complaintId;
            
            if (complaintId && selectedOption) {
                // Get data from selected option
                const reference = selectedOption.dataset.reference;
                const type = selectedOption.dataset.type;
                const name = selectedOption.dataset.name;
                const contact = selectedOption.dataset.contact;
                
                // Update form fields
                hearingTitleInput.value = `Hearing for: ${reference}`;
                complaintTypeInput.value = type;
                complainantNameInput.value = name;
                contactNumberInput.value = contact;
                
                console.log('Updated fields:', {
                    title: hearingTitleInput.value,
                    type: complaintTypeInput.value,
                    name: complainantNameInput.value,
                    contact: contactNumberInput.value
                });
            } else {
                // Clear form fields if no complaint selected
                hearingTitleInput.value = '';
                complaintTypeInput.value = '';
                complainantNameInput.value = '';
                contactNumberInput.value = '';
            }
        }
        
        // Update fields when the page loads
        updateFields();
        
        // Update fields when a different complaint is selected
        complaintSelector.addEventListener('change', updateFields);
        
        // Log initial state
        console.log('Form initialized');

        // If there was a server-side validation error for scheduled_at, show the modal
        try {
            var scheduleError = @json($errors->first('scheduled_at'));
        } catch (e) {
            var scheduleError = null;
        }

        if (scheduleError) {
            // set message if available
            var msgEl = document.getElementById('conflictMessage');
            if (msgEl) { msgEl.textContent = scheduleError; }

            // Show Bootstrap modal (v5)
            if (typeof bootstrap !== 'undefined' && document.getElementById('conflictModal')) {
                var conflictModal = new bootstrap.Modal(document.getElementById('conflictModal'));
                conflictModal.show();
            } else {
                // fallback to alert
                alert(scheduleError);
            }
        }
    });
</script>
