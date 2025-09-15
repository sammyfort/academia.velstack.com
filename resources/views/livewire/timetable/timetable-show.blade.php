<div>
    @section("title", 'Timetables')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">
                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <h2 class="card-title mb-0 text-center">School Timetable</h2>
                            <h6 class="card-title mb-0 text-center">{{$term->name}}</h6>
                        </div>
                        <div class="col-sm-auto">
                            <x-link label="Timetables" route="timetables.index" class="btn btn-primary" icon="ri-grid-line align-bottom me-1"/>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card mb-1">
                        <table class="table table-bordered text-center">
                            <thead class="table-light">
                            <tr>
                                <th>Day</th>
                                @foreach ($timeSlots as $slot)
                                    <th>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($days as $day)
                                <tr>
                                    <td>{{ ucfirst($day->value) }}</td>
                                    @foreach ($timeSlots as $slot)
                                        <td>
                                            @php
                                                $entry = $timetables->where('day', $day)
                                                                   ->where('start_time', $slot->start_time)
                                                                   ->where('end_time', $slot->end_time)
                                                                   ->first();
                                            @endphp
                                            @if ($entry)
                                                <strong>{{ $entry->subject->name }} (<span class="text-muted">{{ $entry->class->name }}</span>)</strong> <br>

                                                <span class="text-muted">{{ $entry->staff->fullname }}</span>
                                            @else
                                                <span class="text-muted">---</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <livewire:modal.delete-modal/>
        </div>
    </div>

    @section('script')
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
</div>
