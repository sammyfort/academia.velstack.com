@section("title")
    {{"List Classes"}}
@endsection

<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between gy-3">
                        <div class="col-lg-3">
                            <div class="search-box">
                                <input wire:model.live="search" type="text" class="form-control search"
                                       placeholder="Search for class...">
                                <i class="ri-search-line search-icon"></i>
                            </div>
                        </div>
                        <div class="col-lg-auto">
                            <div class="d-md-flex text-nowrap gap-2">
                                @can('create.Classroom')
                                    <x-link label="Create Class" route="classes.create" class="btn btn-info add-btn"
                                            icon="ri-add-fill me-1 align-bottom"/>
                                @endcan


                                <button wire:click.prevent="resetFilter" class="btn btn-danger"><i
                                        class="ri-filter-2-line me-1 align-bottom"></i>
                                    Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->


    <div class="row row-cols-xxl-5 row-cols-lg-3 row-cols-md-2 row-cols-1">
        @php
            $hasPermittedClass = false;
        @endphp

        @forelse($classes as $class)
            @php

            $staff = staff();
               $permitted = \Illuminate\Support\Facades\Cache::remember("permissionToClass:$class->id:$staff->id", now()->addHours(12), function () use ($class){
                    return permittedTo($class, \App\Enum\ClassRole::cases()) || $class->created_by == staff()->id;
                })
            @endphp
            @if($permitted)
                @php
                    $hasPermittedClass = true;
                @endphp
                <div class="col">
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <a href="{{ route('classes.show', $class->uuid) }}" class="link-stretched">
                                <lord-icon src="https://cdn.lordicon.com/hfmdczge.json" trigger="hover"
                                           colors="primary:#405189"
                                           target="div" style="width:50px;height:50px"></lord-icon>

                                <h5 class="mt-4">{{ $class->name }}</h5>

                                <p class="text-muted mb-0">{{ $class->students_count}} Student(s)</p>
                            </a>
                            <div class="mt-3 d-flex justify-content-center gap-2">
                                @can('edit.Classroom')
                                    <x-link class="btn btn-sm btn-primary" label="Edit" route="classes.edit"
                                            :param="$class->uuid"/>
                                @endcan

                                @can('delete.Classroom')
                                    <button type="button" class="btn btn-sm btn-danger"
                                            @click="$dispatch('open-delete-modal', {model: 'Classroom',
                                         modelId:{{ $class->id }},recordName: 'Class' })">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>

            @endif
        @empty
            <x-no-result description="No Class found. Considering one"/>
        @endforelse

        @if(!$hasPermittedClass )
            <x-no-result description="Sorry, you're only seeing classes you are associated with.
             Please contact your school administrator to assign you to class
            either as a class teacher or a subject teacher."/>
        @endif
    </div>
    <x-paginate :collection="$classes"/>
    <livewire:modal.delete-modal/>

</div>
@section('script')
    <script src="{{URL::asset('build/js/app.js')}}"></script>
@endsection
