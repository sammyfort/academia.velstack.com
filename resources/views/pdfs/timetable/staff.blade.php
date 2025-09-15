@extends('pdfs.layout.app')

@section("title", "Staff Timetable - $staff->fullname")

@section('content')
    <style>
        /* Full-page styling */
        .page-container {
            width: 100%;
            padding: 5px;
        }

        /* Responsive table container */
        .table-container {
            width: 100%;
            overflow-x: auto; /* Enables horizontal scrolling if needed */
        }

        /* Table styles */
        .table {
            width: 100%;
            table-layout: fixed; /* Ensures equal column distribution */
            border-collapse: collapse;
            font-size: 12px; /* Reduce font size */
        }

        /* Table cell styles */
        .table th, .table td {
            padding: 5px; /* Reduce padding */
            font-size: 12px; /* Reduce font size */
            vertical-align: middle;
            word-break: break-word; /* Prevents text from overflowing */
            white-space: normal; /* Allows wrapping */
            text-align: center;
        }

        /* Reduce header font size */
        .table thead {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        /* Adjust column width */
        .table th:first-child,
        .table td:first-child {
            width: 8%; /* Narrow 'Day' column */
        }

        .table th:not(:first-child),
        .table td:not(:first-child) {
            width: auto; /* Allow other columns to resize */
        }

        /* Prevents large gaps */
        @media (max-width: 768px) {
            .table th, .table td {
                font-size: 10px; /* Further reduce size on small screens */
                padding: 3px; /* Reduce padding */
            }
        }
    </style>
    <div class="page-container">
        <div class="content-wrapper">
            <h2 class="text-center mb-4">{{$staff->school->name}}</h2>
            <h6 class="text-center mb-4">Timetable  For - {{ $staff->fullname }}</h6>

            <div class="table-responsive">
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
                                        $entry = $timetables->where('staff_id', $staff->id)
                                                           ->where('day', $day)
                                                           ->where('start_time', $slot->start_time)
                                                           ->where('end_time', $slot->end_time)
                                                           ->first();
                                    @endphp
                                    @if ($entry)
                                        <strong>{{ $entry->subject->name }}</strong> <br>
                                        <span class="text-muted">{{ $entry->class->name }}</span>
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
@endsection
<script>
    window.onload = function () {
        setTimeout(function () {
            window.print();
        }, 100);
    };
    window.onafterprint = function () {
        // window.location.href = '/previous-page';
    };
</script>
