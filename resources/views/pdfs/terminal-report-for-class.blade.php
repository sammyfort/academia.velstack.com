@extends('pdfs.layout.app')

@section('title', 'Student Terminal Report')

@section('content')
    @foreach ($reportCards as $reportCard)
        @php
            $student = $reportCard['student'];
            $school = $student->school;
            $class = $reportCard['class'];
        @endphp
        <div class="page-container">
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row mb-3 align-items-center">
                        <!-- Header Section -->
                        <div class="col-3">
                            <div class="mb-2" style="font-size: 12px;">
                                <strong>Student Name:</strong> {{ $student->fullname }}<br>
                                <strong>Class/Level:</strong> {{ $class->name }}<br>
{{--                                <strong>Attendance:</strong> {{$student->attendances()->present($term->id)->count()}}/{{$term->days}}<br>--}}
                                <strong>Next Term Begins:</strong> {{ $term->next_term_begins->format('d F, Y') }}
                            </div>
                        </div>

                        <div class="col-6 text-center">
                            <div class="mb-2" style="position: relative;">
                                @if($school->preference->show_school_image_on_report)
                                    <img src="{{ $student->school->favicon()}}" alt="School Logo"
                                         style="position: absolute; left: -20px; top: -10px; width: 60px; height: 60px;">
                                @endif
                                <h4 class="mb-2" style="font-size: 16px; font-weight: bold;">TERMINAL REPORT</h4>
                                <h2 style="font-size: 16px; font-weight: bold;">{{$school->name}}</h2>
                                    <h4 style="font-size: 16px; font-weight: bold;">{{$school->district}}</h4>
                                @if($school->preference->show_student_image_on_report)
                                    <img src="{{ $student->image()}}" alt="School Logo"
                                         style="position: absolute; right: -20px; top: -10px; width: 60px; height: 60px;">
                                @endif
                            </div>
                            <div style="font-size: 11px;">
                                <div>{{$school->postal_address}}</div>
                                <div>Tel: {{$school->phone}} @if($school->phone2) / {{$school->phone2}} @endif</div>
                                <div class="mt-3"><strong>SESSION: </strong>{{$term->name}}</div>
                            </div>
                        </div>

                        <div class="col-3 text-end" style="font-size: 11px;">
                            <div class="mb-2">
                                @if($school->preference->show_overall_percentage)
                                    <strong>Student Overall Percentage:</strong> {{$reportCard['overall_percentage']}}
                                    <br>
                                    <strong>Student Overall
                                        Grade:</strong> {{getGradeAndRemark($reportCard['overall_percentage'], $school)['grade']}}
                                    <br>
                                @endif
                                @if(hasOtherClassesInGroup($reportCard['class']->id) && $school->preference->show_overall_position)
                                    <strong>Overall Position:</strong> {{$reportCard['school_overall_rank']}}
                                    /{{$reportCard['total_students_in_school']}}<br>
                                @endif
                                <strong>Position in Class:</strong> {{$reportCard['class_overall_rank']}}/{{$reportCard['total_students_in_class']}}<br>
{{--                                <strong>House:</strong> SIX--}}
                            </div>
                        </div>
                    </div>

                    <!-- Academic Results Table -->
                    <div class="px-2">
                        <table class="table table-bordered border-dark mb-3" style="font-size: 10px;">
                            <thead>
                            <tr style="background-color: #f8f9fa; height: 25px;">
                                <th style="width: 15%;">SUBJECT</th>

                                {{-- Dynamically generate score type headers --}}
                                @if (!empty($reportCard['subjects']))
                                    @php
                                        $allScoreTypes = collect($reportCard['subjects'])
                                            ->flatMap(fn($subject) => $subject['score_details'])
                                            ->pluck('scoreType')
                                            ->unique()
                                            ->toArray();
                                    @endphp
                                    @foreach ($allScoreTypes as $type)
                                        <th style="width: 8%;">{{ strtoupper($type['name']) }}
                                            <br>{{ $type['percentage'] }}%
                                        </th>
                                    @endforeach
                                @endif

                                <th style="width: 8%;">TOTAL SCORE<br>100%</th>
                                <th style="width: 8%;">GRADE</th>
                                @if($school->preference->show_class_average)
                                    <th style="width: 8%;">CLASS AVERAGE</th>
                                @endif
                                <th style="width: 12%;">SUBJECT CLASS POSITION</th>
                                @if(hasOtherClassesInGroup($class->id) && $school->preference->show_overall_position)
                                    <th style="width: 12%;">OVERALL POSITION</th>
                                @endif
                                <th style="width: 10%;">REMARKS</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($reportCard['subjects'] as $subject)
                                <tr style="height: 22px;">
                                    <td>{{ $subject['subject'] }}</td>

                                    {{-- Display scores for each score type --}}
                                    @foreach ($allScoreTypes as $type)
                                        @php
                                            $scoreDetail = collect($subject['score_details'])->firstWhere('scoreType.name', $type['name']);
                                        @endphp
                                        <td class="text-center">{{ $scoreDetail['score'] ?? '-' }}</td>
                                    @endforeach
                                    <td class="text-center">{{ $subject['subject_total'] }}</td>
                                    <td class="text-center">{{ getGradeAndRemark($subject['subject_total'], $school)['grade'] ?? '-' }}</td>
                                    @if($school->preference->show_class_average)
                                        <td class="text-center">{{ $subject['class_average'] ?? '-' }}</td>
                                    @endif
                                    <td class="text-center">{{ ordinal($subject['class_rank']) ?? '-' }}</td>
                                    @if(hasOtherClassesInGroup($class->id) && $school->preference->show_overall_position)
                                        <td class="text-center">{{ ordinal($subject['school_rank'] )?? '-' }}</td>
                                    @endif
                                    <td>{{ getGradeAndRemark($subject['subject_total'], $school)['remark'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-5 px-2">
                        <div class="col-8">
                            <!-- Reports Section -->
                            <div class="mb-2" style="font-size: 11px;">
                                <strong>Formmaster/Mistress' Report</strong><br>
                                <div class="ms-4">
                                    <div>Interest: <span class="signature-text">Positive</span></div>
                                    <div>Attitude: <span class="signature-text">Satisfactory</span></div>
                                </div>
                            </div>
                            <div class="mb-2" style="font-size: 11px;">
                                <strong>Housemaster/Mistress' Report</strong><br>
                                <div class="ms-4">CONDUCT: <span class="signature-text">Good</span></div>
                            </div>
                            <div style="font-size: 11px;">
                                <strong>Headmaster/Mistress' Report:</strong><br>
                                <div class="ms-4">REMARKS: <span class="signature-text">Keep it up</span></div>
                            </div>
                        </div>
                        <div class="grading">
                            <table>
                                <thead>
                                <tr style="height: 1.5%;">
                                    <th>Grade</th>
                                    <th>Score</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($school->gradeScores as $score)
                                    <tr style="height: 1.5%; width: 1%">
                                        <td>{{$score->grade}}</td>
                                        <td>{{$score->min_score}}-{{$score->max_score}}</td>
                                        <td>{{$score->remark}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="text-align:center;">No grading data available.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
