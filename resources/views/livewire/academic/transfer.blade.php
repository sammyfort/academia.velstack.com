@section('title')
    {{"Transfer"}}
@endsection
<div class="page-wrapper">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Transfer Student</h3>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <form wire:submit.prevent="transfer">
                            <div class="row">

                                <div class="col-12">
                                    <h5 class="form-title"><span>Select Student</span></h5>
                                </div>

                                <div class="col-12 col-sm-4" >
                                    <div class="form-group local-forms" wire:ignore>
                                        <select id="studentSelect" wire:model.live="student_id" class="form-select" style=" height: 50px">
                                            <option value="">Select student</option>
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->index_number }} -
                                                    {{ "$student->first_name  $student->last_name"}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('student_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>


                                <div class="col-12 col-sm-4" >
                                    <div class="form-group local-forms" wire:ignore>
                                        <select id="schoolSelect" wire:model.live="school_id" class="form-select" style=" height: 50px">
                                            <option value="">Select school</option>
                                            @foreach($schools as $school)
                                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('school_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-12 mt-3">
                                    <div class="student-submit">
                                        <button type="submit" class="btn btn-primary">Transfer</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@assets
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endassets
@script
<script>

    $(document).ready(function() {
        $('#studentSelect').select2();
        $('#studentSelect').on('change', function (event){
            $wire.set('student_id', event.target.value)
        })
    });

    $(document).ready(function() {
        $('#schoolSelect').select2();
        $('#schoolSelect').on('change', function (event){
            $wire.set('school_id', event.target.value)
        })
    });
</script>
@endscript
