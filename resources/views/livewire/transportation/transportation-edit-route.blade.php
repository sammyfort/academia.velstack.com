@section('title', "Transport Routes")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Route</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Routes" route="transportations.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text label="Route" model="transportation.route"/>
                        <x-model-select model_class="Fee" label="Fare (GHS)"  model="transportation.fee_id"/>
                        <x-input-text label="Distance" model="transportation.distance"/>
                        <x-input-text label="Description" model="transportation.description"/>

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
