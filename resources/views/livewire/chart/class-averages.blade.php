<div class="card">
    <div class="card-header border-0 align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Class Averages</h4>
        <div>
            <div class="flex-shrink-0 mb-4">
                <div class="dropdown card-header-dropdown">
                    <a class="dropdown-btn text-muted" href="#" data-bs-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        {{ $selectedTermName }} <i class="mdi mdi-chevron-down ms-1"></i>
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
        </div>
    </div><!-- end card header -->


    <div class="card-body p-0 pb-2">
        <div>
            <div class="card-body p-0 pb-2">
                <div style="height: 32rem;" >
                    <livewire:livewire-column-chart
                        key="{{ $classChartData->reactiveKey() }}"
                        :column-chart-model="$classChartData"
                    />
                </div>
            </div>

        </div>
    </div>

</div><!-- end card -->
