@section('title', "Logs")
<div class="row" >
    <div class="col-lg-12">

            <div class="card-body">
                <h5 class="card-title">Activities</h5>

                <div class="acitivity-timeline py-3">
                    @forelse($logs as $log)
                        @php
                            $bg = match ($log->event){
                               'deleted' => 'danger',
                               'created' => 'success',
                               'updated' => 'info',
                               'restored' => 'primary',
                               default  => 'secondary'
                             };
                        @endphp
                        <div class="acitivity-item py-3 d-flex">
                            <div class="flex-shrink-0">
                                <div class="avatar-xs acitivity-avatar">
                                    <div class="avatar-title rounded-circle bg-{{$bg}}-subtle text-{{$bg}}">
                                        <i class="ri-history-line"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{($log->subject->logger_name ?? str_replace('_', '', class_basename($log->subject_type))). " $log->event by {$log->causer->fullname}"}}

                                    <span class="badge bg-{{$bg}}-subtle text-{{$bg}} align-middle ms-1">{{$log->event}}</span>
                                </h6>
                                <p class="text-muted mb-2"> {!! $log->description !!} </p>

                                <small class="mb-0 text-muted">
                                    @if ($log->created_at->diffInHours() < 24)
                                        {{ $log->created_at->diffForHumans() }}
                                    @else
                                        {{ $log->created_at->format('d M Y - h:i A') }}
                                    @endif
                                </small>
                            </div>
                        </div>

                    @empty
                        <p class="text-muted mb-2">No Log Found</p>
                    @endforelse


                    <x-paginate :collection="$logs"/>
                </div>
            </div>
            <!--end card-body-->

    </div>
</div>

@section('script')
    <script src="{{URL::asset('build/js/app.js')}}"></script>
@endsection
