@section('title', "Create Parent")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Edit Parent</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Parents" route="parents.index" class="btn btn-info" icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text label="Full Name" model="edit.name"/>
                        <x-input-text label="Email" type="email" model="edit.email"/>
                        <x-input-text label="Phone Number" type="tel" model="edit.phone"/>
                        <x-input-text label="Address"   model="edit.address"/>
                        <x-input-text label="Ghana Card No" model="edit.identity_number"/>
                        <x-input-text label="Occupation" model="edit.occupation"/>
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
