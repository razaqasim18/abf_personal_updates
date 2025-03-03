@extends('layouts.admin')
@section('title')
    Admin || Dasboard
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
        $data = json_decode($vendor->other_data);
    @endphp
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $vendor->user->name }} Vendor Detail</h4>
                                <div class="card-header-action">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Business Name</label>
                                        <input type="text" class="form-control" name="business_name" id="business_name"
                                            value="{{ $vendor->business_name }}" readonly>
                                        @error('business_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="category">Category</label>
                                        <input type="text" class="form-control" name="category" id="category"
                                            value="{{ $vendor->category }}" readonly>
                                        @error('category')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="shop_phone">Shop Phone</label>
                                        <input type="text" class="form-control" name="shop_phone" id="shop_phone"
                                            value="{{ $vendor->shop_phone }}" readonly>
                                        @error('shop_phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="mobile_phone">Mobile Phone</label>
                                        <input type="text" class="form-control" name="mobile_phone" id="mobile_phone"
                                            value="{{ $vendor->mobile_phone }}" readonly>
                                        @error('mobile_phone')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Business Logo</label>
                                        <br />
                                        @if ($vendor->business_logo)
                                            <a href="{{ $vendor->business_logo }}" target="_blank">
                                                <img class="rounded-circle" src="{{ $vendor->business_logo }}"
                                                    width="70px" height="70px" alt="{{ $vendor->user->name }}">
                                            </a>
                                        @endif
                                        @error('business_logo')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Shop Card</label>
                                        <br />
                                        @if ($vendor->shop_card)
                                            <a href="{{ $vendor->shop_card }}" target="_blank">
                                                <img class="rounded-circle" src="{{ $vendor->shop_card }}" width="70px"
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
                                            value="{{ $vendor->business_mail }}" readonly>
                                        @error('business_mail')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>Owner Image</label>
                                        <br />
                                        @if ($vendor->owner_image)
                                            <a href="{{ $vendor->owner_image }}" target="_blank">
                                                <img class="rounded-circle" src="{{ $vendor->owner_image }}" width="70px"
                                                    height="70px" alt="{{ $vendor->user->name }}">
                                            </a>
                                        @endif
                                        @error('owner_image')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="business_address">Business Address</label>
                                        <textarea name="business_address" minlength="100" maxlength="5000" class="form-control" id="business_address" readonly>{{ $vendor->business_address }}</textarea>
                                        @error('business_address')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    {{-- <div class="form-group col-md-6">
                                        <label for="business_withabf">Why did you choose to start your business with
                                            ABF?</label>
                                        <textarea name="business_withabf" minlength="100" maxlength="5000" class="form-control" id="business_withabf" readonly> {{ $data->business_withabf }}</textarea>
                                        @error('business_withabf')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    {{-- <div class="form-group col-md-6">
                                        <label for="career_goal">How does this business align with your career
                                            goals?</label>
                                        <textarea name="career_goal" minlength="100" maxlength="5000" class="form-control" id="career_goal" readonly>{{ $data->career_goal }}</textarea>
                                        @error('career_goal')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    {{-- <div class="form-group col-md-6">
                                        <label for="describe_product">Can you describe the products you plan to sell on
                                            ABF?</label>
                                        <textarea name="describe_product" minlength="100" maxlength="5000" class="form-control" id="describe_product" readonly>{{ $data->describe_product }}</textarea>
                                        @error('describe_product')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    {{-- <div class="form-group col-md-6">
                                        <label>Are you considering selling herbal items on our site?</label>
                                        <div class="form-row mt-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio"
                                                        id="herbel_product_yes" name="herbel_product" value="1"
                                                        @if ($data->herbel_product == '1') checked @endif disabled>
                                                    <label class="custom-control-label" for="herbel_product_yes">Yes</label>
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
                                    </div> --}}

                                    {{-- <div class="form-group col-md-6">
                                        <label for="previous_work">What companies have you worked for in the
                                            past?</label>
                                        <textarea name="previous_work" minlength="100" maxlength="5000" class="form-control" id="previous_work" readonly>{{ $data->previous_work }}</textarea>
                                        @error('previous_work')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div> --}}

                                    {{-- <div class="form-group col-md-6">
                                        <label>Do you have any work experience?</label>
                                        <div class="form-row mt-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="experience_yes"
                                                        name="experience" value="1"
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
                                    </div> --}}

                                    {{-- <div class="form-group col-md-6">
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
                                    </div> --}}

                                    {{-- <div class="form-group col-md-6">
                                        <label>How do you intend to market your business on ABF?</label>
                                        <div class="form-row mt-2">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio"
                                                        id="market_business_yes" name="market_business" value="Social Media"
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
                                    </div> --}}

                                    <div class="form-group col-md-6">
                                        <label for="website_link">Website Link</label>
                                        <input type="url" class="form-control" name="website_link" id="website_link"
                                            value="{{ $vendor->website_link }}" readonly>
                                        @error('website_link')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="social_media_link">Social Media Link</label>
                                        <span class="text-danger">*</span>
                                        <input type="url" class="form-control" name="social_media_link"
                                            id="social_media_link" value="{{ $vendor->social_media_link }}" readonly>
                                        @error('social_media_link')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-xs-6 col-sm-12 col-md-6">
                                        <button id="statusChange" data-id="{{ $vendor->id }}" data-status="1"
                                            class="btn btn-block btn-danger" type="button"
                                            @if ($vendor->is_blocked) disabled @endif>Blocked Vendor</button>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                        <button id="statusChange" data-id="{{ $vendor->id }}" data-status="0"
                                            class="btn btn-block btn-primary" type="button"
                                            @if (!$vendor->is_blocked) disabled @endif>Un-Blocked Vendor</button>
                                    </div>
                                </div>
                                <br />
                                <div class="form-row">
                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <button id="deleteButton" class="btn btn-block btn-danger"
                                            type="button">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
        </section>
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
                            var url = '{{ url('/admin/vendor/delete') }}' + '/' + id;
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

            $(document).on("click touchstart", "button#statusChange", function() {
                var id = $(this).data("id");
                var status = $(this).data("status");
                var text = status == 0 ? "You want to unblocked the vendor" :
                    "You want to block the vendor";
                swal({
                        title: 'Are you sure?',
                        text: text,
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = "{{ url('admin/vendor/status') }}";
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
        });
    </script>
@endsection
