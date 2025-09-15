<div wire:ignore.self class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-labelledby="deleteRecordLabel" aria-hidden="true"
     x-data="{
        init() {
            window.addEventListener('open-delete-modal', () => {
                new bootstrap.Modal(this.$el).show();
            });
            window.addEventListener('close-delete-modal', () => {
                bootstrap.Modal.getInstance(this.$el).hide();
            });
        }
    }">
    <div class="modal-dialog modal-dialog-centered"  >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
            </div>
            <div class="modal-body p-5 text-center">
                <lord-icon
                    src="https://cdn.lordicon.com/gsqxdxog.json"
                    trigger="loop"
                    colors="primary:#405189,secondary:#f06548"
                    style="width:90px;height:90px">
                </lord-icon>
                <div class="mt-4 text-center">
                    <h4 class="fs-semibold">{{$title}}</h4>
                    <p class="text-muted fs-14 mb-4 pt-1">
                        {{$description}}
                    </p>
                    <div class="hstack gap-2 justify-content-center remove">
                        <button class="btn btn-link link-success fw-medium text-decoration-none"
                                data-bs-dismiss="modal"
                                id="deleteRecord-close">
                            <i class="ri-close-line me-1 align-middle"></i> Close
                        </button>
                        <button class="btn btn-danger"
                                wire:click="delete"
                                id="delete-record">
                            {{$buttonText}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        Livewire.on('recordDeleted', () => {
                $('#deleteRecordModal').modal('hide');
                // Clean up backdrop if needed
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });

    </script>
@endpush
