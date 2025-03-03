@extends('layouts.user')
@section('title')
    Vendor Request || Dasboard
@endsection
@section('style')
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
                                <h4>Vendor Request</h4>
                                <div class="card-header-action">
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('vendor.request.save') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <input type="hidden" class="form-control" name="id" id="id"
                                                value="{{ $vendor->id }}">

                                            <label for="name">Business Name</label>
                                            <input type="text" class="form-control" name="business_name"
                                                id="business_name" value="{{ $data->business_name }}">
                                            @error('business_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="category">Category</label>
                                            <input type="text" class="form-control" name="category" id="category"
                                                value="{{ $data->category }}">
                                            @error('category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="shop_phone">Shop Phone</label>
                                            <input type="text" class="form-control" name="shop_phone" id="shop_phone"
                                                value="{{ $data->shop_phone }}">
                                            @error('shop_phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="mobile_phone">Mobile Phone</label>
                                            <input type="text" class="form-control" name="mobile_phone" id="mobile_phone"
                                                value="{{ $data->mobile_phone }}">
                                            @error('mobile_phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Business Logo</label>
                                            <input type="file" name="business_logo" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" />
                                            <br />
                                            @if ($data->business_logo)
                                                <a href="{{ $data->business_logo }}" target="_blank">
                                                    <img class="rounded-circle" src="{{ $data->business_logo }}"
                                                        width="70px" height="70px" alt="{{ $vendor->user->name }}">
                                                </a>
                                                <input type="hidden" class="form-control" name="business_logo_show"
                                                    id="business_logo_show" value="{{ $data->business_logo }}">
                                            @endif
                                            @error('business_logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Shop Card</label>
                                            <input type="file" name="shop_card" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" />
                                            <br />
                                            @if ($data->shop_card)
                                                <a href="{{ $data->shop_card }}" target="_blank">
                                                    <img class="rounded-circle" src="{{ $data->shop_card }}" width="70px"
                                                        height="70px" alt="{{ $vendor->user->name }}">
                                                </a>
                                                <input type="hidden" class="form-control" name="shop_card_show"
                                                    id="shop_card_show" value="{{ $data->shop_card }}">
                                            @endif
                                            @error('shop_card')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="business_mail">Business Mail</label>
                                            <input type="text" class="form-control" name="business_mail"
                                                id="business_mail" value="{{ $data->business_mail }}">
                                            @error('business_mail')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Owner Image</label>
                                            <input type="file" name="owner_image" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" />
                                            <br />
                                            @if ($data->owner_image)
                                                <a href="{{ $data->owner_image }}" target="_blank">
                                                    <img class="rounded-circle" src="{{ $data->owner_image }}"
                                                        width="70px" height="70px" alt="{{ $vendor->user->name }}">
                                                </a>
                                                <input type="hidden" class="form-control" name="owner_image_show"
                                                    id="owner_image_show" value="{{ $data->owner_image }}">
                                            @endif
                                            @error('owner_image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="business_address">Business Address</label>
                                            <textarea name="business_address" minlength="100" maxlength="5000" class="form-control" id="business_address">{{ $data->business_address }}</textarea>
                                            @error('business_address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="business_withabf">Why did you choose to start your business with
                                                ABF?</label>
                                            <textarea name="business_withabf" minlength="100" maxlength="5000" class="form-control" id="business_withabf"> {{ $data->business_withabf }}</textarea>
                                            @error('business_withabf')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="career_goal">How does this business align with your career
                                                goals?</label>
                                            <textarea name="career_goal" minlength="100" maxlength="5000" class="form-control" id="career_goal">{{ $data->career_goal }}</textarea>
                                            @error('career_goal')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="describe_product">Can you describe the products you plan to sell on
                                                ABF?</label>
                                            <textarea name="describe_product" minlength="100" maxlength="5000" class="form-control" id="describe_product">{{ $data->describe_product }}</textarea>
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
                                                            @if ($data->herbel_product == '1') checked @endif>
                                                        <label class="custom-control-label"
                                                            for="herbel_product_yes">Yes</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio"
                                                            id="herbel_product_no" name="herbel_product" value="0"
                                                            @if ($data->herbel_product == '0') checked @endif>
                                                        <label class="custom-control-label"
                                                            for="herbel_product_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="previous_work">What companies have you worked for in the
                                                past?</label>
                                            <textarea name="previous_work" minlength="100" maxlength="5000" class="form-control" id="previous_work">{{ $data->previous_work }}</textarea>
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
                                                            @if ($data->experience == '1') checked @endif>
                                                        <label class="custom-control-label"
                                                            for="experience_yes">Yes</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio"
                                                            id="experience_no" name="experience" value="0"
                                                            @if ($data->experience == '0') checked @endif>
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
                                                        <input class="custom-control-input" type="radio"
                                                            id="delivery_yes" name="delivery" value="by_abf"
                                                            @if ($data->delivery == 'by_abf') checked @endif>
                                                        <label class="custom-control-label" for="delivery_yes">By
                                                            ABF</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio"
                                                            id="delivery_no" name="delivery" value="by_yourself"
                                                            @if ($data->delivery == 'by_yourself') checked @endif>
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
                                                            @if ($data->market_business == 'social_media') checked @endif>
                                                        <label class="custom-control-label"
                                                            for="market_business_yes">Social
                                                            Media</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio"
                                                            id="market_business_no" name="market_business"
                                                            value="network_marketing"
                                                            @if ($data->market_business == 'network_marketing') checked @endif>
                                                        <label class="custom-control-label"
                                                            for="market_business_no">Network
                                                            Marketing</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="website_link">Website Link</label>
                                            <input type="url" class="form-control" name="website_link"
                                                id="website_link" value="{{ $data->website_link }}">
                                            @error('website_link')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="social_media_link">Social Media Link</label>
                                            <span class="text-danger">*</span>
                                            <input type="url" class="form-control" name="social_media_link"
                                                id="social_media_link" value="{{ $data->social_media_link }}">
                                            @error('social_media_link')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if ($vendor->status == '0')
                                            <div class="form-group col-md-12">
                                                <label for="previous_work">Admin Remarks</label>
                                                <textarea class="form-control" id="previous_work">{{ $vendor->remarks }}</textarea>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <button class="btn btn-block btn-primary"
                                                    type="submit">Re-Submit</button>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
@endsection
