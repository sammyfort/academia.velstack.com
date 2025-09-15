@section('title', "Send SMS Notification")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="send">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Send SMS Notification</h5>
                    
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-enum-select enum="App\Enum\SMSReceivers"
                                       label="Who are you sending to?" bind=".live"
                                       :choices="false"  model="sms.send_to"/>

                        @if($sms['send_to'] == \App\Enum\SMSReceivers::INDIVIDUAL->value)
                            <x-input-text type="textarea" label="Enter Recipient Number eg (020XX,024XX)" model="sms.recipients"/>
                        @endif

                       @if($sms['send_to'])
                            <x-input-text label="Message Content" type="textarea" model="sms.message"/>
                       @endif
                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Send"/>
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
