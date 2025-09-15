@section('title', "Edit Calender")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Academic Calender ({{$term->name}})</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Calenders" route="calenders.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-enum-select enum="App\Enum\AcademicTerm" label="Calender Name" model="calender.name" required/>
                        <x-input-text class="flatpickr" label="Start Date" type="date" model="calender.start_date"  placeholder="Select"/>
                        <x-input-text label="End Date" type="date" model="calender.end_date" placeholder="Select"/>
                        <x-enum-select :choices="false" enum="App\Enum\TermStatus" label="Calender Status" model="calender.status" />
                        <x-input-text label="Total Days" type="number" model="calender.days"/>
                        <x-input-text label="Next Term Begins" type="date" model="calender.next_term_begins" placeholder="Select"/>
                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Update"/>
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
