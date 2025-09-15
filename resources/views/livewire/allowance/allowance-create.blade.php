@section('title', "Create Allowance")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="create">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create Allowance</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Staff" route="staff.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">

                        <x-enum-select enum="App\Enum\AllowanceType" label="Type" model="allowance.type" required/>
                        <x-enum-select enum="App\Enum\AllowanceCalculationType" label="Calculation Type" model="allowance.calculation_type" required/>
                        <x-input-text label=" Name" model="allowance.name" required/>
                        <x-input-text label="Amount" type="number" step="0.01" model="allowance.amount" required/>


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
