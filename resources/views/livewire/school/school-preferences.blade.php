<div   x-show="activeTab === 'preferences'" x-cloak>

    <div class="mb-3">
        <h5 class="card-title text-decoration-underline mb-3">School Preferences:</h5>
        <ul class="list-unstyled mb-0">
            <li class="d-flex">
                <div class="flex-grow-1">
                    <label for="directMessage" class="form-check-label fs-14">
                        Show school image </label>
                    <p class="text-muted">Show school image on report card</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="form-check form-switch">
                        <input wire:model.live="preference.show_school_image_on_report" class="form-check-input" type="checkbox" role="switch"
                               id="directMessage" />
                    </div>
                </div>
            </li>

            <li class="d-flex">
                <div class="flex-grow-1">
                    <label for="directMessage" class="form-check-label fs-14">
                        Show Student Image </label>
                    <p class="text-muted">Show student image on report card</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="form-check form-switch">
                        <input wire:model.live="preference.show_student_image_on_report" class="form-check-input" type="checkbox" role="switch"
                               id="directMessage" />
                    </div>
                </div>
            </li>

            <li class="d-flex">
                <div class="flex-grow-1">
                    <label for="directMessage" class="form-check-label fs-14">
                        Show Class Average </label>
                    <p class="text-muted">Show student class average in subject on report card</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="form-check form-switch">
                        <input wire:model.live="preference.show_class_average" class="form-check-input" type="checkbox" role="switch"
                               id="directMessage" />
                    </div>
                </div>
            </li>
            <li class="d-flex mt-2">
                <div class="flex-grow-1">
                    <label class="form-check-label fs-14" for="desktopNotification">
                       Show Overall Position
                    </label>
                    <p class="text-muted">Show student's overall position in subject
                        and school on report card if student class has same class groups.</p>
                </div>
                <div class="flex-shrink-0">
                    <div class="form-check form-switch">
                        <input wire:model.live="preference.show_overall_position" class="form-check-input" type="checkbox" role="switch"
                               id="desktopNotification" />
                    </div>
                </div>
            </li>
            <li class="d-flex mt-2">
                <div class="flex-grow-1">
                    <label class="form-check-label fs-14" for="emailNotification">
                        Show Student Overall Percentage
                    </label>
                    <p class="text-muted"> Show student's overall scores percentage on the report card </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="form-check form-switch">
                        <input wire:model.live="preference.show_overall_percentage" class="form-check-input" type="checkbox" role="switch"
                               id="emailNotification"/>
                    </div>
                </div>
            </li>

            <li class="d-flex mt-2">
                <div class="flex-grow-1">
                    <label class="form-check-label fs-14" for="emailNotification">
                        Allow Transfer
                    </label>
                    <p class="text-muted"> Allow other schools to make transfer request to this school </p>
                </div>
                <div class="flex-shrink-0">
                    <div class="form-check form-switch">
                        <input wire:model.live="preference.open_for_transfer" class="form-check-input" type="checkbox" role="switch"
                               id="emailNotification"/>
                    </div>
                </div>
            </li>
        </ul>
    </div>


    <!--end tab-pane-->
</div>
