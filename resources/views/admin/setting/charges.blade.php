@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Charges Setting</h4>
                                {{-- <div class="card-header-action">
                                    <a href="{{ route('admin.category.list') }}" class="btn btn-primary">Add Category</a>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.setting.charges.save') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">E-pin Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="epin_charges" id="epin_charges" step=".01"
                                                    value="{!! SettingHelper::getSettingValueBySLug('epin_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>

                                            @error('epin_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">E-pin Panel Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="epin_panel_charges" id="epin_panel_charges" step=".01"
                                                    value="{!! SettingHelper::getSettingValueBySLug('epin_panel_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>

                                            @error('epin_panel_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Transection Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="transection_charges" id="transection_charges"
                                                    value="{!! SettingHelper::getSettingValueBySLug('transection_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>

                                            @error('transection_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">GST Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="gst_charges"
                                                    id="gst_charges" value="{!! SettingHelper::getSettingValueBySLug('gst_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>

                                            @error('gst_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Admin Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="admin_charges" id="admin_charges" value="{!! SettingHelper::getSettingValueBySLug('admin_charges') !!}"
                                                    required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>

                                            @error('admin_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Return Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="return_charges" id="return_charges"
                                                    value="{!! SettingHelper::getSettingValueBySLug('return_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>

                                            @error('return_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Shipping Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="shipping_charges" id="shipping_charges"
                                                    value="{!! SettingHelper::getSettingValueBySLug('shipping_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>

                                            @error('shipping_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Customized Shipping Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="customized_shipping_charges" id="customized_shipping_charges"
                                                    value="{!! SettingHelper::getSettingValueBySLug('customized_shipping_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>

                                            @error('customized_shipping_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Money Rate<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="money_rate" id="money_rate" value="{!! SettingHelper::getSettingValueBySLug('money_rate') !!}"
                                                    required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>
                                            <label>1 point = {!! SettingHelper::getSettingValueBySLug('money_rate') !!} PKR</label>
                                            @error('money_rate')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Coupon Discount<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="coupon_discount" id="coupon_discount"
                                                    value="{!! SettingHelper::getSettingValueBySLug('coupon_discount') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>
                                            @error('coupon_discount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Register Reward<span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="register_reward" id="register_reward"
                                                    value="{!! SettingHelper::getSettingValueBySLug('register_reward') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>
                                            @error('register_reward')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Vendor Registration Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="vendor_registration_charges" id="vendor_registration_charges"
                                                    value="{!! SettingHelper::getSettingValueBySLug('vendor_registration_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>

                                            @error('vendor_registration_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Vendor Order Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="vendor_order_commission" id="vendor_order_commission"
                                                    value="{!! SettingHelper::getSettingValueBySLug('vendor_order_commission') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>

                                            @error('vendor_order_commission')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Vendor Transection Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="vendor_transection_charges" id="vendor_transection_charges"
                                                    value="{!! SettingHelper::getSettingValueBySLug('vendor_transection_charges') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>

                                            @error('vendor_transection_charges')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Vendor Orders <br />By Admin Charges <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="vendor_order_handle_by_admin_comission"
                                                    id="vendor_order_handle_by_admin_comission"
                                                    value="{!! SettingHelper::getSettingValueBySLug('vendor_order_handle_by_admin_comission') !!}" required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>

                                            @error('vendor_order_handle_by_admin_comission')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
    <script src="{{ asset('/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    {{-- <script src="{{ asset('/js/page/forms-advanced-forms.js') }}"></script> --}}
    <script>
        $(".colorpickerinput").colorpicker({
            format: 'hex',
            component: '.input-group-append',
        });
    </script>
@endsection
