@section('title', 'Student Details')
@section('css')
    <link href="{{ URL::asset('build/libs/gridjs/theme/mermaid.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

@endsection
<div class="row" x-init="
        studentActiveTab = localStorage.getItem('studentActiveTab') || 'records';
        $watch('studentActiveTab', value => localStorage.setItem('studentActiveTab', value));
    "
     x-cloak
     x-data="{ studentActiveTab: 'records' }">
    <div class="col-xxl-3">
        <div class="card">
            <div class="card-body p-4">
                <div>
                    <div class="flex-shrink-0 avatar-md mx-auto">
                        <div class="avatar-title bg-light rounded">
                            <img src="{{$student->image()}}" alt="" height="50"/>
                        </div>
                    </div>
                    <div class="mt-4 text-center mb-3">
                        <h5 class="mb-1">{{$student->fullname}}</h5>
                        <p class="text-muted">{{$student->school->name}}</p>
                        <p class="text-muted">{{$student->class->name}}</p>
                        @if($show_parents)
                            <button type="button" class="btn btn-sm btn-info"
                                    wire:click="setDisplay(false)">
                                <i class="fas fa-trash"></i> Show Student
                            </button>
                        @else
                            <button type="button" class="btn btn-sm btn-success"
                                    wire:click.prevent="setDisplay(true)">
                                <i class="fas fa-trash"></i> Show Parents
                            </button>
                        @endif

                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 table-borderless">
                            <tbody>
                            @if(!$show_parents)
                                <tr>
                                    <th><span class="fw-medium">Index Number</span></th>
                                    <td>{{$student->index_number}}</td>
                                </tr>

                                <tr>
                                    <th><span class="fw-medium">Date of Birth</span></th>
                                    <td>{{$student->dob->format('D, d F, Y')}}</td>
                                </tr>

                                <tr>
                                    <th><span class="fw-medium">Religion</span></th>
                                    <td>{{$student->religion}}</td>
                                </tr>

                                <tr>
                                    <th><span class="fw-medium">Region</span></th>
                                    <td>{{$student->region}}</td>
                                </tr>
                                <tr>
                                    <th><span class="fw-medium">City</span></th>
                                    <td>{{$student->city}}</td>
                                </tr>
                            @else
                                <div class="mt-4 text-center mb-3">
                                    <h5 class="mb-1">Parent(s)</h5>
                                </div>
                                @foreach($student->parents as $parent)
                                    <tr>
                                        <th colspan="2" class="text-center">
                                            <h5 class="fw-bold text-primary">
                                                {{ ucfirst($parent->type) }}
                                            </h5>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th><span class="fw-medium">Parent Name</span></th>
                                        <td>{{$parent->name}}</td>
                                    </tr>
                                    <tr>
                                        <th><span class="fw-medium">Parent Phone</span></th>
                                        <td>{{$parent->phone}}</td>
                                    </tr>
                                    <tr>
                                        <th><span class="fw-medium">Parent Email</span></th>
                                        <td>
                                            <a href="mailto:{{$parent->email}}" class="link-primary">{{$parent->email}}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr class="my-3">
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!--end card-body-->
            <div class="card-body border-top border-top-dashed p-4">
                <div>
                    <h6 class="text-muted text-uppercase fw-semibold mb-4">Student Subjects</h6>
                    @if($student->subjects->count() >0)
                        <div>
                            @php
                                $subjectPercentages = $student->getSubjectPercentages(); // Get all subjects with percentages
                                $totalSubjects = count($subjectPercentages);
                                $averagePercentage = $totalSubjects > 0
                                    ? round(collect($subjectPercentages)->pluck('percentage')->avg(), 1)
                                    : 0;

                                // Calculate stars based on average percentage
                                $fullStars = floor($averagePercentage / 20); // Each 20% equals one full star
                                $halfStar = ($averagePercentage % 20 >= 10) ? 1 : 0; // Add a half star if the remainder is 10 or more
                                $emptyStars = 5 - ($fullStars + $halfStar); // Remaining stars are empty
                            @endphp

                            <div>
                                <div class="bg-light px-3 py-2 rounded-2 mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <div class="fs-16 align-middle text-warning">
                                                @for ($i = 0; $i < $fullStars; $i++)
                                                    <i class="ri-star-fill"></i>
                                                @endfor
                                                @if ($halfStar)
                                                    <i class="ri-star-half-fill"></i>
                                                @endif
                                                @for ($i = 0; $i < $emptyStars; $i++)
                                                    <i class="ri-star-line"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <h6 class="mb-0">
                                                {{ $averagePercentage }} out of 100%
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="">
                                        Performance <span
                                            class="fw-medium text-muted">/{{ $totalSubjects > 0 ? '100%' : 'No Subjects' }}</span>
                                    </div>
                                </div>
                            </div>


                            <div class="mt-3">
                                @php
                                    $subjectPercentages = $student->getSubjectPercentages();
                                @endphp
                                @foreach($subjectPercentages as $subject)
                                    <div class="row align-items-center g-2">
                                        <div class="col-auto">
                                            <div class="p-1">
                                                <h6 class="mb-0">{{$subject['subject']}}</h6>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="p-1">
                                                <div class="progress animated-progress progress-sm">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                         style="width: {{$subject['percentage']}}%"
                                                         aria-valuenow="{{$subject['percentage']}}"
                                                         aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="p-1">
                                                <h6 class="mb-0 text-muted">{{$subject['percentage']}}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end row -->
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div>
                            <span>No Subject found fo student</span>
                        </div>
                    @endif

                </div>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->

    <div class="col-xxl-9">
        <div wire:ignore class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Student Profile</h4>
                <div>
                    @auth('staff')
                        @can('edit.Student')
                            <x-link icon="ri-pencil-fill" route="students.edit" :param="$student->uuid"
                                    class="btn btn-soft-secondary btn-sm" label="Edit"/>
                        @endcan
                    @endauth

                </div>
            </div><!-- end card header -->

            <div class="card-header p-0 border-0 bg-light-subtle">
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value"
                                                   data-target="{{$student->subjects->count()}}">0</span>
                            </h5>
                            <p class="text-muted mb-0">Subjects</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">
                                @php
                                    $totalOwing = $student->outstandingBills->sum(function ($bill) {
                                         $paidAmount = $bill->payments()->sum('amount_paid');
                                         return $bill->amount - $paidAmount;
                                     });
                                @endphp
                                <span> {{ cedi(). $totalOwing}}</span>
                            </h5>
                            <p class="text-muted mb-0">Outstanding Debt</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">

                        @php
                            $classPerformance = $student->classPerformanceRate()  ;
                               $cbg = match (true) {
                                       $classPerformance <= 30 => 'danger',
                                       $classPerformance <= 59 => 'warning',
                                       $classPerformance <= 69 => 'info',
                                       $classPerformance >= 70 => 'success',
                             };
                        @endphp
                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                            <h5 class="mb-1 text-{{$cbg}}">
                                <span class="counter-value" data-target="{{$classPerformance}}"></span>%
                            </h5>
                            <p class="text-muted mb-0">Class Performance</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-3">
                        <div class="p-3 border border-dashed border-start-0 border-end-0">
                            @php
                                $overallPerformance = $student->overallPerformanceRate();
                                $obg =   match (true){
                                       $overallPerformance <= 30 => 'danger',
                                       $overallPerformance <= 49 => 'warning',
                                       $overallPerformance <= 59 => 'primary',
                                       $overallPerformance <= 69 => 'info',
                                       $overallPerformance >= 70 => 'success',
                                     }
                            @endphp
                            <h5 class="mb-1 text-{{$obg}}">
                                <span class="counter-value" data-target="{{$overallPerformance}}"></span>%
                            </h5>
                            <p class="text-muted mb-0">Overall Performance</p>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div><!-- end card header -->
        </div><!-- end card -->


            <div class="card" >
                <div class="card-header border-0 align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Student Performance By Term Chart</h4>
                </div><!-- end card header -->

                <div style="height: 32rem;" >
                    <livewire:livewire-line-chart
                        key="{{ $chart->reactiveKey()}}"
                        :line-chart-model="$chart"
                    />
                </div>
            </div><!-- end card -->

        <div class="card mb-3">
            <div class="card-body">
                <div>
                    <ul class="nav nav-tabs nav-tabs-custom nav-primary mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link All py-3" :class="{ 'active': studentActiveTab === 'records' }"
                               data-bs-toggle="tab" id="All"
                               @click.prevent="studentActiveTab = 'records'"
                               role="tab" aria-selected="true">
                                <i class="ri-file-chart-fill me-1 align-bottom"></i> Records
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link All py-3" :class="{ 'active': studentActiveTab === 'bills' }"
                               data-bs-toggle="tab" id="All"
                               @click.prevent="studentActiveTab = 'bills'"
                               role="tab" aria-selected="true">
                                <i class="ri-store-2-fill me-1 align-bottom"></i> Bills
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link py-3 " :class="{ 'active': studentActiveTab === 'debts' }"
                               data-bs-toggle="tab" id="Delivered"
                               @click.prevent="studentActiveTab = 'debts'"
                               role="tab" aria-selected="false">
                                <i class="ri-wallet-2-line me-1 align-bottom"></i>Debts
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link py-3 " :class="{ 'active': studentActiveTab === 'payments' }"
                               data-bs-toggle="tab" id="ranking"
                               @click.prevent="studentActiveTab = 'payments'"
                               role="tab" aria-selected="false">
                                <i class="ri-wallet-3-line me-1 align-bottom"></i> Payments
                                {{--                                <span class="badge bg-danger align-middle ms-1">2</span>--}}
                            </a>
                        </li>

                        <li class="nav-item">
                            <div class="row g-4 mb-3">
                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <input wire:model.live="search" type="text" class="form-control"
                                                   placeholder="Search...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>


                    <div class="table-responsive table-card mb-1">

                        <div x-show="studentActiveTab === 'records'">
                            <table class="table table-nowrap align-middle" id="home1">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">

                                    <th>#</th>
                                    <th>Academic Year</th>
                                    <th>School</th>
                                    <th>Class/Level</th>
                                    <th>REPORT</th>
                                    <th>Term Ended</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($student->academicRecords as $record)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>

                                        <td>{{$record->term->name}}</td>
                                        <td>{{$record->school->name}}</td>
                                        <td>{{$record->class->name}}</td>
                                        <td>
                                            <a href="{{route('terminal.report', ['uuid' => $student->uuid, 'term_id' => $record->term, 'class_id' => $record->class->id])}}"
                                               target="_blank" class="btn btn-warning btn-sm"
                                               title="Generate report">
                                                GENERATE
                                            </a>
                                        </td>
                                        <td>{{$record->term->end_date->format('d M, Y')}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <x-no-result description="No Academic record found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <x-paginate :collection="$bills"/>
                        </div>
                        <div x-show="studentActiveTab === 'bills'">
                            <table class="table table-nowrap align-middle" id="home1">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">

                                    <th>#</th>
                                    <th>Fee Type</th>
                                    <th>Academic Term</th>
                                    <th>Bill amount</th>
                                    <th>Total Paid</th>
                                    <th>Status</th>
                                    <th>Billed Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($bills as $bill)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$bill->fee->name}}</td>
                                        <td>{{$bill->term->name}}</td>
                                        <td class="text-primary">GHS {{$bill->amount}}</td>
                                        <td class="text-primary">GHS {{$bill->totalPaid()}}</td>
                                        <td>
                                            @if($bill->totalPaid() >= $bill->amount)
                                                <span class="badge bg-success">PAID</span>
                                            @else
                                                <span class="badge bg-danger">OWING</span>
                                            @endif
                                        </td>
                                        <td>{{$bill->created_at->format('d M, Y - H:i')}}</td>
                                        <td>
                                            <x-table-actions  >
                                                @auth('staff')
                                                    <li class="list-inline-item" title="Remove">
                                                        <a  target="_blank" class="btn btn-danger btn-sm"
                                                            @click="$dispatch('open-delete-modal', {model: 'Bill',
                                                             modelId:{{ $bill->id }},recordName: 'Student Bill',
                                                             title: 'Are you sure you want to remove this bill from the student',
                                                             description: 'Removing this bill will also delete all bill payments',
                                                             buttonText: 'Yes, Remove'
                                                             })"
                                                        >
                                                            <i class="ri-close-fill fs-16"></i> Remove Bill</a>
                                                    </li>
                                                @endauth

                                            </x-table-actions>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <x-no-result description="No Bill found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <x-paginate :collection="$bills"/>
                        </div>
                        <div x-show="studentActiveTab === 'debts'">
                            <table class="table table-nowrap align-middle" id="home1">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th>#</th>
                                    <th>Bill Type</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Billed on</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($debts as $bill)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$bill->fee->name}}</td>
                                        <td class="text-primary">GHS {{$bill->amount}}</td>
                                        <td class="text-primary">GHS {{$bill->totalPaid()}}</td>
                                        <td class="text-primary">GHS {{$bill->balance}}</td>
                                        <td>
                                            <span class="badge bg-danger">OWING</span>
                                        </td>
                                        <td>{{$bill->created_at->format('d M, Y')}}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <x-no-result description="No debt found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <x-paginate :collection="$debts"/>
                        </div>
                             <div x-show="studentActiveTab === 'payments'">
                            <table class="table table-nowrap align-middle" id="home1">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th>#</th>
                                    <th>Channel</th>
                                    <th>Paid amount</th>
                                    <th>Paid on</th>
                                    <th>Receipt</th>

                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($payments as $payment)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$payment->channel}}</td>
                                        <td class="text-primary">GHS {{$payment->amount}}</td>

                                        <td>{{$payment->created_at->format('d M, Y - H:i')}}</td>
                                        <td>
                                            @auth('staff')
                                                @can('edit.Student')
                                                    <a href="{{route('finance.payment.receipt', $payment->uuid)}}" target="_blank"
                                                       class="btn btn-primary">
                                                        <i class="ri-printer-fill fs-16"></i> Receipt</a>
                                                @endcan
                                            @endauth


                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <x-no-result description="No payment found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <x-paginate :collection="$payments"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <livewire:modal.delete-modal />
    </div>
    <!--end col-->
</div>
@section('script')
    <script src="{{ URL::asset('build/libs/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/wnumb/wNumb.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/gridjs/gridjs.umd.js') }}"></script>
    <script src="https://unpkg.com/gridjs/plugins/selection/dist/selection.umd.js"></script>


    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/seller-details.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
