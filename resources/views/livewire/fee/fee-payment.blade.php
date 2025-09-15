@section('title', "Pay Bill")
<div class="row" x-cloak>
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="create">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        Pay Bills
                    </h5>
                    <div class="d-flex gap-2">
                        @if($printOut)
                            <a onclick="printReceipt('{{ route('finance.payment.receipt',  $printOut->uuid) }}')"
                               class="btn btn-primary" target="_blank">
                                <i class="ri-printer-line align-bottom me-2"></i>Print Receipt
                            </a>
                        @endif
                        <x-link label="Payment History" route="finance.payment.history" class="btn btn-info add-bt" icon="ri-grid-fill me-1 align-bottom" />
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4" >
                        <div class="mb-3 form-group col-md-6" x-data="studentDropdown()"   >
                            <label for="student-select" class="form-label">Student<span class="text-danger">*</span></label>
                            <div class="position-relative" >
                                <input type="text"
                                       :value="selectedName || search"
                                       @input="search = $event.target.value; selectedName = ''"
                                       placeholder="Search Student..."
                                       class="form-control"
                                       @focus="showDropdown = true"
                                       @click.away="showDropdown = false"
                                       :class="{ 'is-invalid': @js($errors->has('payment.student_id')) }"
                                >
                                <x-error key="payment.student_id"/>
                                <button type="button"
                                        class="btn-close position-absolute end-0 top-50 translate-middle-y me-2"
                                        x-show="selectedName"
                                        @click="selectedName = ''; search = ''; selected = ''; $wire.selectStudent('');"
                                        aria-label="Clear"></button>
                            </div>


                            <div class="dropdown-menu show position-absolute mt-1 shadow"
                                 x-show="showDropdown"
                                 style="width: auto; min-width: 47%; max-width: 300px; max-height: 250px; overflow-y: auto;">

                                <template x-if="filteredStudents().length > 0">
                                    <template x-for="student in filteredStudents()" :key="student.id">
                                        <div @click="selectStudent(student)" class="dropdown-item cursor-pointer">
                                            <span x-text="student.name"></span>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="filteredStudents().length === 0">
                                    <div class="dropdown-item">No Student found</div>
                                </template>
                            </div>
                            <select class="form-select d-none" wire:model.live="payment.student_id" id="student-select">
                                <option value="">Select</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ "$student->fullname - {$student->class->name}" }}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-input-text label="Payment Date" model="payment.created_at" type="date" placeholder="Payment Date" wrapper_class="col-md-6" wire:ignore />
                        <x-enum-select enum="App\Enum\PaymentChannel" wrapper_class="col-md-4" label="Channel" model="payment.channel" bind=".live" required />
                        <x-input-text label="Payer Name" model="payment.payer_name" wrapper_class="col-md-4" required />
                        <x-input-text label="Payer Phone" type="tel" model="payment.payer_phone" wrapper_class="col-md-4" required />
                        @if($payment['student_id'])
                            <div class="col-md-6 mx-auto">
                                <div class="card product">
                                    @foreach($bills as $index => $bill)
                                        <div class="card-body">
                                            <div class="row gy-3">
                                                <div class="col-sm">
                                                    <h5 class="fs-14 text-truncate">
                                                        <span class="text-body">{{ $bill->fee->name }}</span>
                                                    </h5>
                                                    <ul class="list-inline text-muted">
                                                        <li class="list-inline-item">{{ $bill->term->name }}:
                                                            <span class="fw-medium text-warning">{{cedi(). $bill->fee->amount }}</span>
                                                        </li>
                                                    </ul>
                                                    <input type="number" wire:model="payment.amount.{{ $index }}" step="0.001" placeholder="Enter amount" class="form-control" min="0">
                                                    <x-error key="payment.amount.{{ $index }}"/>
                                                </div>
                                                <div class="col-sm-auto">
                                                    <div class="text-lg-end">
                                                        <p class="text-danger mb-1">Balance:</p>
                                                        <h5 class="fs-14"><span class="text-danger">{{ cedi(). $bill->balance }}</span></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <!-- card body -->
                                    <div class="card-footer">
                                        <div class="row align-items-center gy-3">
                                            <div class="col-sm">
                                                <div class="d-flex flex-wrap my-n1">

                                                    <div>
                                                        <span href="#" class="d-block text-body p-1 px-2">
                                                            <i class="ri-money-cny-box-fill text-muted align-bottom me-1"></i>Outstanding</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-auto">
                                                <div class="d-flex align-items-center gap-2 text-muted">
                                                    <div>Total Debt :</div>
                                                    <h5 class="fs-14 mb-0"><span class="product-line-price">{{cedi()}} {{$totalDebt}}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end card footer -->
                                </div>
                            </div>
                        @endif

                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Pay Now" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        Alpine.data('studentDropdown', () => ({
            search: '',
            selectedName: '',
            showDropdown: false,
            selected: @entangle('payment.student_id'), // Make sure this is valid
            students: @json($students->map(fn($s) => [
        'id' => $s->id,
        'name' => "$s->fullname - {$s->class->name}"
    ])),
            init() {
                this.search = '';
            },
            filteredStudents() {
                return this.students
                    .filter(student =>
                        this.search.trim() === '' ||
                        student.name.toLowerCase().includes(this.search.toLowerCase())
                    )
                    .slice(0, 100);
            },
            selectStudent(student) {
                this.selectedName = student.name;
                this.search = student.name;
                this.selected = student.id;
                this.showDropdown = false;
                this.$wire.selectStudent(student.id);
            }
        }));

    </script>
@endsection
