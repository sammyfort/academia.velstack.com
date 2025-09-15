@section("title", 'Calenders')


<div class="row">
    <div class="col-lg-12">
        <div class="card" id="customerList">
            <div class="card-header border-bottom-dashed">

                <div class="row g-4 align-items-center">
                    <div class="col-sm">
                        <div>
                            <h5 class="card-title mb-0">Academic Calender List</h5>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex flex-wrap align-items-start gap-2">

                            <x-link label="Create Calender" route="calenders.create" class="btn btn-primary"
                                    icon="ri-add-line align-bottom me-1"/>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body border-bottom-dashed border-bottom">
                <form>
                    <div class="row g-3">
                        <div class="col-xl-6">
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
                                    <div class="">
                                        <input wire:model.live="date" type="text" class="form-control"
                                               id="datepicker-range"
                                               data-provider="flatpickr" data-date-format="d M, Y"
                                               data-range-date="true" placeholder="Select date">
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-sm-4">
                                    <div>
                                        <select class="form-control" data-plugin="choices" data-choices
                                                data-choices-search-false name="choices-single-default"
                                                id="idStatus">

                                            @foreach(\App\Enum\TermStatus::cases() as $term)
                                                <option value="{{$term->value}}">{{$term->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--end col-->

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
                                <th class="sort">Calender Name</th>
                                <th class="sort">Start Date</th>
                                <th class="sort" data-sort="product_name">Status</th>
                                <th class="sort" data-sort="date">Ends At</th>
                                <th class="sort" data-sort="city">Action</th>
                            </tr>
                            </thead>
                            <tbody class="list form-check-all">
                            @forelse($calenders as $term)
                                <tr>
                                    <td class="customer_name">{{$loop->iteration}}</td>
                                    <td class="customer_name">{{$term->name}}</td>
                                    <td class="date">{{$term->start_date->format('d F, Y')}}</td>
                                    <td class="status">
                                        @if($term->status === \App\Enum\TermStatus::ACTIVE->value)
                                            <span
                                                class="badge bg-success-subtle text-success text-uppercase">{{$term->status}}</span>
                                        @else
                                            <span
                                                class="badge bg-warning-subtle text-warning text-uppercase">{{$term->status}}</span>
                                        @endif

                                    </td>
                                    <td class="date">{{$term->end_date->format('d F, Y')}}</td>

                                    <td>
                                        <x-table-actions :id="$term->uuid" edit="calenders.edit">

{{--                                            <li class="list-inline-item" title="Remove">--}}
{{--                                                <a class="text-danger d-inline-block remove-item-btn"--}}
{{--                                                   @click="$dispatch('open-delete-modal', {model: 'Term',  modelId:{{ $term->id }},recordName: 'Calender' })">--}}
{{--                                                    <i class="ri-delete-bin-5-fill fs-24"></i>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}

                                        </x-table-actions>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center">
                                        <x-no-result description="No Student found"/>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                    </div>
                    <x-paginate :collection="$calenders"/>
                </div>

            </div>
        </div>
        <livewire:modal.delete-modal />
    </div>
    <!--end col-->


</div>



@section('script')
    <script src="{{ URL::asset('build/libs/list.js/list.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/ecommerce-order.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
