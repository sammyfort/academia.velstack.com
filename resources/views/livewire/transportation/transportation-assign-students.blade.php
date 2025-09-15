@section('title', 'Assign Students')

<div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Assign Students To Transport</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">

                                <x-link label="Create Route" route="transportations.create" class="btn btn-primary"
                                        icon="ri-add-line align-bottom me-1"/>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <input wire:model.live="search" type="text" class="form-control search"
                                           placeholder="Search something...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xl-6">
                                <div class="row g-3">

                                    <div class="col-sm-4">
                                        <div>
                                            <button type="button" class="btn btn-primary w-100"
                                                    wire:click.prevent="resetFilter()">
                                                <i class="ri-equalizer-fill me-2 align-bottom"></i>Filters
                                            </button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                    </form>
                </div>
                <div class="card-body">
                    <div>
                        <div class="table-responsive table-card mb-1">
                            <table class="table table-nowrap align-middle">
                                <thead class="text-muted table-light">
                                <tr class="text-uppercase">
                                    <th class="sort" data-sort="id">#</th>
                                    <th class="sort">Student Name</th>
                                    <th class="sort" >Class</th>
                                    <th class="sort" >Bus Stop</th>
                                    <th class="sort">Fare (GHS)</th>
                                    <th class="sort" >Action</th>
                                </tr>
                                </thead>
                                <tbody class="list form-check-all">
                                @forelse($students as $student)
                                    <tr>
                                        <td class="name">{{$loop->iteration}}</td>
                                        <td class="name">{{$student->fullname}}</td>
                                        <td class="name">{{$student->class->name}}</td>
                                        @if($student->transportation)
                                            <td class="text-success">{{$student->transportation->route }}</td>
                                        @else
                                            <td class="text-danger">{{'Not Assigned'}}</td>
                                        @endif
                                        <td class="name">{{$student->transportation->fee->amount ?? 0}}</td>
                                        <td>
                                            <ul class="list-inline hstack gap-2 mb-0">

                                                <li class="list-inline-item" title="Select">
                                                    <div class="mb-0 form-group">
                                                        <select  class="form-select" wire:change="updateRoute({{$student->id}}, $event.target.value)" >
                                                            <option value="">Select</option>
                                                            @forelse($transportations as $one)
                                                                <option value="{{$one->id}}">{{$one->route}}</option>
                                                            @empty
                                                                <option value="" class="text-danger"> No Route Found</option>
                                                            @endforelse

                                                        </select>
                                                    </div>
                                                </li>


                                            </ul>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <x-no-result description="No Student found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                        <x-paginate :collection="$students"/>
                    </div>

                </div>
            </div>

        </div>
        <!--end col-->
    </div>


</div>

@section('script')

    <script src="{{URL::asset('build/js/pages/api-key.init.js')}}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
