<div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Level Average</h4>
                <div class="flex-shrink-0">
                    <div class="dropdown card-header-dropdown">
                        <a class="dropdown-btn text-muted" href="#" data-bs-toggle="dropdown"
                           aria-haspopup="true" aria-expanded="false">
                            {{$selectedTermName}}<i class="mdi mdi-chevron-down ms-1"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            @foreach($terms as $term)
                                <a class="dropdown-item" href="#" wire:click.prevent="selectTerm({{ $term->id }})">
                                    {{ $term->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div><!-- end card header -->

            <div class="card-body">
                <div style="height: 32rem;" >
                    <livewire:livewire-pie-chart
                        key="{{ $pieChartModel->reactiveKey() }}"
                        :pie-chart-model="$pieChartModel"
                    />
                </div>
            </div><!-- end cardbody -->
        </div><!-- end card -->

@section('script')
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

@endsection
