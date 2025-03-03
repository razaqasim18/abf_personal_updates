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
                                <h4>Site Setting</h4>
                                {{-- <div class="card-header-action">
                                    <a href="{{ route('admin.category.list') }}" class="btn btn-primary">Add Category</a>
                                </div> --}}
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.setting.site.save') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Site Name <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="site_name" id="site_name"
                                                value="{!! SettingHelper::getSettingValueBySLug('site_name') !!}" required>
                                            @error('site_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Site Logo</label>
                                        <div class="col-sm-9">
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="site_logo" id="site_logo"
                                                value="{{ old('site_logo') }}" accept="image/png, image/gif, image/jpeg">
                                            <input type="hidden" name="sitelogoimage"
                                                value="{{ SettingHelper::getSettingValueBySLug('site_logo') }}" />
                                            @error('site_logo')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Site Favicon</label>
                                        <div class="col-sm-9">
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="site_favicon" id="site_favicon"
                                                value="{{ old('site_favicon') }}"
                                                accept="image/png, image/gif, image/jpeg, image/x-icon">
                                            <input type="hidden" name="sitefaviconimage" value="{!! SettingHelper::getSettingValueBySLug('site_favicon') !!}" />
                                            @error('site_favicon')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Site Primary Color</label>
                                        <div class="col-sm-9">
                                            <div class="input-group colorpickerinput">
                                                <input type="text" class="form-control" name="site_primary_color"
                                                    id="site_primary_color" value="{!! SettingHelper::getSettingValueBySLug('site_primary_color') !!}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-fill-drip"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('site_primary_color')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Site Secondary Color</label>
                                        <div class="col-sm-9">
                                            <div class="input-group colorpickerinput">
                                                <input type="text" class="form-control" name="site_secondary_color"
                                                    id="site_secondary_color"
                                                    value="{{ SettingHelper::getSettingValueBySLug('site_secondary_color') }}">
                                                <div class="input-group-append">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-fill-drip"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @error('site_secondary_color')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Catalog</label>
                                        <div class="col-sm-9">
                                            <input type="file"
                                                accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                                class="form-control" name="catalog" id="catalog"
                                                value="{{ old('catalog') }}">
                                            <input type="hidden" name="sitecatalog"
                                                value="{{ SettingHelper::getSettingValueBySLug('catalog') }}" />
                                            @error('catalog')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- customize ticker --}}
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Customized Ticker</label>
                                        <div class="col-sm-9">
                                            <textarea type="text" class="form-control" name="customized_ticker" id="customized_ticker">{!! SettingHelper::getSettingValueBySLug('customized_ticker') !!}</textarea>
                                            @error('customized_ticker')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Site Email</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="site_email" id="site_email"
                                                value="{{ SettingHelper::getSettingValueBySLug('site_email') }}" />
                                            @error('site_email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Site Phone</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="site_phone" id="site_phone"
                                                value="{{ SettingHelper::getSettingValueBySLug('site_phone') }}" />
                                            @error('site_phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Login Background Image</label>
                                        <div class="col-sm-9">
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="site_login" id="site_login"
                                                value="{{ old('site_login') }}"
                                                accept="image/png, image/gif, image/jpeg, image/x-icon">
                                            <input type="hidden" name="siteloginimage"
                                                value="{!! SettingHelper::getSettingValueBySLug('site_login') !!}" />
                                            @error('site_login')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Register Background Image</label>
                                        <div class="col-sm-9">
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="site_register" id="site_register"
                                                value="{{ old('site_register') }}"
                                                accept="image/png, image/gif, image/jpeg, image/x-icon">
                                            <input type="hidden" name="siteregisterimage"
                                                value="{!! SettingHelper::getSettingValueBySLug('site_register') !!}" />
                                            @error('site_register')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Epin Background Image</label>
                                        <div class="col-sm-9">
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="site_epin" id="site_epin"
                                                value="{{ old('site_epin') }}"
                                                accept="image/png, image/gif, image/jpeg, image/x-icon">
                                            <input type="hidden" name="siteepinimage" value="{!! SettingHelper::getSettingValueBySLug('site_epin') !!}" />
                                            @error('site_epin')
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
