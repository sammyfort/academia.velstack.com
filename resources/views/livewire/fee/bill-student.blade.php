@section('title', "Bill Students")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="create">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Bill Students  <span class="text-danger"></span></h5>
                    <div class="d-flex gap-2">
                        <x-link label="Fees" route="finance.fee.index" class="btn btn-info add-bt" icon="ri-grid-fill me-1 align-bottom" />
                    </div>
                </div>
                <div class="card-body">
                    <div  class="row g-4">

                        <x-input-select wrapper_class="col-md-4" label="Term"
                                        :options="$terms" key="id" value="name"
                                        model="bill.term_id" bind=".live" />

                        <div class="mb-3 form-group col-md-4">
                            <label for="bills" class="form-label">Select Fee<span class="text-danger">*</span></label>
                            <select wire:model.live="bill.fee_id"  class="form-select">
                                <option  value="">Select...</option>
                                @foreach($fees as $fee)
                                    <option value="{{$fee->id }}">{{"$fee->name - $fee->amount"}}</option>
                                @endforeach
                            </select>
                            <x-error key="bill.fee_id"/>
                        </div>

                        <x-enum-select wrapper_class="col-md-4" enum="App\Enum\BillType" bind=".live"
                                       label="Who are you billing ?" model="bill.type" />


                        @if($bill['type'] == \App\Enum\BillType::STUDENT->value)

                            <div class="mb-3 form-group col-md-4" x-data="studentDropdown()"   >
                                <label for="student-select" class="form-label">Student<span class="text-danger">*</span></label>
                                <div class="position-relative" >
                                    <input type="text"
                                           :value="selectedName || search"
                                           @input="search = $event.target.value; selectedName = ''"
                                           placeholder="Search Student..."
                                           class="form-control"
                                           @click.away="search = ''"
                                           :class="{ 'is-invalid': @js($errors->has('bill.student_id')) }"
                                    >
                                    <x-error key="bill.student_id"/>
                                    <button type="button"
                                            class="btn-close position-absolute end-0 top-50 translate-middle-y me-2"
                                            x-show="selectedName"
                                            @click="selectedName = ''; search = ''; selected = ''; $wire.selectStudent('');"
                                            aria-label="Clear"></button>
                                </div>
                                <div class="dropdown-menu show w-100" x-show="search.length > 0">
                                    <template x-if="filteredStudents().length > 0">
                                        <template x-for="student in filteredStudents()" :key="student.id">
                                            <div @click="selectStudent(student)" class="dropdown-item cursor-pointer">
                                                <span x-text="student.name"></span>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="filteredStudents().length === 0">
                                        <div class="dropdown-item">No student found</div>
                                    </template>
                                </div>
                                <select class="form-select d-none" wire:model.live="bill.student_id" id="student-select">
                                    <option value="">Select</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}">{{ "$student->fullname - {$student->class->name}" }}</option>
                                    @endforeach
                                </select>

                            </div>
                        @endif
                        @if($bill['type'] == \App\Enum\BillType::CLASSROOM->value)
                            <x-input-select wrapper_class="col-md-4" label="Select Class"
                                            :options="$classes" key="id" value="name"
                                            model="bill.class_id" :choices="false"   />
                        @endif

                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Bill Now" />
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
        document.addEventListener('alpine:init', () => {
            Alpine.data('studentDropdown', () => ({
                search: '',
                selectedName: '',
                selected: @entangle('payment.student_id').defer,
                students: @json($students->map(fn($s) => [
                    'id' => $s->id,
                    'name' => "$s->fullname - {$s->class->name}"
                ])),
                init() {
                    this.search = '';
                },
                filteredStudents() {
                    if (this.search.trim() === '') {
                        return [];
                    }
                    return this.students.filter(student => student.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                selectStudent(student) {
                    this.selectedName = student.name;
                    this.search = student.name;
                    this.selected = student.id;
                    this.$wire.selectStudent(student.id);
                }
            }));
        });
    </script>
@endsection
