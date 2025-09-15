@section("title", 'Staff')
<div x-init="
        staffActiveTab = localStorage.getItem('staffActiveTab') || 'all';
        $watch('staffActiveTab', value => localStorage.setItem('staffActiveTab', value));
    "
     x-cloak
          x-data="{ staffActiveTab: 'all' }">

    <div class="row"  >
        @php
            $date = \Illuminate\Support\Carbon::parse($this->date);
        @endphp
        <div class="col-lg-12">
            <div class="card" id="staffList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Staff List</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                @can('create.Staff')
                                    <x-link label="Create Staff" route="staff.create" class="btn btn-primary"
                                            icon="ri-add-line align-bottom me-1"/>
                                @endcan

                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <ul class="nav nav-tabs nav-tabs-custom nav-primary mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link All py-3" :class="{ 'active': staffActiveTab === 'all' }"
                               data-bs-toggle="tab" id="All"
                               @click.prevent="staffActiveTab = 'all'"
                               role="tab" aria-selected="true">
                                <i class="ri-store-2-fill me-1 align-bottom"></i> Staff
                            </a>
                        </li>

                        @can('staff.Attendance')
                            <li class="nav-item">
                                <a class="nav-link py-3 " :class="{ 'active': staffActiveTab === 'attendance' }"
                                   data-bs-toggle="tab" id="Attendance"
                                   @click.prevent="staffActiveTab = 'attendance'"
                                   role="tab" aria-selected="false">
                                    <i class="ri-checkbox-circle-line me-1 align-bottom"></i> Attendance
                                </a>
                            </li>
                        @endcan


                        @can('view.Payslip')
                            <li class="nav-item">
                                <a class="nav-link py-3 " :class="{ 'active': staffActiveTab === 'payroll' }"
                                   data-bs-toggle="tab" id="ranking"
                                   @click.prevent="staffActiveTab = 'payroll'"
                                   role="tab" aria-selected="false">
                                    <i class="ri-money-dollar-box-fill me-1 align-bottom"></i> Payroll
                                </a>
                            </li>
                        @endcan

                        @canany(['create.Allowance', 'edit.Allowance', 'edit.Allowance'])
                            <li class="nav-item">
                                <a class="nav-link py-3 " :class="{ 'active': staffActiveTab === 'allowance' }"
                                   data-bs-toggle="tab" id="allowance"
                                   @click.prevent="staffActiveTab = 'allowance'"
                                   role="tab" aria-selected="false">
                                    <i class="ri-creative-commons-by-line me-1 align-bottom"></i> Allowance And
                                    Deductions
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
              <div>
                  <div class="card-body border-bottom-dashed border-bottom">
                      <form>
                          <div class="row g-3">
                              <div class="col-xl-12">
                                  <div class="d-flex flex-wrap align-items-center gap-3">
                                      <!-- Search Box -->
                                      <div class="flex-shrink-0" style="width: 200px;">
                                          <div class="search-box">
                                              <input wire:model.live="search" type="text" class="form-control search"
                                                     placeholder="Search something...">
                                              <i class="ri-search-line search-icon"></i>
                                          </div>
                                      </div>

                                      <div>
                                          <button type="button" class="btn btn-primary"
                                                  wire:click.prevent="resetFilter()">
                                              <i class="ri-equalizer-fill me-2 align-bottom"></i>Filters
                                          </button>
                                      </div>

                                      <x-input-text
                                          wire:model.live="date" placeholder=" Date"
                                          type="date" id="date" wrapper_class="mt-3"/>


                                      <x-input-select model="term_id"
                                                      bind=".live" :options="$terms"
                                                      key="id" value="name"
                                                      placeholder="Select Term" wrapper_class="mt-3"/>


                                      <div x-show="staffActiveTab === 'payroll'">
                                          <button type="button" class="btn btn-success"
                                                  wire:click.prevent="markAllAs('{{\App\Enum\PayslipStatus::PAID->value}}')">
                                              <i class="ri-money-dollar-box-fill me-2 align-bottom"></i>Mark All As
                                              Paid
                                          </button>

                                          <button type="button" class="btn btn-danger"
                                                  wire:click.prevent="markAllAs('{{\App\Enum\PayslipStatus::PENDING->value}}')">
                                              <i class="ri-money-dollar-box-fill me-2 align-bottom"></i>Mark All As
                                              Pending
                                          </button>
                                      </div>

                                      <div  x-show="staffActiveTab === 'allowance'">
                                          <x-link label="Create Allowance" route="allowances.create"
                                                  class="btn btn-primary"
                                                  icon="ri-add-line align-bottom me-1"/>

                                          <button type="button" class="btn btn-primary"
                                                  wire:click.prevent="syncStaffToAllowanceAndDeductions()">
                                              <i class="ri-group-2-line me-2 align-bottom"></i>Sync Staff
                                          </button>

                                      </div>

                                  </div>

                              </div>
                          </div>
                          <!--end row-->
                      </form>
                  </div>
                  <div class="card-body">
                      <div  x-show="staffActiveTab === 'all'">

                          <div class="table-responsive table-card mb-1">
                              <div>
                                  <table class="table table-nowrap align-middle table-hover" id="home1">
                                      <thead class="text-muted table-light">
                                      <tr class="text-uppercase">

                                          <th class="sort" data-sort="id">#</th>
                                          <th class="sort" data-sort="customer_name">Full Name</th>
                                          <th class="sort" data-sort="product_name">Email</th>
                                          <th class="sort" data-sort="product_name">Phone Number</th>
                                          <th class="sort" data-sort="date">Residents</th>
                                          <th class="sort" data-sort="date">Created On</th>
                                          <th class="sort" data-sort="city">Action</th>
                                      </tr>
                                      </thead>
                                      <tbody class="list form-check-all">
                                      @forelse($staff as $worker)
                                          <tr  class="clickable-row" data-href="{{ route('staff.show', $worker->uuid) }}">
                                              <td class="customer_name">{{$loop->iteration}}</td>
                                              <td>
                                                  <x-td-image label="{{$worker->title}} {{$worker->fullname}}"
                                                              src="{{$worker->image()}}"
                                                              size="xs"
                                                              link="{{route('staff.show', $worker->uuid)}}"/>
                                              </td>
                                              <td class="date">{{$worker->email}} </td>
                                              <td class="date">{{$worker->phone}} </td>
                                              <td class="date">{{$worker->city}} </td>
                                              <td class="date">{{$worker->created_at->format('d F, Y')}}</td>

                                              <td class="non-clickable">
                                                  <x-table-actions :id="$worker->uuid"
                                                                   edit="staff.edit">
                                                      @can('delete.Staff')
                                                          <li class="list-inline-item" title="Remove">
                                                              <a class="text-danger d-inline-block remove-item-btn"
                                                                 @click="$dispatch('open-delete-modal', {model: 'Staff',  modelId:{{ $worker->id }},recordName: 'Staff' })">
                                                                  <i class="ri-delete-bin-5-fill fs-24"></i>
                                                              </a>
                                                          </li>
                                                      @endcan

                                                  </x-table-actions>
                                              </td>
                                          </tr>
                                      @empty
                                          <tr>
                                              <td colspan="12" class="text-center">
                                                  <x-no-result description="No Staff found"/>
                                              </td>
                                          </tr>
                                      @endforelse
                                      </tbody>
                                  </table>
                                  <x-paginate :collection="$staff"/>
                              </div>
                          </div>
                      </div>
                      <div x-show="staffActiveTab === 'attendance'">

                          @if(!$staffModel && collect($attendances)->isEmpty())
                              <div>
                                      @php
                                          $date = \Illuminate\Support\Carbon::parse($this->date);
                                      @endphp

                                      <div class="table-responsive table-card mb-1">

                                          <div >
                                              <table class="table table-nowrap align-middle" id="home1">
                                                  <thead class="text-muted table-light">
                                                  <tr class="text-uppercase">
                                                      <th class="sort" data-sort="id">#</th>
                                                      <th class="sort" data-sort="customer_name">Full Name</th>
                                                      <th class="sort" data-sort="date">Term Total ({{$term?->name}})</th>
                                                      <th class="sort" data-sort="date">Date @if($date)
                                                              - ({{$date->format('d M,y')}})
                                                          @endif</th>
                                                      <th class="sort" data-sort="city">Mark</th>
                                                  </tr>
                                                  </thead>
                                                  <tbody class="list form-check-all">
                                                  @if($date and $term)
                                                      @forelse($staff as $worker)
                                                          <tr  style="cursor: pointer;" wire:click="openTimeSheet({{ $worker->id }})">
                                                              <td class="customer_name">{{$loop->iteration}}</td>
                                                              <td>
                                                                  <x-td-image label="{{$worker->fullname}}"
                                                                              src="{{$worker->image()}}"
                                                                              size="xs"
                                                                              link="{{route('staff.show', $worker->uuid)}}"/>
                                                              </td>
                                                              <td class="customer_name">
                                                                  {{$worker->attendances->where('term_id',$term_id)->where('present', true)->count()}}
                                                              </td>
                                                              <td class="text-dark">
                                                                  @php
                                                                      $attendance = $worker->attendances->where('term_id', $term_id)->where('date', $date)->first();
                                                                  @endphp
                                                                  @if($attendance)
                                                                      <span title="Not Assigned"
                                                                            class="badge bg-{{$attendance->present ? 'success': 'danger'}}-subtle text-{{$attendance->present ? 'success': 'danger'}}">
                                                            {{ $attendance->present ? 'Present' : 'Absent'}}
                                                        </span>
                                                                  @else
                                                                      <span title="Assigned" class="text-danger">No Record</span>
                                                                  @endif
                                                              </td>
                                                              <td>
                                                                  <ul class="list-inline hstack gap-2 mb-0">
                                                                      <li class="list-inline-item" title="Enter Results">
                                                                          <button type="button" class="btn btn-success btn-sm"
                                                                                  onclick="event.stopPropagation()"
                                                                                  wire:click.prevent="recordAttendance({{ $worker->id }}, 1)">
                                                                              Present
                                                                          </button>
                                                                      </li>
                                                                      <li class="list-inline-item" title="Assign ScoreType">
                                                                          <button type="button" class="btn btn-danger btn-sm"
                                                                                  onclick="event.stopPropagation()"
                                                                                  wire:click.prevent="recordAttendance({{ $worker->id }}, 0)">
                                                                              Absent
                                                                          </button>

                                                                      </li>
                                                                  </ul>
                                                              </td>

                                                          </tr>
                                                      @empty
                                                          <tr>
                                                              <td colspan="12" class="text-center">
                                                                  <x-no-result description="No Staff found"/>
                                                              </td>
                                                          </tr>
                                                      @endforelse
                                                  @else
                                                      <tr>
                                                          <td colspan="12" class="text-center">
                                                              <x-no-result title="No Attendance"
                                                                           description="Please select date and term"/>
                                                          </td>

                                                      </tr>

                                                  @endif

                                                  </tbody>
                                              </table>
                                                                      <x-paginate :collection="$staff"/>
                                          </div>
                                      </div>
                              </div>
                          @else
                              <div class="card-body">
                                  <div class=" mb-2">
                                      <button type="button" class="btn btn-danger text-center float-end"
                                              wire:click.prevent="closeTimeSheet()">
                                          <i class="ri-close me-2 align-bottom"></i>Close Attendance Sheet
                                      </button>
                                  </div>
                                  <h3 class="text-center">Attendance Report for {{$staffModel->fullname}} ({{$term->name}}) </h3>

                                  <table class="table table-bordered mt-4">
                                      <thead class="table-dark">
                                      <tr>
                                          <th>Date</th>
                                          <th>Attendance</th>
                                          <th>Recorded By</th>
                                          <th>Recorded Time</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                      @forelse($attendances as $attendance)
                                          <tr>
                                              <td>{{ \Carbon\Carbon::parse($attendance->date)->format('D M d, Y') }}</td>
                                              <td class="text-center">
                                                  {!! $attendance->present ? '<span class="text-success">✔️</span>' : '<span class="text-danger">❌</span>' !!}
                                              </td>
                                              <td class="text-center">{{ $attendance->createdBy->fullname }}</td>

                                              <td class="text-center">{{$attendance->created_at->format('d M, Y')}}
                                                  <small class="text-muted">{{$attendance->created_at->format('h:i A')}}</small>
                                              </td>
                                          </tr>
                                      @empty
                                          <tr>
                                              <td colspan="12">
                                                  <x-no-result title="No Attendance" description="Not Attendance for the selected term"/>
                                              </td>
                                          </tr>
                                      @endforelse
                                      </tbody>
                                  </table>
                              </div>
                          @endif
                      </div>

                      <div x-show="staffActiveTab === 'payroll'">
                          <div class="table-responsive table-card mb-1">

                              <div>

                                  <table class="table table-nowrap align-middle" id="home1">
                                      <thead class="text-muted table-light">
                                      <tr class="text-uppercase">

                                          <th class="sort" data-sort="id">#</th>
                                          <th class="sort" data-sort="customer_name">Full Name</th>
                                          <th class="sort" data-sort="product_name">Phone Number</th>
                                          <th class="sort" data-sort="product_name">Month</th>
                                          <th class="sort" data-sort="product_name">Basic Salary</th>
                                          <th class="sort" data-sort="date">Status</th>
                                          <th class="sort" data-sort="date">Allowances</th>
                                          <th class="sort" data-sort="date">Deductions</th>
                                          <th class="sort" data-sort="city">Mark As</th>
                                          <th class="sort" data-sort="city">Action</th>
                                      </tr>
                                      </thead>
                                      <tbody class="list form-check-all">
                                      @forelse($staff as $worker)
                                          @php
                                              $payslip = $worker->payslips->where('date',$date->format('Y-m'))->first() ?? null;
                                          @endphp
                                          <tr>
                                              <td class="customer_name">{{$loop->iteration}}</td>
                                              <td>
                                                  <x-td-image label="{{$worker->fullname}}" src="{{$worker->image()}}"
                                                              size="xs" link="{{route('staff.show', $worker->uuid)}}"/>
                                              </td>
                                              <td class="date">{{$worker->phone}} </td>
                                              <td>{{ $date->format('M Y') }}</td>
                                              <td class="text-success">+{{$worker->basic_salary}}</td>
                                              <td class="date">{{$payslip?->status}}</td>
                                              <td>
                                                  @forelse($worker->allowancesAndDeductions->where('type', \App\Enum\AllowanceType::ALLOWANCE->value) as $alawa)
                                                      <span title="Assigned" class="badge bg-success-subtle
                                                    text-success d-inline-flex align-items-center">{{ $alawa->name }}
                                                        <button type="button" class="btn-close ms-2"
                                                                aria-label="Close"
                                                                wire:click="removeAllowance({{$worker->id}}, {{$alawa->id}})">
                                                        </button>
                                                    </span>
                                                  @empty
                                                      <span class="text-danger">No Allowance</span>
                                                  @endforelse
                                              </td>
                                              <td>
                                                  @forelse($worker->allowancesAndDeductions->where('type', \App\Enum\AllowanceType::DEDUCTION->value) as $deduction)
                                                      <span title="Assigned" class="badge bg-danger-subtle
                                                    text-danger d-inline-flex align-items-center">{{ $deduction->name }}
                                                        <button type="button" class="btn-close ms-2"
                                                                aria-label="Close"
                                                                wire:click="removeDeduction({{$worker->id}}, {{$deduction->id}})">
                                                        </button>
                                                    </span>
                                                  @empty
                                                      <span class="text-danger">No deduction</span>
                                                  @endforelse
                                              </td>

                                              <td>
                                                  <ul class="list-inline hstack gap-2 mb-0">

                                                      <li class="list-inline-item" title="Assign ScoreType">
                                                          <button type="button" class="btn btn-success btn-sm me-2"
                                                                  wire:click.prevent="setPayslipStatus({{ $payslip}}, '{{\App\Enum\PayslipStatus::PAID->value}}')">
                                                              Mark Paid
                                                          </button>
                                                          <button type="button" class="btn btn-danger btn-sm"
                                                                  wire:click.prevent="setPayslipStatus({{ $payslip }}, '{{\App\Enum\PayslipStatus::PENDING->value}}')">
                                                              Mark Unpaid
                                                          </button>
                                                      </li>
                                                  </ul>
                                              </td>
                                              <td>
                                                  <ul class="list-inline hstack gap-2 mb-0">
                                                      <li class="list-inline-item" title="Assign ScoreType">
                                                          <a href="{{route('staff.payslip', ['staff' => $worker->uuid, 'date' => $date])}}"
                                                             target="_blank" class="btn btn-warning btn-sm"
                                                             title="Generate report">
                                                              PAYSLIP
                                                          </a>
                                                      </li>

                                                  </ul>
                                              </td>
                                          </tr>
                                      @empty
                                          <tr>
                                              <td colspan="12" class="text-center">
                                                  <x-no-result description="No Staff found"/>
                                              </td>
                                          </tr>
                                      @endforelse
                                      </tbody>
                                  </table>
                                                      <x-paginate :collection="$staff"/>
                              </div>

                          </div>
                      </div>

                      <div x-show="staffActiveTab === 'allowance'">
                          <div class="table-responsive table-card mb-1">

                              <div  >
                                  <table class="table table-nowrap align-middle" id="home1">
                                      <thead class="text-muted table-light">
                                      <tr class="text-uppercase">

                                          <th class="sort" data-sort="id">#</th>
                                          <th class="sort" data-sort="customer_name">Allowance Name</th>
                                          <th class="sort" data-sort="product_name">Type</th>
                                          <th class="sort" data-sort="product_name">Amount</th>
                                          <th class="sort" data-sort="product_name">Staff On</th>
                                          <th class="sort" data-sort="date">Created By</th>
                                          <th class="sort" data-sort="date">Created On</th>
                                          <th class="sort" data-sort="city">Action</th>
                                      </tr>
                                      </thead>
                                      <tbody class="list form-check-all">

                                      @forelse($school->allowancesAndDeductions as $allowance)

                                          <tr>
                                              @php
                                                  $type = $allowance->type == \App\Enum\AllowanceType::ALLOWANCE->value
                                              @endphp
                                              <td class="customer_name">{{$loop->iteration}}</td>
                                              <td class="date">{{$allowance->name}} </td>
                                              <td class="date">{{$allowance->get_type}} </td>
                                              <td class="text-{{$type === true ? 'success' : 'danger'}}">
                                                  {{$type === true ? '+' : '-'}} {{$allowance->amount}} </td>
                                              <td class="date">{{$allowance->staff_count}} </td>
                                              <td class="date">{{$allowance->createdBy->fullname}} </td>
                                              <td class="date">{{$allowance->created_at->format('d F, Y')}}</td>
                                              <td>
                                                  <x-table-actions :id="$allowance->uuid" edit="allowances.edit">
                                                      <li class="list-inline-item" title="Remove">
                                                          <a class="text-danger d-inline-block remove-item-btn"
                                                             @click="$dispatch('open-delete-modal', {model: 'AllowanceAndDeduction',
                                                            modelId:{{ $allowance->id }},recordName: '{{$allowance->get_type}}'})">
                                                              <i class="ri-delete-bin-5-fill fs-24"></i>
                                                          </a>
                                                      </li>
                                                  </x-table-actions>
                                              </td>
                                          </tr>
                                      @empty
                                          <tr>
                                              <td colspan="12" class="text-center">
                                                  <x-no-result description="No Allowance found"/>
                                              </td>
                                          </tr>
                                      @endforelse
                                      </tbody>
                                  </table>

                              </div>


                          </div>
                      </div>
                  </div>
              </div>
            </div>
            <livewire:modal.delete-modal/>
        </div>
        <!--end col-->

    </div>

    @section('script')
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
</div>


