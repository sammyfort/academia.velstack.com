@section('title', "Update Timetable")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Update Timetable</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Timetable" route="timetables.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-select label="Term" :options="$terms" key="id" value="name" model="timetable.term_id"/>
                        <x-input-select label="Select Class" :options="$classes" key="id" value="name" model="timetable.class_id"/>
                        <x-input-select label="Select Subject" :options="$subjects" key="id" value="name" model="timetable.subject_id"/>
                        <x-input-select label="Staff (Teacher)" :options="$staff" key="id" value="fullname" model="timetable.staff_id"/>
                        <x-enum-select enum="App\Enum\TimetableDays" label="Day" model="timetable.day" required/>
                        <x-input-text label="Start Time" type="time" model="timetable.start_time"/>
                        <x-input-text label="End Time" type="time" model="timetable.end_time"/>

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
