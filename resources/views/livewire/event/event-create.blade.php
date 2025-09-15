@section('title', "Create Event")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="create">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Create Event</h5>
                    <div class="d-flex gap-2">

                        <x-link label="Events" route="events.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text label="Event Name" model="event.name"/>
                        <x-input-text label="Event Description" type="textarea" model="event.description"/>
                        <x-input-text label="Start Date" type="date" model="event.start_date"/>
                        <x-input-text label="End Date" type="date" model="event.end_date"/>
                        <x-enum-select enum="App\Enum\NotifyClass" label="Send Notification To" model="event.send_notification_to"/>
                        <x-input-text label="Intervals" type="number" model="event.interval"/>
                        <x-input-text label="Notification Message" type="textarea" model="event.message"/>
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
