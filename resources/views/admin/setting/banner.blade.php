@extends('layouts.admin')
@section('title')
    Admin || Dashboard
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
                                <h4>Website Banner Setting</h4>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.setting.banner.save') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="form-row mb-3">
                                        <div class="form-group col-md-12">
                                            <label>Banner Image</label>
                                            <input id="file" name="file[]" type="file" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" multiple>
                                        </div>
                                        @error('file')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!empty($banner))
                                        <div class="col-md-12">
                                            <div class="row gutters-sm">
                                                @foreach ($banner as $image)
                                                    <div class="col-md-3 col-sm-3 text-center">
                                                        <label class="imagecheck mb-4">
                                                            {{-- <input name="imagecheck" type="checkbox" value="1"
                                                            class="imagecheck-input" /> --}}
                                                            {{-- <span class="imagecheck-figure"> --}}
                                                            <img src="{{ $image->getFirstMediaUrl('images') }}"
                                                                alt="{{ $image->name }}" class="imagecheck-image d-flex"
                                                                width="100px"><br />
                                                            <button type="button" class="btn btn-danger" id="removeImage"
                                                                data-mediaid="{{ $image->id }}"
                                                                data-bannerid="{{ $image->id }}">
                                                                <i class="fas fa-trash"></i> Remove</button>
                                                            {{-- </span> --}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="card-footer text-right">
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Website Shop Banner Setting</h4>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.setting.other.banner.save') }}">
                                    @csrf
                                    @if (session('othersuccess'))
                                        <div class="alert alert-success">{{ session('othersuccess') }}</div>
                                    @endif
                                    @if (session('othererror'))
                                        <div class="alert alert-danger">{{ session('othererror') }}</div>
                                    @endif
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Shop Banners</label>
                                        <div class="col-sm-9">
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="shop_banner" id="shop_banner"
                                                value="{{ old('shop_banner') }}" accept="image/png, image/gif, image/jpeg">
                                            <input type="hidden" name="shopbannerimage"
                                                value="{{ SettingHelper::getSettingValueBySLug('shop_banner') }}" />
                                            @error('shop_banner')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Other Brand Banners</label>
                                        <div class="col-sm-9">
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="other_brand_banner" id="other_brand_banner"
                                                value="{{ old('other_brand_banner') }}"
                                                accept="image/png, image/gif, image/jpeg">
                                            <input type="hidden" name="otherbrandbannerimage"
                                                value="{{ SettingHelper::getSettingValueBySLug('other_brand_banner') }}" />
                                            @error('other_brand_banner')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Customized Banners</label>
                                        <div class="col-sm-9">
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="customize_banner" id="customize_banner"
                                                value="{{ old('customize_banner') }}"
                                                accept="image/png, image/gif, image/jpeg">
                                            <input type="hidden" name="customizebannerimage"
                                                value="{{ SettingHelper::getSettingValueBySLug('customize_banner') }}" />
                                            @error('customize_banner')
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

                        <div class="card">
                            <div class="card-header">
                                <h4>Dashboard Banner Setting</h4>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.setting.banner.dashboard.save') }}">
                                    @csrf
                                    @if (session('dashboardsuccess'))
                                        <div class="alert alert-success">{{ session('dashboardsuccess') }}</div>
                                    @endif
                                    @if (session('dashboardsuccess'))
                                        <div class="alert alert-danger">{{ session('dashboarderror') }}</div>
                                    @endif

                                    <div class="form-row mb-3">
                                        <div class="form-group col-md-12">
                                            <label>Banner Image</label>
                                            <input id="files" name="files[]" type="file" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" multiple>
                                        </div>
                                        @error('files')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!empty($dashboardbanner))
                                        <div class="col-md-12">
                                            <div class="row gutters-sm">
                                                @foreach ($dashboardbanner as $image)
                                                    <div class="col-md-3 col-sm-3 text-center">
                                                        <label class="imagecheck mb-4">
                                                            {{-- <input name="imagecheck" type="checkbox" value="1"
                                                            class="imagecheck-input" /> --}}
                                                            {{-- <span class="imagecheck-figure"> --}}
                                                            <img src="{{ $image->getFirstMediaUrl('images') }}"
                                                                alt="{{ $image->name }}" class="imagecheck-image d-flex"
                                                                width="100px"><br />
                                                            <button type="button" class="btn btn-danger"
                                                                id="removeImage" data-mediaid="{{ $image->id }}"
                                                                data-bannerid="{{ $image->id }}">
                                                                <i class="fas fa-trash"></i> Remove</button>
                                                            {{-- </span> --}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="card-footer text-right">
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- vendor page banner --}}
                        <div class="card">
                            <div class="card-header">
                                <h4>Vendor Page Banner Setting</h4>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.setting.vendor.banner.save') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <div class="form-row mb-3">
                                        <div class="form-group col-md-12">
                                            <label>Banner Image</label>
                                            <input id="file" name="file[]" type="file" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" multiple>
                                        </div>
                                        @error('file')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    @if (!empty($banner))
                                        <div class="col-md-12">
                                            <div class="row gutters-sm">
                                                @foreach ($vendorbanner as $image)
                                                    <div class="col-md-3 col-sm-3 text-center">
                                                        <label class="imagecheck mb-4">
                                                            {{-- <input name="imagecheck" type="checkbox" value="1"
                                                            class="imagecheck-input" /> --}}
                                                            {{-- <span class="imagecheck-figure"> --}}
                                                            <img src="{{ $image->getFirstMediaUrl('images') }}"
                                                                alt="{{ $image->name }}" class="imagecheck-image d-flex"
                                                                width="100px"><br />
                                                            <button type="button" class="btn btn-danger"
                                                                id="removeImage" data-mediaid="{{ $image->id }}"
                                                                data-bannerid="{{ $image->id }}">
                                                                <i class="fas fa-trash"></i> Remove</button>
                                                            {{-- </span> --}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

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
    <script>
        $(document).ready(function() {

            $("body").on("click touchstart", "button#removeImage", function() {
                var mediaid = $(this).data("mediaid");
                var bannerid = $(this).data("bannerid");
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
                            var url = '{{ url('/admin/setting/banner/delete/media') }}' + '/' +
                                bannerid;
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                dataType: 'json',
                                data: {
                                    // "id": id,
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
                                                    // $(this).parent().parent().remove();
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
