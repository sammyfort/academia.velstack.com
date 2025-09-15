<div>
    @section("title", 'Parents')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Parents List</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <x-link label="Add Parent" route="parents.create" class="btn btn-primary"
                                        icon="ri-add-line align-bottom me-1"/>
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

                                    <div class="col-sm-2">
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
                                    <th class="sort" data-sort="customer_name">Full Name</th>
                                    <th class="sort" data-sort="product_name">Phone Number</th>
                                    <th class="sort" data-sort="product_name">Email address</th>
                                    <th class="sort" data-sort="date">Address</th>
                                    <th class="sort" data-sort="date">Added On</th>
                                    <th class="sort" data-sort="city">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($parents as $parent)
                                    <tr>
                                        <td class="customer_name">{{$loop->iteration}}</td>
                                        <td class="customer_name">{{$parent->name}}</td>
                                        <td class="date">{{$parent->phone}} </td>
                                        <td class="date">{{$parent->email}} </td>
                                        <td class="date">{{$parent->address}} </td>
                                        <td class="date">{{$parent->created_at->format('d F, Y')}}</td>
                                        <td>
                                            <x-table-actions :id="$parent->uuid" edit="parents.edit">
                                                <li class="list-inline-item" title="Remove">
                                                    <a class="text-danger d-inline-block remove-item-btn"
                                                       @click="$dispatch('open-delete-modal', {model: '_Parent',  modelId:{{ $parent->id }},recordName: 'Parent' })">
                                                        <i class="ri-delete-bin-5-fill fs-24"></i>
                                                    </a>
                                                </li>
                                            </x-table-actions>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <x-no-result description="No parent found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                         <x-paginate :collection="$parents"/>

                    </div>

                </div>
            </div>

            <livewire:modal.delete-modal />

        </div>
        <!--end col-->

    </div>

    @section('script')

        <script src="{{ URL::asset('build/js/app.js') }}"></script>

    @endsection
</div>


