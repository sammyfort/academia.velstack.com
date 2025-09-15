<div>
    @section("title", 'Lent History')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Books <span class="text-success">{{$book->title}}</span>
                                    Take Home History</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                <x-link label="Library" route="library.index" class="btn btn-primary"
                                        icon="ri-gird-line align-bottom me-1"/>

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
                                    <th class="sort" data-sort="customer_name">Book Title</th>
                                    <th class="sort" data-sort="product_name">Isbn</th>
                                    <th class="sort" data-sort="product_name">Author</th>
                                    <th class="sort" data-sort="date">Taken By</th>
                                    <th class="sort" data-sort="date">Taken On</th>
                                    <th class="sort" data-sort="date">Due By</th>

                                </tr>
                                </thead>
                                <tbody>
                                @forelse($lents as $lend)
                                    <tr>
                                        <td class="customer_name">{{$loop->iteration}}</td>
                                        <td class="customer_name">{{$lend->book->title}}</td>
                                        <td class="date">{{$lend->book->isbn}} </td>
                                        <td class="date">{{$lend->book->author}} </td>
                                        <td>
                                            @switch($lend->lentable_type)
                                                @case('App\Models\Student')
                                                    <a class="link-info"
                                                       href="{{route('students.show', $lend->student->uuid)}}">
                                                        {{$lend->student->fullname}}
                                                    </a>

                                                    @break
                                                @case('App\Models\Staff')
                                                    <a class="link-info"
                                                       href="{{route('staff.show', $lend->staff->uuid)}}">
                                                        {{$lend->staff->fullname}}
                                                    </a>
                                                    @break
                                            @endswitch
                                        </td>

                                        <td>{{$lend->lent_on->format('d F Y')}}</td>

                                        <td>
                                            @php $bg = $lend->lent_on->isPast() ? 'danger' : 'success'  @endphp
                                            <span class="badge bg-{{$bg}}-subtle text-{{$bg}} text-uppercase">{{$lend->lent_on->format('d F Y')}}</span>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <x-no-result description="No Book found"/>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                        </div>
                        <x-paginate :collection="$lents"/>
                    </div>

                </div>
            </div>
        </div>
        <!--end col-->
    </div>

    @section('script')

        <script src="{{ URL::asset('build/js/app.js') }}"></script>
    @endsection
</div>


