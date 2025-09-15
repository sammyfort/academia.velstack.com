@section('title', "Subscription")
<div>
    <div class="row justify-content-center mt-4">
        <div class="col-lg-5">
            <div class="text-center mb-4">
                <h4 class="fw-semibold fs-22">Plans & Pricing</h4>
                <p class="text-muted mb-4 fs-15">Simple pricing. No hidden fees. Advanced features for you school.</p>

                <div class="d-inline-flex">
                    <ul class="nav nav-pills arrow-navtabs plan-nav rounded mb-3 p-1" id="pills-tab" role="tablist">

                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold active" id="annual-tab" data-bs-toggle="pill"
                                    data-bs-target="#annual" type="button" role="tab" aria-selected="false">Termly
                                <span class="badge bg-success">25% Off</span></button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <div class="row justify-content-center mt-4">
        <div class="col-xxl-3 col-lg-5">
            <div class="card pricing-box ribbon-box right">
                <div class="card-body bg-light m-2 p-4">
                    <div class="ribbon-two ribbon-two-danger"><span>Popular</span></div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h5 class="mb-0 fw-semibold">Enterprise</h5>
                        </div>
                        <div class="ms-auto">
                            <h2 class="month mb-0">{{cedi()}}{{schoolCharge()}} <small class="fs-13 text-muted">/Term</small></h2>

                        </div>
                    </div>
                    <p class="text-muted">This plan is for small and big-mid schools running a large school.</p>
                    <ul class="list-unstyled vstack gap-3">
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>{{school()->students()->count()}}</b> Students
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>{{school()->staff()->count()}}</b> Staff
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    Free Updates
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>+ SMS </b> Notifications
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>24/7</b> Support
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex">
                                <div class="flex-shrink-0 text-success me-1">
                                    <i class="ri-checkbox-circle-fill fs-15 align-middle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <b>Unlimited </b> Storage
                                </div>
                            </div>
                        </li>

                    </ul>
                    <form  id="paymentForm">
                        <div class="mt-3 pt-2">
                            <button type="submit"  id="pay-btn" onclick="payWithPaystack()" class="btn btn-primary w-100">
                                <i class="ri-secure-payment-fill align-bottom me-1"></i> Pay GHS {{schoolCharge()}}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->




    <script src="https://js.paystack.co/v1/inline.js"></script>

    <script>
        const paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener("submit", payWithPaystack, false);
        function payWithPaystack(e) {
            e.preventDefault();

            let handler = PaystackPop.setup({
                key: '{{config('services.PAYSTACK_PUBLIC_KEY')}}',
                email:  '{{school()->email}}',
                amount: {{schoolCharge()}} * 100,
                currency: 'GHS',
                ref: 'OPEN-PORTAL'+'-'+Math.floor((Math.random() * 1000000000) + 1),
                metadata: {
                    school_id: {{school()->id}}
                },
                onClose: function(){
                    $(document).ready(function() {
                        $('#paymentCancelled').modal('show');
                    });
                },
                callback: function(response){
                    $(document).ready(function() {
                        $('#paidSuccessfully').modal('show');
                    });
                }
            });

            handler.openIframe();
        }
    </script>

    <div class="modal fade" id="paidSuccessfully" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">

                    <div class="avatar-lg mx-auto mt-2">
                        <div class="avatar-title bg-light text-success display-3 rounded-circle">
                            <i class="ri-check-fill"></i>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="mb-3">Payment Successful</h4>
                        <p class="text-muted mb-4"> The subscription was successfully received by Velstack. Wait for the the page refresh.</p>
                        <div class="hstack gap-2 justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-link shadow-none link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentCancelled" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">

                    <div class="avatar-lg mx-auto mt-2">
                        <div class="avatar-title bg-light text-danger display-3 rounded-circle">
                            <i class="ri-close-circle-fill"></i>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="mb-3">Payment Cancelled</h4>
                        <p class="text-muted mb-4"> The transaction was not completed. Please refresh this page.</p>
                        <div class="hstack gap-2 justify-content-center">
                            <a href="javascript:void(0);" class="btn btn-link shadow-none link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
@section('script')
    <script src="{{ URL::asset('build/js/pages/pricing.init.js') }}"></script>

    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
