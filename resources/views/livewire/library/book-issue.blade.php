@section('title', "Issue Book")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="issue">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Issue Book</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Books" route="classes.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-enum-select enum="App\Enum\UserType" model="lend.lend_to"
                                       :choices="false" bind=".live"
                                       label="Issue To"
                        />
                        @if($lend['lend_to'])
                            @if($lend['lend_to']  == \App\Enum\UserType::STUDENT->value)
                                <x-input-select :options="$students" model="lend.student_id"
                                                :choices="false" bind=".live"
                                                key="id" value="fullname"
                                                label="Select Student"
                                />
                            @else
                                <x-input-select :options="$staff" model="lend.staff_id"
                                                :choices="false" bind=".live" key="id"  value="fullname"
                                                label="Select Staff"
                                />
                            @endif

                            @if($lend['student_id'] || $lend['staff_id'])
                                <x-input-select :options="$books" model="lend.book_id"
                                                :choices="false" key="id" value="title"
                                                label="Select Book"
                                />

                                <x-input-text   model="lend.due_on" type="date"
                                                label="Due Date"
                                />
                            @endif

                        @endif

                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Issue"/>
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
