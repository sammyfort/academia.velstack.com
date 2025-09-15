<div>
    @section("title", 'Timetables')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Timetable List</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <x-link label="Create Timetable" route="timetables.create" class="btn btn-primary"
                                        icon="ri-add-line align-bottom me-1"/>
                                <x-link label="View Timetable" route="timetables.show" :param="$term->uuid" class="btn btn-dark"
                                        icon="ri-eye-line align-bottom me-1"/>

                                <a href="{{route('timetables.school.print', ['term_uuid' => $term->uuid])}}" target="_blank" class="btn btn-warning" >
                                    <i class="ri-printer-fill align-bottom me-1 me-2"></i>
                                    Print Timetable
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body border-bottom-dashed border-bottom">
                    <form>
                        <div class="row g-3">
                            <div class="col-xl-12">
                                <div class="row g-3">
                                    <div class="col-xl-3">
                                        <div class="search-box">
                                            <input wire:model.live="search" type="text" class="form-control search"
                                                   placeholder="Search something...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <!--end col-->
                                    <div class="col-sm-2">
                                        <div>
                                            <button type="button" class="btn btn-primary w-100"
                                                    wire:click.prevent="resetFilter()">
                                                <i class="ri-equalizer-fill me-2 align-bottom"></i>Filters
                                            </button>
                                        </div>
                                    </div>

                                    <!--end col-->
                                    <div class="col-sm-2">
                                        <div>
                                            <x-input-select  model="term_id"
                                                             bind=".live" :options="$terms"
                                                             key="id" value="name"
                                                             placeholder="Select Term" wrapper_class="col-md-12"/>
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
                                    <th class="sort" data-sort="customer_name">Day</th>
                                    <th class="sort" data-sort="product_name">Subject</th>
                                    <th class="sort" data-sort="product_name">Class</th>
                                    <th class="sort" data-sort="date">Staff</th>
                                    <th class="sort" data-sort="date">Start Time</th>
                                    <th class="sort" data-sort="date">End Time</th>
                                    <th class="sort" data-sort="date">Created On</th>
                                    <th class="sort" data-sort="city">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($timetables as $timetable)
                                    <tr>
                                        <td class="customer_name">{{$loop->iteration}}</td>
                                        <td class="customer_name">{{$timetable->day}}</td>
                                        <td class="date">{{$timetable->subject->name}} </td>
                                        <td class="date">{{$timetable->class->name}} </td>
                                        <td class="date">{{$timetable->staff->fullname}} </td>
                                        <td class="text-success">{{$timetable->start_time->format('H:i A')}} </td>
                                        <td class="text-danger">{{$timetable->end_time->format('H:i A')}} </td>
                                        <x-date-field :date="$timetable->created_at"/>

                                        <td>
                                            <x-table-actions :id="$timetable->uuid" edit="timetables.edit">
                                                <li class="list-inline-item" title="Remove">
                                                    <a class="text-danger d-inline-block remove-item-btn"
                                                       @click="$dispatch('open-delete-modal', {model: 'Timetable',  modelId:{{ $timetable->id }},recordName: 'Timetable' })">
                                                        <i class="ri-delete-bin-5-fill fs-24"></i>
                                                    </a>
                                                </li>
                                            </x-table-actions>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <x-no-result description="No Timetable found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                        <x-paginate :collection="$timetables"/>
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


