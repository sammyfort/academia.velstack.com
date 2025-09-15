@section('title', "New Admission")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="admit">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Student Admission</h5>
                    <div class="d-flex gap-2">
                        <x-link label="Students" route="students.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text wrapper_class="col-md-4" label="Index Number" model="student.index_number" />
                        <x-input-text wrapper_class="col-md-4" label="First Name" model="student.first_name" required />
                        <x-input-text wrapper_class="col-md-4"  label="Last Name" model="student.last_name" required />
                        <x-input-text wrapper_class="col-md-4"  label="Other Name" model="student.middle_name"/>
                        <x-input-text wrapper_class="col-md-4"  label="Phone Number" model="student.phone"/>
                        <x-input-text wrapper_class="col-md-4"  label="Email address" type="email" model="student.email"/>
                        <x-enum-select wrapper_class="col-md-4"  enum="App\Enum\Region" label="Region"  model="student.region" required/>
                        <x-input-text wrapper_class="col-md-4"  label="Hometown/City"  model="student.city" required/>
                        <x-enum-select wrapper_class="col-md-4"  enum="App\Enum\Gender" label="Gender" model="student.gender" required/>
                        <x-input-text wrapper_class="col-md-4"  label="Date of Birth" type="date" model="student.dob"  required/>
                        <x-enum-select wrapper_class="col-md-4"  enum="App\Enum\Religion" label="Religion" model="student.religion" required />

                        <x-input-select wrapper_class="col-md-4"  label="Academic Year" :options="$calenders" key="id" value="name" model="student.admission_term"  required/>
                        <x-input-select wrapper_class="col-md-4"  label="Select Class" :options="$classes" key="id" value="name" model="student.class_id" required />
                        <x-input-text wrapper_class="col-md-4"  label="Medical Information (Special Needs)" type="textarea" model="student.bio" required />

                        <x-input-text wrapper_class="col-md-4"  label="Upload Student Photo (150px X 150px)" type="file" model="image" info>
                            @if($image)
                                <div class="upload-images mt-3">
                                    <img src="{{$image->temporaryUrl()}}" style="height: 100px; width: 100px" alt="Image">
                                    <a href="javascript:void(0);" class="btn-icon logo-hide-btn">
                                        <i class="feather-x-circle"></i>
                                    </a>
                                </div>
                            @endif
                        </x-input-text>

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Parent Details</h5>
                        </div>
                        @if($newParent)
                            <span class="text">Existing Parent ?
                                    <a href="javascript:void(0)" class="text-success" wire:click="setParentType(false)">Select Parent</a>
                            </span>

                            @foreach($parents as $index => $parent)
                                <div class="row align-items-center mb-3" wire:key="parent-{{ $index }}">
                                    <x-enum-select wrapper_class="col-md-4"  :choices="false"  enum="App\Enum\ParentType" label="Type"  model="parents.{{$index}}.type" />
                                    <x-input-text label="Parent Name"  model="parents.{{$index}}.name" wrapper_class="col-md-4" />
                                    <x-input-text label="Parent Email" type="email" model="parents.{{$index}}.email" wrapper_class="col-md-4" />
                                    <x-input-text label="Parent Phone" type="tel" model="parents.{{$index}}.phone" wrapper_class="col-md-4" />
                                    <x-input-text label="Parent Address" model="parents.{{$index}}.address" wrapper_class="col-md-4" />
                                    <x-input-text label="Parent ID No (Ghana Card)" model="parents.{{$index}}.identity_number" wrapper_class="col-md-4" />
                                    <x-input-text label="Occupation" model="parents.{{$index}}.occupation"  wrapper_class="col-md-4"/>
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-danger btn-sm bg-danger mt-2"
                                                wire:click.prevent="removeParent({{ $index }})">Remove</button>
                                    </div>
                                </div>
                            @endforeach
                            <div class="mt-2">
                                <button type="button" class="btn btn-success btn-xs" wire:click.prevent="addParent">+ Add Parent</button>
                            </div>
                        @else
                            <div class="mb-3 form-group col-md-4" x-data="studentDropdown()"   >
                                <label for="student-select" class="form-label">Parent<span class="text-danger">*</span></label>
                                <div class="position-relative" >
                                    <input type="text"
                                           :value="selectedName || search"
                                           @input="search = $event.target.value; selectedName = ''"
                                           placeholder="Search Parent..."
                                           class="form-control"
                                           @click.away="search = ''"
                                           :class="{ 'is-invalid': @js($errors->has('student.parent_id')) }"
                                    >
                                    <x-error key="student.parent_id"/>
                                    <button type="button"
                                            class="btn-close position-absolute end-0 top-50 translate-middle-y me-2"
                                            x-show="selectedName"
                                            @click="selectedName = ''; search = ''; selected = ''; $wire.selectParent('');"
                                            aria-label="Clear"></button>
                                </div>
                                <div class="dropdown-menu show w-100" x-show="search.length > 0">
                                    <template x-if="filteredParents().length > 0">
                                        <template x-for="parent in filteredParents()" :key="parent.id">
                                            <div @click="selectParent(parent)" class="dropdown-item cursor-pointer">
                                                <span x-text="parent.name"></span>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="filteredStudents().length === 0">
                                        <div class="dropdown-item">No Parent found</div>
                                    </template>
                                </div>
                                <select class="form-select d-none" wire:model.live="student.parent_id" id="student-select">
                                    <option value="">Select</option>
                                    @foreach($allSystemParents as $parent)
                                        <option value="{{ $parent->id }}">{{ "$parent->name - $parent->phone" }}</option>
                                    @endforeach
                                </select>


                                <div class="mt-2 form-check form-switch">
                                        <span class="text mt-2">Local Parents ?<input wire:model.live="localParents"
                                                                                      class="form-check-input"
                                                                                      type="checkbox"
                                                                                      role="switch"/></span>
                                </div>

                                <div class="mt-2">
                                    <span class="text mt-2">New Parent ?
                                    <a href="javascript:void(0)" class="text-success" wire:click="setParentType(true)">Add Parent</a></span>
                                </div>

                            </div>
                        @endif


                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Admit Student"/>
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
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('studentDropdown', () => ({
                search: '',
                selectedName: '',
                selected: @entangle('student.parent_id').defer,
                items: @json($allSystemParents->map(fn($s) => [
                    'id' => $s->id,
                    'name' => "$s->name - {$s->phone}"
                ])),
                init() {
                    this.search = '';
                },
                filteredParents() {
                    if (this.search.trim() === '') {
                        return [];
                    }
                    return this.items.filter(parent => parent.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                selectParent(parent) {
                    this.selectedName = parent.name;
                    this.search = parent.name;
                    this.selected = parent.id;
                    this.$wire.selectParent(parent.id);
                }
            }));
        });
    </script>
@endsection




