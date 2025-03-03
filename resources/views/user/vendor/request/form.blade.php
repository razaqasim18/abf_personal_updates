@extends('layouts.user')
@section('title')
    Vendor Request || Dasboard
@endsection
@section('style')
@endsection
@section('content')
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
                                            <label for="name">Business Name</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="business_name"
                                                id="business_name" required>
                                            @error('business_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="category">Category</label>
                                            <span class="text-danger">*</span>
                                            {{-- <select class="form-control" name="category">
                                                <option value="">Select an option</option>
                                            </select> --}}
                                            <input type="text" class="form-control" name="category" id="category"
                                                required>
                                            @error('category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="shop_phone">Shop Phone</label>

                                            <input type="text" class="form-control" name="shop_phone" id="shop_phone">
                                            @error('shop_phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="mobile_phone">Mobile Phone</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="mobile_phone" id="mobile_phone"
                                                required>
                                            @error('mobile_phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Business Logo</label>
                                            <span class="text-danger">*</span>

                                            <input type="file" name="business_logo" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" required />

                                            @error('business_logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Shop Card</label>
                                            <input type="file" name="shop_card" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" />

                                            @error('shop_card')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="business_mail">Business Mail</label>
                                            <input type="text" class="form-control" name="business_mail"
                                                id="business_mail">
                                            @error('business_mail')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Owner Image</label>
                                            <input type="file" name="owner_image" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" />

                                            @error('owner_image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="business_address">Business Address</label>
                                            <textarea name="business_address" minlength="50" maxlength="500" class="form-control" id="business_address"></textarea>
                                            @error('business_address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label for="business_withabf">Why did you choose to start your business with
                                                ABF?</label>
                                            <textarea name="business_withabf" minlength="50" maxlength="500" class="form-control" id="business_withabf"></textarea>
                                            @error('business_withabf')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="career_goal">How does this business align with your career
                                                goals?</label>
                                            <textarea name="career_goal" minlength="50" maxlength="500" class="form-control" id="career_goal"></textarea>
                                            @error('career_goal')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="describe_product">Can you describe the products you plan to sell on
                                                ABF?</label>
                                            <textarea name="describe_product" minlength="50" maxlength="500" class="form-control" id="describe_product"></textarea>
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
                                                            checked>
                                                        <label class="custom-control-label"
                                                            for="herbel_product_yes">Yes</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio"
                                                            id="herbel_product_no" name="herbel_product" value="0">
                                                        <label class="custom-control-label"
                                                            for="herbel_product_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>





                                        <div class="form-group col-md-6">
                                            <label for="previous_work">What companies have you worked for in the
                                                past?</label>
                                            <textarea name="previous_work" minlength="50" maxlength="500" class="form-control" id="previous_work"></textarea>
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
                                                            id="experience_yes" name="experience" value="1" checked>
                                                        <label class="custom-control-label"
                                                            for="experience_yes">Yes</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio"
                                                            id="experience_no" name="experience" value="0">
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
                                                            id="delivery_yes" name="delivery" value="by_abf" checked>
                                                        <label class="custom-control-label" for="delivery_yes">By
                                                            ABF</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio"
                                                            id="delivery_no" name="delivery" value="by_yourself">
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
                                                            value="social_media" checked>
                                                        <label class="custom-control-label"
                                                            for="market_business_yes">Social
                                                            Media</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio"
                                                            id="market_business_no" name="market_business"
                                                            value="network_marketing">
                                                        <label class="custom-control-label"
                                                            for="market_business_no">Network
                                                            Marketing</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="form-group col-md-6">
                                            <label for="website_link">Website Link</label>
                                            <span class="text-danger">*</span>
                                            <input type="url" class="form-control" name="website_link"
                                                id="website_link" required>
                                            @error('website_link')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="social_media_link">Social Media Link</label>
                                            <span class="text-danger">*</span>
                                            <input type="url" class="form-control" name="social_media_link"
                                                id="social_media_link" required>
                                            @error('social_media_link')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="terms_conditions">Terms and Conditions</label><br />
                                            <b> <label>Please Read <a
                                                        href="{{ route('vendor.terms', ['slug' => 'vendor_terms_condition']) }}"
                                                        target="_blank">Terms and Conditions</a> in
                                                    detail</label></b>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="privacy_policy">Privacy Policy</label><br />
                                            <b> <label>Please Read <a
                                                        href="{{ route('vendor.terms', ['slug' => 'vendor_privacy_policy']) }}"
                                                        target="_blank">Privacy Policy</a>
                                                    in
                                                    detail</label></b>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="notes">Notes</label><br />
                                            {!! SettingHelper::getSettingValueBySLug('vendor_notes') !!}
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label>Have you read and understood all the terms and conditions of business and
                                                the privacy policy on the ABF site?</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" id="agree_yes"
                                                            name="agree" value="1">
                                                        <label class="custom-control-label" for="agree_yes">Yes</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" id="agree_no"
                                                            name="agree" value="0" checked>
                                                        <label class="custom-control-label" for="agree_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card-footer text-right">
                                            <button class="btn btn-secondary" type="reset">Reset</button>
                                            <button class="btn btn-primary mr-1" type="submit">Submit</button>
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
