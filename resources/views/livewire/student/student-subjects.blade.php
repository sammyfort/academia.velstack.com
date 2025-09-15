@section("title", 'Student Subjects')


<div class="row">
    <div class="col-lg-12">
        <div class="card" id="customerList">
            <div class="card-header border-bottom-dashed">

                <div class="row g-4 align-items-center">
                    <div class="col-sm">
                        <div class="mb-3">
                            <h5 class="card-title mb-0">Student Subjects</h5>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">
                                Student: <span class="text-danger">{{"$student->fullname"}}</span></h5>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">
                                Index Number: <span class="text-danger">{{"$student->index_number"}}</span></h5>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">
                                Class: <span class="text-danger">{{$student->class->name}}</h5>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">
                                Subjects: <span class="text-danger">{{$student->subjects_count}}</h5>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex flex-wrap align-items-start gap-2">

                            <x-link label="Back To Class" route="classes.show" param="{{$student->class->uuid}}" class="btn btn-primary"
                                    icon="ri-arrow-left-line align-bottom me-1"/>

                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-md-2 row-cols-1">
                    @forelse($student->class->subjects as $subject)
                        <div class="col">
                            <div class="card">
                                <a class="card-body bg-primary-subtle" data-bs-toggle="collapse"
                                   href="#{{$subject->id}}" role="button"
                                   aria-expanded="false" aria-controls="{{$subject->id}}">
                                    <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">
                                        {{$subject->name}}
                                        @if($student->hasSubject($subject->id))
                                            <small class="badge bg-success-subtle text-success">ASSIGNED</small>
                                        @else
                                            <small class="badge bg-danger-subtle text-danger">NOT ASSIGNED</small>
                                        @endif
                                    </h5>

                                    </p>
                                </a>
                            </div>
                            <!--end card-->
                            <div class="collapse " id="{{$subject->id}}">
                                <div class="card mb-1">
                                    <div class="card-footer hstack gap-2">
                                        @if($student->hasSubject($subject->id))
                                            <button wire:click="remove({{$subject->id}})"
                                                    class="btn btn-danger btn-sm w-100"><i
                                                    class="ri-close-fill align-bottom me-1"></i>
                                                Remove
                                            </button>
                                        @else
                                            <button wire:click="attach({{$subject->id}})"
                                                    class="btn btn-success btn-sm w-100"><i
                                                    class="ri-checkbox-circle-fill align-bottom me-1"></i>
                                                Assign
                                            </button>
                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>
                    @empty
                        <x-no-result title="No subject found"/>
                    @endforelse
                    <!--end col-->
                </div>

            </div>

        </div>
        <!--end col-->
    </div>

    @section('script')
        <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/pages/ecommerce-order.init.js') }}"></script>
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
