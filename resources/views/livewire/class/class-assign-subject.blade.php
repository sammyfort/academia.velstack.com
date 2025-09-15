@section('title', "Assign Subjects")
<div>
    <div class="card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="search-box">
                        <input type="text" wire:model.live="search" class="form-control search" placeholder="Search...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <!--end col-->
                <div class="col-md-auto ms-auto">
                    <div class="d-flex hastck gap-2 flex-wrap">
                        <x-link label="Classes" route="classes.index" icon="ri-grid-fill align-bottom me-1"  class="btn btn-primary"/>
                    </div>
                </div>
                <!--end col-->
            </div>
            <!--end row-->
        </div>
    </div>
    <!--end card-->

    <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-md-2 row-cols-1">
        @forelse($classes as $class)
            <div class="col">
                <div class="card" x-data="{ isOpen: false }" x-init="$watch('isOpen', value => value ? $el.querySelector('.collapse').classList.add('show') : $el.querySelector('.collapse').classList.remove('show'))">
                    <a class="card-body bg-primary-subtle"
                       @click="isOpen = !isOpen"
                       role="button">
                        <h5 class="card-title text-uppercase fw-semibold mb-1 fs-15">{{$class->name}}</h5>
                        <p class="text-muted mb-0">{{$class->subjects_count}}<span class="fw-medium"> Subject(s)</span></p>
                    </a>
                    <!--end card-->
                    <div class="collapse" :class="{ 'show': isOpen }">
                        @forelse($subjects as $subject)
                            <div class="card mb-1">
                                <div class="card-body">
                                    <h6 class="fs-14 mb-1">{{$subject->name}}
                                        @if(in_array($subject->id, $class->subjects->pluck('id')->toArray()))
                                            <small class="badge bg-success-subtle text-success">ASSIGNED</small>
                                        @else
                                            <small class="badge bg-danger-subtle text-danger">NOT ASSIGNED</small>
                                        @endif
                                    </h6>
                                    <p class="text-muted">{{$subject->code}}</p>
                                </div>

                                <div class="card-footer hstack gap-2">
                                    @if(in_array($subject->id, $class->subjects->pluck('id')->toArray()))
                                        <button wire:click="remove({{$class->id}}, {{$subject->id}})"
                                                @click.stop="isOpen = true"
                                                class="btn btn-danger btn-sm w-100">
                                            <i class="ri-close-fill align-bottom me-1"></i> Remove
                                        </button>
                                    @else
                                        <button wire:click="attach({{$class}}, {{$subject}})"
                                                @click.stop="isOpen = true"
                                                class="btn btn-success btn-sm w-100">
                                            <i class="ri-checkbox-circle-fill align-bottom me-1"></i> Assign
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        @empty
            <x-no-result title="No Class found"/>
        @endforelse
        <!--end col-->
    </div>

    <!--end row-->
    <x-paginate :collection="$classes"/>
</div>


@section('script')
    <script src="{{ URL::asset('build/libs/cleave.js/cleave.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/crm-deals.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
