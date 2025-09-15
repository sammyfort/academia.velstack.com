@section('title', 'Getting Started')
<div>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="bg-dark-subtle position-relative">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <h3>Getting Started</h3>
                            <p class="mb-0 text-muted">Last update: 1 Jan, 2025</p>
                        </div>
                    </div>
                    <div class="shape">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                             xmlns:svgjs="http://svgjs.com/svgjs" width="1440" height="60" preserveAspectRatio="none"
                             viewBox="0 0 1440 60">
                            <g mask="url(&quot;#SvgjsMask1001&quot;)" fill="none">
                                <path d="M 0,4 C 144,13 432,48 720,49 C 1008,50 1296,17 1440,9L1440 60L0 60z"
                                      style="fill: var(--vz-secondary-bg);"></path>
                            </g>
                            <defs>
                                <mask id="SvgjsMask1001">
                                    <rect width="1440" height="60" fill="#ffffff"></rect>
                                </mask>
                            </defs>
                        </svg>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i data-feather="check-circle" class="text-success icon-dual-success icon-xs"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5>Getting Started</h5>
                            <p class="text-muted">Getting started on {{config('app.name')}} is simpler than you think.
                                Since some actions depend on others, you are required to complete certain steps before
                                proceeding to others. In this guide, we will walk you through a step-by-step process to
                                set up the system completely for operations.</p>

                            <p class="text-muted">Here is the step-by-step order of how you should create your records
                                for convenience:</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i data-feather="check-circle" class="text-success icon-dual-success icon-xs"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5>Add Subjects</h5>
                            <p class="text-muted">The first step is to <a class="text-success"
                                                                          href="{{route('subjects.create')}}">create all
                                    subjects</a> taught in your school. This is essential for assigning subjects to
                                classes and students.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i data-feather="check-circle" class="text-success icon-dual-success icon-xs"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5>Add Staff</h5>
                            <p class="text-muted">Next, it is crucial to <a class="text-success"
                                                                            href="{{route('staff.create')}}">add
                                    staff</a>. This includes all personnel who will use the portal for various
                                activities. After adding all staff members, ensure you <a class="text-success"
                                                                                          href="{{route('roles.permissions')}}">assign
                                    permissions</a> to enable them to perform specific tasks.</p>

                            <p class="text-muted">These staff members may include Finance Officers, Administrators,
                                Academic Officers, and Tutors (Class and Subject Teachers).</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i data-feather="check-circle" class="text-success icon-dual-success icon-xs"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5>Add Classrooms</h5>
                            <p class="text-muted">It is important to <a class="text-success"
                                                                        href="{{route('classes.create')}}">add
                                    classrooms</a> in the school. This includes all the classrooms in the school. After
                                adding classrooms, <a class="text-success" href="{{route('classes.assign.subject')}}">assign
                                    subjects</a> to each class, as different classes may study different subjects.</p>

                            <p class="text-muted">Once subjects are assigned to classes, it is equally important to <a
                                    class="text-success" href="{{route('classes.assign.staff')}}">assign staff</a> to
                                each class, either as subject teachers or class teachers.</p>

                            <p class="text-danger">Note that when staff visit the <a class="text-success"
                                                                                     href="{{route('classes.index')}}">Classes</a>
                                page, they will not see any class unless they are assigned to it as a subject teacher or
                                class teacher. This ensures that staff members only see the classes they are directly
                                involved with.</p>

                            <p class="text-muted">This feature improves usability and reduces the complexity of
                                displaying all classes to staff members with no activities in those classes.</p>

                            <p class="text-muted">Class teachers and subject teachers have distinct roles. Subject
                                teachers can only enter academic scores for the subjects they teach in a class during
                                reporting.</p>

                            <p class="text-muted">In some cases, students in the same class may study different
                                subjects. Teachers can assign specific subjects to individual students as needed.</p>

                            <p class="text-muted">Additionally, subject teachers may wish to record more than just CLASS
                                SCORES and EXAM SCORES for an academic term. For example, a SCIENCE teacher might need
                                to include CLASS SCORES, PRACTICAL SCORES, and EXAM SCORES. Our system accommodates such
                                customizations.</p>

                            <ul class="text-muted">
                                <li><p>Assign subjects to students or specific groups of students</p></li>
                                <li><p>Mark class attendance</p></li>
                                <li><p>Generate student reports</p></li>
                                <li><p>Add score types for various subjects</p></li>
                                <li><p>Promote students</p></li>
                                <li><p>Export class lists, etc.</p></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i data-feather="check-circle" class="text-success icon-dual-success icon-xs"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5>Add Calendar/Term</h5>
                            <p class="text-muted">Next, it is important to <a class="text-success"
                                                                              href="{{route('calenders.create')}}">add
                                    an academic calendar</a>. When creating a calendar, specify its status as either
                                active or ended. Ensure these statuses <span class="text-danger">do not overlap</span>,
                                as we rely on the active calendar for current-term actions.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i data-feather="check-circle" class="text-success icon-dual-success icon-xs"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5>Onboard Students</h5>
                            <p class="text-muted">After completing the above tasks, you can <a class="text-success"
                                                                                               href="{{route('students.admit')}}">admit
                                    new students</a> or onboard current ones.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <i data-feather="check-circle" class="text-success icon-dual-success icon-xs"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5>Finance</h5>
                            <p class="text-muted">This is the billing cycle for the school. You can <a
                                    class="text-success" href="{{route('finance.fee.create')}}">create a fee</a> for a single
                                student, an entire class, or all students in the school. Once created, the system will
                                automatically bill the selected students.</p>

                            <p class="text-muted">You can also create a fee without billing any student immediately and
                                <a class="text-success" href="{{route('finance.bill.student')}}">bill them later</a>.</p>

                            <p class="text-muted">When billing students, the system ensures that no student is billed
                                twice for the same fee in the same academic term. If a student has already been billed,
                                only the remaining students will be billed. This applies to both individual and mass
                                billing.</p>
                        </div>
                    </div>


                    <div>
                        <h5 class="fw-semibold mb-3">Comments:</h5>
                        <div data-simplebar class="px-3 mx-n3 mb-2">
                            @forelse($reviews as $review)
                                <div class="d-flex mb-4">
                                    <div class="flex-shrink-0">
                                        <img src="{{ $review->user->image() }}" alt=""
                                             class="avatar-xs rounded-circle"/>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="fs-13 d-flex align-items-center">
                                            {{-- User Name --}}
                                            <span class="d-flex align-items-center">{{ $review->user->fullname }}
                                                @if($review->reviewable_type == \App\Models\User::class)
                                                    <span class="ms-1"
                                                          style="display: inline-flex; align-items: center;">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 24 24" fill="#1DA1F2" width="16"
                                                                     height="16">
                                                                    <circle cx="12" cy="12" r="10"
                                                                            fill="#1DA1F2"></circle>
                                                                    <path fill="#fff"
                                                                          d="M10 14.6l-2.8-2.8L6 12l4 4 8-8-1.4-1.4z"></path>
                                                                </svg>
                                                            </span>
                                                        </span>

                                            <small class="text-muted ms-2">{{'@'}}{{config('app.name')}}</small>
                                            @endif

                                            {{-- Date --}}
                                            <small class="text-muted ms-3">
                                                @if ($review->created_at->diffInHours() < 24)
                                                    {{ $review->created_at->diffForHumans() }}
                                                @else
                                                    {{ $review->created_at->format('d M Y - h:i A') }}
                                                @endif
                                            </small>
                                        </h5>

                                        <p class="text-muted">{{ $review->content }}</p>
                                        <a href="javascript:void(0);" class="badge text-muted bg-light"
                                           wire:click="$set('showReplyBox.{{ $review->id }}', true)">
                                            <i class="mdi mdi-reply"></i> Reply
                                        </a>

                                        @if(isset($showReplyBox[$review->id]) && $showReplyBox[$review->id])
                                            <div class="mt-2">
                                                <textarea class="form-control bg-light border-light" rows="2"
                                                          placeholder="Enter your reply..."
                                                          wire:model.defer="replyContent.{{ $review->id }}"></textarea>
                                                <button class="btn btn-success btn-sm mt-2"
                                                        wire:click="postReply({{ $review->id }})">Post Reply
                                                </button>
                                            </div>
                                        @endif

                                        @foreach($review->replies as $reply)
                                            <div class="d-flex mt-4">
                                                <div class="flex-shrink-0">

                                                    <img src="{{ $reply->user->image() }}" alt=""
                                                         class="avatar-xs rounded-circle"/>
                                                </div>

                                                <div class="flex-grow-1 ms-3">
                                                    <h5 class="fs-13 d-flex align-items-center">
                                                        {{-- User Name --}}
                                                        <span class="d-flex align-items-center">{{ $reply->user->fullname }}
                                                            @if($reply->replyable_type == \App\Models\User::class)
                                                                <span class="ms-1"
                                                                      style="display: inline-flex; align-items: center;">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 24 24" fill="#1DA1F2" width="16"
                                                                     height="16">
                                                                    <circle cx="12" cy="12" r="10"
                                                                            fill="#1DA1F2"></circle>
                                                                    <path fill="#fff"
                                                                          d="M10 14.6l-2.8-2.8L6 12l4 4 8-8-1.4-1.4z"></path>
                                                                </svg>
                                                            </span>
                                                        </span>

                                                        <small class="text-muted ms-2">{{'@'}}{{config('app.name')}}</small>
                                                        @endif


                                                        {{-- Date --}}
                                                        <small class="text-muted ms-3">
                                                            @if ($reply->created_at->diffInHours() < 24)
                                                                {{ $reply->created_at->diffForHumans() }}
                                                            @else
                                                                {{ $reply->created_at->format('d M Y - h:i A') }}
                                                            @endif
                                                        </small>
                                                    </h5>

                                                    <p class="text-muted">{{ $reply->content }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted"> No Comment</p>
                            @endforelse

                            <x-paginate :collection="$reviews"/>
                        </div>

                        <form class="mt-2">
                            <label for="newReview" class="form-label text-body">Leave a Comment</label>
                            <textarea class="form-control bg-light border-light" id="newReview" rows="3"
                                      placeholder="Enter your comment..."
                                      wire:model.defer="newReview"></textarea>
                            <button type="button" class="btn btn-success mt-2" wire:click="postReview">Post Comment
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@section('script')

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
