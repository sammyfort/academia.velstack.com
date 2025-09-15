@section('title', "Create Parent")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="create">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create Parent</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Parents" route="parents.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text label="Full Name" model="parent.name"/>
                        <x-input-text label="Email" type="email" model="parent.email"/>
                        <x-input-text label="Phone Number" type="tel" model="parent.phone"/>
                        <x-input-text label="Address" model="parent.address"/>
                        <x-input-text label="Ghana Card No" model="parent.identity_number"/>
                        <x-input-text label="Occupation" model="parent.occupation"/>
                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Create"/>
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
