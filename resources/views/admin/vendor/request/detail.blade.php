@extends('layouts.admin')
@section('title')
    Vendor Request || Dasboard
@endsection
@section('style')
    <style>
        @media (max-width: 767px) {
            .swal-top {
                position: relative !important;
                top: -170px;

            }
        }

        .hide {
            display: none;
        }

        .show {
            display: block;
        }
    </style>
@endsection
@section('content')
    @php
        $data = json_decode($vendor->vendor_data);
    @endphp
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $vendor->user->name }} Vendor Request Detail</h4>
                                <div class="card-header-action">
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Business Name</label>
                                        <input type="text" class="form-control" name="business_name" id="business_name"
                                            value="{{ $data->business_name }}" readonly>
                                        @error('business_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="category">Category</label>
                                        <input type="text" class="form-control" name="category" id="category"
                                            value="{{ $data->category }}" readonly>
                                        @error('category')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="shop_phone">Shop Phone</label>
                                        <input type="text" class="form-control" name="shop_phone" id="shop_phone"
                                            value="{{ $data->shop_phone }}" readonly>
                                        @error('shop_phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="mobile_phone">Mobile Phone</label>
                                        <input type="text" class="form-control" name="mobile_phone" id="mobile_phone"
                                            value="{{ $data->mobile_phone }}" readonly>
                                        @error('mobile_phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Business Logo</label>
                                        <br />
                                        @if ($data->business_logo)
                                            <a href="{{ $data->business_logo }}" target="_blank">
                                                <img class="rounded-circle" src="{{ $data->business_logo }}" width="70px"
                                                    height="70px" alt="{{ $vendor->user->name }}">
                                            </a>
                                        @endif
                                        @error('business_logo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Shop Card</label>
                                        <br />
                                        @if ($data->shop_card)
                                            <a href="{{ $data->shop_card }}" target="_blank">
                                                <img class="rounded-circle" src="{{ $data->shop_card }}" width="70px"
                                                    height="70px" alt="{{ $vendor->user->name }}">
                                            </a>
                                        @endif
                                        @error('shop_card')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="business_mail">Business Mail</label>
                                        <input type="text" class="form-control" name="business_mail" id="business_mail"
                                            value="{{ $data->business_mail }}" readonly>
                                        @error('business_mail')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Owner Image</label>
                                        <br />
                                        @if ($data->owner_image)
                                            <a href="{{ $data->owner_image }}" target="_blank">
                                                <img class="rounded-circle" src="{{ $data->owner_image }}" width="70px"
                                                    height="70px" alt="{{ $vendor->user->name }}">
                                            </a>
                                        @endif
                                        @error('owner_image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="business_address">Business Address</label>
                                        <textarea name="business_address" minlength="100" maxlength="5000" class="form-control" id="business_address" readonly>{{ $data->business_address }}</textarea>
                                        @error('business_address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="business_withabf">Why did you choose to start your business with
                                            ABF?</label>
                                        <textarea name="business_withabf" minlength="100" maxlength="5000" class="form-control" id="business_withabf" readonly> {{ $data->business_withabf }}</textarea>
                                        @error('business_withabf')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="career_goal">How does this business align with your career
                                            goals?</label>
                                        <textarea name="career_goal" minlength="100" maxlength="5000" class="form-control" id="career_goal" readonly>{{ $data->career_goal }}</textarea>
                                        @error('career_goal')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="describe_product">Can you describe the products you plan to sell on
                                            ABF?</label>
                                        <textarea name="describe_product" minlength="100" maxlength="5000" class="form-control" id="describe_product"
                                            readonly>{{ $data->describe_product }}</textarea>
                                        @error('describe_product')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Are you considering selling herbal items on our site?</label>
                                        <div class="form-row mt-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio"
                                                        id="herbel_product_yes" name="herbel_product" value="1"
                                                        @if ($data->herbel_product == '1') checked @endif disabled>
                                                    <label class="custom-control-label"
                                                        for="herbel_product_yes">Yes</label>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio"
                                                        id="herbel_product_no" name="herbel_product" value="0"
                                                        @if ($data->herbel_product == '0') checked @endif disabled>
                                                    <label class="custom-control-label" for="herbel_product_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="previous_work">What companies have you worked for in the
                                            past?</label>
                                        <textarea name="previous_work" minlength="100" maxlength="5000" class="form-control" id="previous_work" readonly>{{ $data->previous_work }}</textarea>
                                        @error('previous_work')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Do you have any work experience?</label>
                                        <div class="form-row mt-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio"
                                                        id="experience_yes" name="experience" value="1"
                                                        @if ($data->experience == '1') checked @endif disabled>
                                                    <label class="custom-control-label" for="experience_yes">Yes</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="experience_no"
                                                        name="experience" value="0"
                                                        @if ($data->experience == '0') checked @endif disabled>
                                                    <label class="custom-control-label" for="experience_no">No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>What are your plans for sourcing and delivering the products?</label>
                                        <div class="form-row mt-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="delivery_yes"
                                                        name="delivery" value="by_abf"
                                                        @if ($data->delivery == 'by_abf') checked @endif disabled>
                                                    <label class="custom-control-label" for="delivery_yes">By
                                                        ABF</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="delivery_no"
                                                        name="delivery" value="by_yourself"
                                                        @if ($data->delivery == 'by_yourself') checked @endif disabled>
                                                    <label class="custom-control-label" for="delivery_no">By
                                                        Yourself</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>How do you intend to market your business on ABF?</label>
                                        <div class="form-row mt-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio"
                                                        id="market_business_yes" name="market_business"
                                                        value="Social Media"
                                                        @if ($data->market_business == 'social_media') checked @endif disabled>
                                                    <label class="custom-control-label" for="market_business_yes">Social
                                                        Media</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio"
                                                        id="market_business_no" name="market_business"
                                                        value="network_marketing"
                                                        @if ($data->market_business == 'network_marketing') checked @endif disabled>
                                                    <label class="custom-control-label" for="market_business_no">Network
                                                        Marketing</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="website_link">Website Link</label>
                                        <input type="url" class="form-control" name="website_link" id="website_link"
                                            value="{{ $data->website_link }}" readonly>
                                        @error('website_link')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="social_media_link">Social Media Link</label>
                                        <span class="text-danger">*</span>
                                        <input type="url" class="form-control" name="social_media_link"
                                            id="social_media_link" value="{{ $data->social_media_link }}" readonly>
                                        @error('social_media_link')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    @if ($vendor->status == '1')
                                        <div class="col-xs-6 col-sm-12 col-md-6">
                                            <button id="cancelButton" data-status="0" data-id="{{ $vendor->id }}"
                                                data-status="0" class="btn btn-block btn-secondary" type="button">Reject
                                                Application</button>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6">
                                            <button id="actionButton" data-status="2" data-id="{{ $vendor->id }}"
                                                class="btn btn-block btn-primary" type="button">Approved
                                                Application</button>
                                        </div>
                                    @endif

                                    @if ($vendor->bank_id == '' && ($vendor->status == '0' || $vendor->status == '-1'))
                                        <div class="col-xs-12 col-sm-12 col-md-12">
                                            <button id="deleteButton" class="btn btn-block btn-danger"
                                                type="button">Delete</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($vendor->bank_id && $vendor->transectionid && $vendor->transectiondate)
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4> Vendor Payment Detail</h4>
                                            <div class="card-header-action">
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <input type="hidden" id="id" name="id"
                                                    value="{{ $vendor->id }}" />
                                                <div class="form-group col-6">
                                                    <label for="bank_id">Bank</label>
                                                    <select class="form-control" id="bank_id" name="bank_id"
                                                        @if ($vendor->bank_id) disabled @endif>
                                                        <option value="">Select</option>
                                                        @foreach ($bank as $row)
                                                            <option value="{{ $row->id }}"
                                                                @if ($vendor->bank_id == $row->id) selected @endif>
                                                                {{ $row->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-6">
                                                    <label for="transectionid">Transection ID</label>
                                                    <input id="transectionid" type="text"
                                                        class="form-control @error('transectionid') is-invalid @enderror"
                                                        name="transectionid"
                                                        value="@if ($vendor->transectionid) {{ $vendor->transectionid }} @endif"
                                                        readonly>

                                                </div>
                                                <!--<div class="form-group col-12 d-flex">-->
                                                @foreach ($bank as $row)
                                                    <div class="form-group col-12 divBank hide"
                                                        id="bankrow_{{ $row->id }}">
                                                        <div class="row">
                                                            <div class="form-group col-4 m-0">
                                                                <p class="m-0"><b>Account holder name</b> :
                                                                    {{ $row->account_holder_name }}</p>
                                                            </div>
                                                            <div class="form-group col-4 m-0">
                                                                <p class="m-0"><b>Account number</b> :
                                                                    {{ $row->account_number }}
                                                                </p>
                                                            </div>
                                                            <div class="form-group col-4 m-0">
                                                                <p class="m-0"><b>Account IBAN</b> :
                                                                    {{ $row->account_iban }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                                <!--</div>-->
                                                <div class="form-group col-4">
                                                    <label for="amount">Amount Deposit</label>
                                                    <div class="input-group">
                                                        <input type="number" min="0" step=".01"
                                                            class="form-control @error('amount') is-invalid @enderror"
                                                            name="amount" value="{{ $vendor->amount }}" readonly>
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                PKR
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-4">
                                                    <label for="amount">Date</label>
                                                    <input type="date"
                                                        class="form-control @error('date') is-invalid @enderror"
                                                        name="date" value="{{ $vendor->transectiondate }}" readonly>
                                                </div>
                                                <div class="form-group col-4">
                                                    <label for="image">Image Proof</label>
                                                    <br />
                                                    <a href="{{ url('uploads/vendor/payment_proof') . '/' . $vendor->proof }}"
                                                        target="_blank">
                                                        <img class="rounded-circle"
                                                            src="{{ url('uploads/vendor/payment_proof') . '/' . $vendor->proof }}"
                                                            width="100" height="100" />
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @if ($vendor->status == '3')
                                                    <div class="col-xs-6 col-sm-12 col-md-6">
                                                        <button id="cancelButton" data-status="-1"
                                                            data-id="{{ $vendor->id }}"
                                                            class="btn btn-block btn-secondary" type="button">Reject
                                                            Payment</button>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                        <button id="actionButton" data-status="4"
                                                            data-id="{{ $vendor->id }}" data-status="-1"
                                                            class="btn btn-block btn-primary" type="button">Approved
                                                            Payment</button>
                                                    </div>
                                                @endif
                                                @if ($vendor->status == '0' || $vendor->status == '-1')
                                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                                        <button id="deleteButton" class="btn btn-block btn-danger"
                                                            type="button">Delete</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
        </section>
    </div>

    {{-- note --}}
    <div id="approvalModel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Admin Remarks</h5>
                    <button type="button" class="close" onclick="approvalModelClose()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="approvalForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control" id="id" name="id" required>
                        <input type="hidden" class="form-control" id="status" name="status" value="2"
                            required>
                        <div id="customerrror"></div>

                        <div class="form-group">
                            <label for="delivery_trackingid">Remark</label>
                            <textarea class="form-control" name="remarks" required></textarea>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary mr-1" type="button"
                                onclick="submitApproval()">Submit</button>
                            <button class="btn btn-secondary" onclick="approvalModelClose()">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $(document).on("click touchstart", "button#deleteButton", function() {
                var id = $(this).data("id");
                swal({
                        title: 'Are you sure?',
                        text: "Once deleted, you will not be able to recover",
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = '{{ url('/admin/vendor/request/delete') }}' + '/' + id;
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                dataType: 'json',
                                data: {
                                    "id": id,
                                    "_token": token,
                                },
                                beforeSend: function() {
                                    $(".loader").show();
                                },
                                complete: function() {
                                    $(".loader").hide();
                                },
                                success: function(response) {
                                    var typeOfResponse = response.type;
                                    var res = response.msg;
                                    if (typeOfResponse == 0) {
                                        swal('Error', res, 'error');
                                    } else if (typeOfResponse == 1) {
                                        swal({
                                                title: 'Success',
                                                text: res,
                                                icon: 'success',
                                                type: 'success',
                                                showCancelButton: false, // There won't be any cancel button
                                                showConfirmButton: true // There won't be any confirm button
                                            })
                                            .then((ok) => {
                                                if (ok) {
                                                    location.reload();
                                                }
                                            });
                                    }
                                }
                            });
                        }
                    });
            });

            $(document).on("click touchstart", "button#actionButton", function() {
                var id = $(this).data("id");
                var status = $(this).data("status");
                swal({
                        title: 'Are you sure?',
                        text: "Once Processed, you will not be able to recover",
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            let url = "{{ url('/admin/vendor/request/status') }}";
                            $.ajax({
                                url: url,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    "id": id,
                                    "status": status,
                                    "_token": token,
                                },
                                beforeSend: function() {
                                    $(".loader").show();
                                },
                                complete: function() {
                                    $(".loader").hide();
                                },
                                success: function(response) {
                                    var typeOfResponse = response.type;
                                    var res = response.msg;
                                    if (typeOfResponse == 0) {
                                        swal('Error', res, 'error');
                                    } else if (typeOfResponse == 1) {
                                        swal({
                                                title: 'Success',
                                                text: res,
                                                icon: 'success',
                                                type: 'success',
                                                showCancelButton: false, // There won't be any cancel button
                                                showConfirmButton: true // There won't be any confirm button
                                            })
                                            .then((ok) => {
                                                if (ok) {
                                                    location.reload();
                                                }
                                            });
                                    }
                                }
                            });
                        }
                    });
            });



            $(document).ready(function() {
                $("#bank_id").change(function(e) {
                    e.preventDefault();
                    val = $(this).val();
                    if (val) {
                        $("div.divBank").addClass("hide");
                        $("div.divBank").removeClass("show")
                        $("div#bankrow_" + val).removeClass("hide")
                        $("div#bankrow_" + val).addClass("show")
                    } else {
                        $("div.divBank").removeClass("show");
                        $("div.divBank").addClass("hide");
                    }
                })

                $("body").on("click touchstart", "button#cancelButton", function() {
                    $("input#id").val($(this).data("id"));
                    $("input#status").val($(this).data("status"));
                    $("#approvalModel").modal("show");
                    // $("#approvalForm")[0].reset()
                });
            });
        });

        function approvalModelClose() {
            $("#approvalModel").modal("hide");
            $("#approvalForm")[0].reset()
        }

        function submitApproval() {
            var myForm = $('form#approvalForm')
            if (!myForm[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                // $myForm.find(':submit').click();
                myForm[0].reportValidity();
                return false;
            }
            let token = "{{ csrf_token() }}";
            let url = "{{ url('/admin/vendor/request/status') }}";
            var form = $('#approvalForm')[0];
            var data = new FormData(form);
            $.ajax({
                enctype: 'multipart/form-data',
                type: "POST",
                url: url,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                beforeSend: function() {
                    $(".loader").show();
                },
                complete: function() {
                    $(".loader").hide();
                },
                success: function(response) {
                    var typeOfResponse = response.type;
                    if (!typeOfResponse) {
                        if (response.validator_error) {
                            let errors = response.errors;
                            $.each(response.errors, function(key, value) {
                                $('#customerrror').append('<div class="alert alert-danger">' +
                                    value + '</div>');
                            });
                        } else {
                            let msg = response.msg;
                            swal('Error', msg, 'error');
                        }
                    } else {
                        if (typeOfResponse == 0) {
                            var res = response.msg;
                            swal('Error', res, 'error');
                        } else if (typeOfResponse == 1) {
                            var res = response.msg;
                            swal({
                                    title: 'Success',
                                    text: res,
                                    icon: 'success',
                                    type: 'success',
                                    showCancelButton: false, // There won't be any cancel button
                                    showConfirmButton: true, // There won't be any confirm button
                                    closeOnClickOutside: false,
                                })
                                .then((ok) => {
                                    if (ok) {
                                        location.reload();
                                    }
                                });
                        }
                    }
                }
            });
        }
    </script>
@endsection
