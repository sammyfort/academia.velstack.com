@extends('pdfs.layout.app')

@section('title', 'Student Terminal Report')

@section('content')

<div class="page-container">
    @php
    $student = $data['student'];
    $school = $data['student']->school;
    $class = $data['class'];
    @endphp
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <!-- Header Section -->

                <div class="col-3">
                    <div class="mb-2" style="font-size: 11px; font-family: monospace;">
                        {{-- <strong>Student Name:</strong> {{ $student->fullname }}<br> --}}
                        <strong>Class/Level:</strong> {{ $data['class']->name }}<br>
                        <strong>Position in Class:</strong> {{$data['class_overall_rank']}}/{{$data['total_students_in_class']}}<br>
                        {{-- <strong>Attendance:</strong> {{$student->attendances()->present($term->id)->count()}}/{{$term->days}}<br>--}}
                        <strong>Next Term Begins:</strong> {{ $term->next_term_begins->format('d F, Y') }}

                    </div>
                </div>

                <div class="col-6 text-center">
                    <div class="mb-1" style="position: relative;">
                        @if($school->preference->show_school_image_on_report)
                        <img src="{{ $student->school->favicon()}}" alt="School Logo" style="position: absolute; left: -20px; top: -10px; width: 60px; height: 60px;">
                        @endif
                        <h4 class="" style="font-size: 16px; font-weight: bold; font-family: monospace;">GHANA EDUCATION SERVICE</h4>
                        <h2 style="font-size: 16px; font-weight: semi-bold; font-family: monospace;">{{$school->name}}</h2>
                        <h4 style="font-size: 16px; font-weight: semi-bold; font-family: monospace;">{{$school->district}}</h4>
                        @if($school->preference->show_student_image_on_report)
                        <img src="{{ $student->image()}}" alt="School Logo" style="position: absolute; right: -20px; top: -10px; width: 60px; height: 60px;">
                        @endif
                    </div>
                    <div style="font-size: 11px; font-family: monospace;">
                        <div>{{$school->postal_address}}</div>
                        <div>Tel: {{$school->phone}} @if($school->phone2) / {{$school->phone2}} @endif</div>

                        <div style="background: #343a40; color: white; padding: 6px 20px; font-size: 11px; font-weight: bold; margin: 8px 0; text-transform: uppercase; display: inline-block;">
                            TERMINAL REPORT
                        </div>

                        <div style="margin-top: 12px;">
                    <div style="font-size: 11px; color: #495057; margin-bottom: 8px;"><strong>SESSION:</strong> {{$term->name}}</div>
                    <div style="margin-top: 8px;">
                        <span style="font-weight: 600; font-size: 12px; color: #495057;">STUDENT NAME:</span>
                        <span style="font-family: 'Courier New', Monaco, Menlo, monospace; font-size: 18px; font-weight: bold; color: #212529;">{{ $student->fullname }}</span>
                    </div>
                </div>
                        
                    </div>
                </div>

                <div class="col-3 text-end" style="font-size: 11px; font-family: monospace;">
                    <div class="mb-2">
                        @if($school->preference->show_overall_percentage)
                        <strong>Student Overall Percentage:</strong> {{$data['overall_percentage']}}<br>
                        <strong>Student Overall
                            Grade:</strong> {{getGradeAndRemark($data['overall_percentage'], $school)['grade']}}
                        <br>
                        @endif
                        @if(hasOtherClassesInGroup($data['class']->id) && $school->preference->show_overall_position)
                        <strong>Overall Position:</strong> {{$data['school_overall_rank']}}
                        /{{$data['total_students_in_school']}}<br>
                        @endif
                        {{-- <strong>Position in Class:</strong> {{$data['class_overall_rank']}}/{{$data['total_students_in_class']}}<br> --}}
                        {{-- <strong>House:</strong> SIX--}}
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
                            @if (!empty($data['subjects']))
                            @php
                            // Get all unique score types across subjects
                            $allScoreTypes = collect($data['subjects'])
                            ->flatMap(fn($subject) => $subject['score_details'])
                            ->pluck('scoreType')
                            ->unique()
                            ->toArray();
                            @endphp
                            @foreach ($allScoreTypes as $type)

                            <th style="width: 8%;">{{ strtoupper($type['name']) }}<br>{{ $type['percentage'] }}%
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
                        @foreach ($data['subjects'] as $subject)
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
                        <strong>Formmaster/Mistress' Report:</strong><br>
                        <div class="ms-4">
                            @if($data['remark']->interest)
                            <div>Interest: <span class="signature-text">{{$data['remark']->interest}}</span>
                            </div>

                            @endif
                            @if($data['remark']->attitude)
                            <div>Attitude: <span class="signature-text">{{$data['remark']->attitude}}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2" style="font-size: 11px;">
                        <strong>Headmaster/Mistress' Report:</strong><br>
                        <div class="ms-4">
                            @if($data['remark']->conduct)
                            <div>Conduct: <span class="signature-text">{{$data['remark']->conduct}}</span></div>
                            @endif
                            @if($data['remark']->remark)
                            <div>Remark: <span class="signature-text">{{$data['remark']->remark}}</span></div>

                            @endif
                        </div>
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
                            <!-- Fallback message for empty data -->
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

@endsection
<script>
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 100);
    };
    window.onafterprint = function() {
        // window.location.href = '/previous-page';
    };

</script>
