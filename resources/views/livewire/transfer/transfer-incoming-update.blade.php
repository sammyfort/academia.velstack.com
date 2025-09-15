@section('title', "Transfer View")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Transfer View</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Transfers" route="transfers.incoming" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-enum-select enum="App\Enum\TransferStatus" bind=".live" label="Status" model="transfer.status" required/>
                        @if($transfer['status'] === \App\Enum\TransferStatus::APPROVED->value)
                            <x-input-select label="Class" :options="$classes" key="id" value="name" model="transfer.class_id" />
                            <x-input-text label="Reason" type="textarea" model="transfer.reason"/>
                        @endif
                        <x-input-text label="School" model="transfer.school_name" disabled/>
                        <x-input-text label="School Region" model="transfer.school_region" disabled/>
                        <x-input-text label="School Address" model="transfer.school_address" disabled/>
                        <x-input-text label="School Number" type="tel" model="transfer.school_number" disabled/>
                        <x-input-text label="Student" model="transfer.student_name" disabled/>
                        <x-input-text label="Class" model="transfer.student_class" disabled/>
                        <x-input-text label="Requested Date" type="date" model="transfer.requested_date" disabled/>

                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Update Transfer"/>
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
