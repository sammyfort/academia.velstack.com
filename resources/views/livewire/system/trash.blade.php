<div>
    @section("title", 'Trash')
    <div class="row">
        <div class="col-lg-12">
            <div class="card" id="customerList">
                <div class="card-header border-bottom-dashed">

                    <div class="row g-4 align-items-center">
                        <div class="col-sm">
                            <div>
                                <h5 class="card-title mb-0">Trash Bin</h5>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex flex-wrap align-items-start gap-2">
                                @if($trashes->isNotEmpty())
                                    <a href=" " wire:click.prevent="restoreAll"
                                       class="btn btn-primary" target="_blank">
                                        <i class="ri-reserved-fill align-bottom me-2"></i>Restore All
                                    </a>

                                    <a href="" wire:click.prevent="deleteAll"
                                       class="btn btn-danger" target="_blank">
                                        <i class="ri-delete-bin-2-fill align-bottom me-2"></i>Delete All
                                    </a>
                                @endif

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
                                            <input wire:model.live="query" type="text" class="form-control search"
                                                   placeholder="Search something...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div>
                                            <select wire:model.live="model" class="form-control form-select">
                                                @foreach($models as $model)
                                                    <option value="{{ $model }}">{{$model}}</option>
                                                @endforeach
                                            </select>
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
                                    <th>Object Name</th>
                                    <th>Deleted AT</th>
                                    <th>Restore</th>
                                    <th>Delete</th>

                                </tr>
                                </thead>
                                <tbody>

                                    @forelse($trashes as $trash)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $trash->trash_info }}</td>
                                            <td>{{ $trash->deleted_at->format('d F Y - h:s')}}</td>
                                            <td><button type="button" class="btn btn-success btn-sm"
                                                        wire:click.prevent="restore({{$trash->id}})">Restore</button></td>
                                            <td><button type="button" class="btn btn-danger btn-sm"
                                                        wire:click.prevent="delete({{$trash->id}})">Delete Permanently</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">
                                                <x-no-result description="No Trash found"/>
                                            </td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>

                        </div>
                        <x-paginate :collection="$trashes"/>
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


