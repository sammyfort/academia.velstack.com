@section('title', "Edit Student")
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <form wire:submit.prevent="update">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Student Update</h5>
                    <div class="d-flex gap-2">
                        <x-link label="Students" route="students.index" class="btn btn-info add-bt " icon="ri-grid-fill me-1 align-bottom"/>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <x-input-text wrapper_class="col-md-4" label="Index Number" model="edit.index_number" />
                        <x-input-text wrapper_class="col-md-4" label="First Name" model="edit.first_name" />
                        <x-input-text wrapper_class="col-md-4"  label="Last Name" model="edit.last_name" />
                        <x-input-text wrapper_class="col-md-4"  label="Other Name" model="edit.middle_name"/>
                        <x-input-text wrapper_class="col-md-4"  label="Phone Number" model="edit.phone"/>
                        <x-input-text wrapper_class="col-md-4"  label="Email address" type="email" model="edit.email"/>
                        <x-enum-select wrapper_class="col-md-4" :choices="false"  enum="App\Enum\Religion" label="Region"  model="edit.region"/>
                        <x-input-text wrapper_class="col-md-4"  label="Hometown/City"  model="edit.city"/>
                        <x-enum-select wrapper_class="col-md-4"  :choices="false"  enum="App\Enum\Gender" label="Gender"  model="edit.gender" />
                        <x-input-text wrapper_class="col-md-4"  label="Date of Birth" type="date" model="edit.dob" />
                        <x-enum-select wrapper_class="col-md-4"  :choices="false"  enum="App\Enum\Religion" label="Religion" model="edit.religion" />
                        <x-input-select wrapper_class="col-md-4"  :choices="false" label="Select Class" :options="$classes"
                                        key="id" value="name" model="edit.class_id" />

                        <x-input-text wrapper_class="col-md-4"  label="Medical Information (Special Needs)" type="textarea" model="edit.bio" />
                        <x-input-text wrapper_class="col-md-4"  label="Upload Student Photo (150px X 150px)" type="file" model="image" info>
                            @if($image)
                                <div class="upload-images mt-3">
                                    <img src="{{$image->temporaryUrl()}}" style="height: 100px; width: 100px" alt="Image">
                                    <a href="javascript:void(0);" class="btn-icon logo-hide-btn">
                                        <i class="feather-x-circle"></i>
                                    </a>
                                </div>
                            @else
                                <div class="upload-images mt-3">
                                    <img src="{{$student->image()}}" style="height: 100px; width: 100px" alt="Image">
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
                            <span class="text">Old Parent ?
                                    <a href="javascript:void(0)" class="text-success" wire:click="setParentType(false)">Select Parent</a>
                            </span>

                            @foreach($parents as $index => $parent)
                                @php
                                    $disabled = null;
                                    $school_id = $parent['school_id'] ?? null;
                                    if (!$school_id) $disabled = false;
                                    elseif($school_id != school()->id) $disabled = true;
                                    elseif($school_id == school()->id) $disabled = false;

                                @endphp
                                <div class="row align-items-center mb-3" wire:key="parent-{{ $index }}">
                                    <x-enum-select wrapper_class="col-md-4"   enum="App\Enum\ParentType" label="Type"  model="parents.{{$index}}.type" :disabled="$disabled" />
                                    <x-input-text label="Parent Name"  model="parents.{{$index}}.name" wrapper_class="col-md-4" :disabled="$disabled" />
                                    <x-input-text label="Parent Email" type="email" model="parents.{{$index}}.email" wrapper_class="col-md-4" :disabled="$disabled" />
                                    <x-input-text label="Parent Phone" type="tel" model="parents.{{$index}}.phone" wrapper_class="col-md-4" :disabled="$disabled" />
                                    <x-input-text label="Parent Address" model="parents.{{$index}}.address" wrapper_class="col-md-4" :disabled="$disabled"/>
                                    <x-input-text label="Parent ID No (Ghana Card)" model="parents.{{$index}}.identity_number" wrapper_class="col-md-4" :disabled="$disabled" />
                                    <x-input-text label="Occupation" model="parents.{{$index}}.occupation"  wrapper_class="col-md-4" :disabled="$disabled"/>
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
                            <div class="mb-3 form-group col-md-4">
                                <label for="parents" class="form-label">
                                    Parent
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-control form-select {{ $errors->has('edit.parent_id') ? 'is-invalid' : '' }}"
                                        wire:model="edit.parent_id" id="parents">
                                    <option  value="">Select</option>
                                    @forelse($allSystemParents as $parent)
                                        <option value="{{$parent->id}}">{{ "$parent->name - $parent->phone"}}</option>
                                    @empty
                                        <option value="" class="text-danger">No Parent to select from</option>
                                    @endforelse
                                </select>
                                <x-error key="edit.parent_id"/>
                                <span class="text">New Parent ?
                                    <a href="javascript:void(0)" class="text-success"
                                       wire:click="setParentType(true)">Add Parent</a>
                                </span>
                            </div>
                        @endif

                        <div class="col-lg-12">
                            <div class="hstack justify-content-end gap-2">
                                <x-button label="Update"/>
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

@endsection




