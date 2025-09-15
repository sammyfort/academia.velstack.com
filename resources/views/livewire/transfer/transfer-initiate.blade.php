@section('title', "Initiate Trabsfer")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="transfer">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Transfer Student</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Transfers" route="transfers.outgoing" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="mb-3 form-group col-md-6">
                            <label for="parents" class="form-label">Student<span class="text-danger">*</span></label>
                            <select class="form-control form-select {{ $errors->has('student_id') ? 'is-invalid' : '' }}" wire:model="student_id" id="student">
                                <option  value="">Select Student</option>
                                @forelse($students as $student)
                                    <option value="{{$student->id}}">{{"$student->fullname - $student->index_number"}}</option>
                                @empty
                                    <option value="" class="text-danger">No Student to select from</option>
                                @endforelse
                            </select>
                            <x-error key="student_id"/>
                        </div>

                        <div class="mb-3 form-group col-md-6">
                            <label for="parents" class="form-label">Transfer To <span class="text-danger">*</span></label>
                            <select class="form-control form-select {{ $errors->has('to_school_id') ? 'is-invalid' : '' }}" wire:model="to_school_id" id="student">
                                <option  value="">Select School</option>
                                @forelse($schools as $school)
                                    <option value="{{$school->id}}">{{"$school->name - $school->region"}}</option>
                                @empty
                                    <option value="" class="text-danger">No School to select from</option>
                                @endforelse
                            </select>
                            <x-error key="to_school_id"/>
                        </div>

                        <x-input-text label="Reason For Transfer" type="textarea" model="reason" required/>


                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Transfer"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
    <script src="{{URL::asset('build/js/app.js')}}"></script>
@endsection
