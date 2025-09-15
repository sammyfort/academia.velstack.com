@section('title', "Update Fee")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create New Fee</h5>
                    <div class="d-flex gap-2">
                        <x-link label="Fees" route="finance.fee.index" class="btn btn-info add-bt" icon="ri-grid-fill me-1 align-bottom" />
                    </div>
                </div>
                <div class="card-body">
                    <div x-data="{ feeType: @entangle('fee.type') }" class="row g-4">
                        <x-input-text wrapper_class="col-md-4" label="Name" model="fee.name" />
                        <x-input-text wrapper_class="col-md-4" label="Amount" type="number" model="fee.amount" />

                        <x-input-select wrapper_class="col-md-4" label="Academic Year"
                                        :options="$terms" key="id" value="name"
                                        model="fee.term_id" :choices="false"  />
                        <x-input-text wrapper_class="col-md-4" label="Description" model="fee.description" type="textarea" />



                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Update" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
