@section('title', "Return Book")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="returnBook">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Return Book</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Books" route="library.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-enum-select enum="App\Enum\UserType" model="return.from"
                                       :choices="false" bind=".live"
                                       label="Return From"
                        />

                        @if($return['from'])
                            @if($return['from']  == \App\Enum\UserType::STUDENT->value)
                                <x-input-select :options="$students" model="return.student_id"
                                                :choices="false" bind=".live"
                                                key="id" value="fullname"
                                                label="Select Student"
                                />
                            @else
                                <x-input-select :options="$staff" model="return.staff_id"
                                                :choices="false" bind=".live"
                                                key="id" value="fullname"
                                                label="Select Staff"
                                />
                            @endif

                            @if($return['student_id'] || $return['staff_id'])
                                <div class="col-md-6 mb-3" >
                                    <div class="form-group">
                                        <label>Select Book</label>
                                        <select class="form control form-select" wire:model="return.book_id">
                                            <option value="">Select...</option>
                                            @forelse($lentBooks as $book)
                                                <option value="{{$book->book->id}}">{{$book->book->title}}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                        <x-error key="return.book_id"/>
                                    </div>
                                </div>
                            @endif

                        @endif
                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Return"/>
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
