@extends('layouts.vendor')
@section('title')
    Vendor || Dashboard
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
                                <h4>Business Information</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('vendor.setting.business.update') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <input type="hidden" class="form-control" name="id" id="id"
                                        value="{{ $vendor->id }}">

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="business_name">Business Name</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="business_name"
                                                id="business_name"
                                                value="{{ isset($vendor->business_name) ? $vendor->business_name : '' }}">
                                            @error('business_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="business_mail">Business Email</label>
                                            <span class="text-danger">*</span>
                                            <input type="email" class="form-control" name="business_mail"
                                                id="business_mail"
                                                value="{{ isset($vendor->business_mail) ? $vendor->business_mail : '' }}">
                                            @error('business_mail')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="shop_phone">Shop phone</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="shop_phone" id="shop_phone"
                                                value="{{ isset($vendor->shop_phone) ? $vendor->shop_phone : '' }}">
                                            @error('shop_phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="mobile_phone">Mobile phone</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="mobile_phone" id="mobile_phone"
                                                value="{{ isset($vendor->mobile_phone) ? $vendor->mobile_phone : '' }}">
                                            @error('mobile_phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="website_link">Website Link</label>
                                            <input type="url" class="form-control" name="website_link" id="website_link"
                                                value="{{ isset($vendor->website_link) ? $vendor->website_link : '' }}">
                                            @error('website_link')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="social_media_link">Social Media Link</label>
                                            <input type="url" class="form-control" name="social_media_link"
                                                id="social_media_link"
                                                value="{{ isset($vendor->social_media_link) ? $vendor->social_media_link : '' }}">
                                            @error('social_media_link')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>Business Logo</label>
                                            <input type="file" name="business_logo" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" />
                                            <input type="hidden" name="oldbusiness_logo" class="form-control"
                                                value="@if (!empty($vendor->business_logo)) {{ $vendor->business_logo }} @endif" />

                                            @error('business_logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Shop Card</label>
                                            <input type="file" name="shop_card" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" />
                                            <input type="hidden" name="oldshop_card" class="form-control"
                                                value="@if (!empty($vendor->shop_card)) {{ $vendor->shop_card }} @endif" />
                                            @error('shop_card')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="delivery_charges">Delivery Charges</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" min="0" class="form-control"
                                                name="delivery_charges" id="delivery_charges"
                                                value="{{ isset($vendor->delivery_charges) ? $vendor->delivery_charges : '' }}">
                                            @error('delivery_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="is_order_handle_by_admin">Order Handle By</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-control select" name="is_order_handle_by_admin">
                                                <option value="0" @if($vendor->is_order_handle_by_admin == "0") selected @endif>Self</option>
                                                <option value="1" @if($vendor->is_order_handle_by_admin == "1") selected @endif>Admin</option>
                                            </select>
                                            @error('is_order_handle_by_admin')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="business_address">Business Address</label>
                                            <textarea name="business_address" class="form-control" id="business_address">{{ isset($vendor->business_address) ? $vendor->business_address : '' }}</textarea>
                                        </div>
                                    </div>

                                    <div class="card-footer text-right">
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
