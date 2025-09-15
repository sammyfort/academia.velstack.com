@section('title', "Edit ScoreType")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit ScoreType For <span class="bold text-danger">{{$class->name}}</span></h5>
                    <div class="d-flex gap-2">

                        <x-link label="BACK TO CLASS" route="classes.show" :param="$class->uuid"
                                class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text model="scoreType.name" label="Score Type"  />
                        <x-input-text model="scoreType.percentage" type="number" label=" Percentage"/>

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
