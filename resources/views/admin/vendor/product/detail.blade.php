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
                                <h4>Detail Product </h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.vendor.product.list') }}" class="btn btn-primary">
                                        Product List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('vendor.product.update', $product->id) }}">
                                    @csrf
                                    @method('PUT')
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    
                                     <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="category">Category</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-control" name="vendor_category_id" id="vendor_category_id">
                                                <option value="">Select</option>
                                                @foreach ($category as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if ($product->vendor_category_id == $row->id) selected @endif>
                                                        {{ $row->category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('vendor_category_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @error('category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="vendor_sub_category_id">Sub Category</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-control" name="vendor_sub_category_id"
                                                id="vendor_sub_category_id" required>
                                                <option value="">Select</option>
                                                @foreach ($subcategory as $row)
                                                    <option value="{{ $row->id }}"
                                                        data-id="{{ $row->vendor_category_id }}"
                                                        @if ($product->vendor_sub_category_id == $row->id) selected @endif>
                                                        {{ $row->sub_category }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('vendor_category_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="form-row">
                                         <div class="form-group col-md-12">
                                            <label for="product">Product</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="product" id="product"
                                                value="{{ $product->product }}" required>
                                            @error('product')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>    
                                    </div>

                                    <div class="form-row">

                                      
                                        <div class="form-group col-md-6">
                                            <label for="points">Points</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="points" id="points"
                                                value="{{ $product->points }}" required>
                                            @error('points')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="weight">Weight</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="weight"
                                                    id="weight" value="{{ $product->weight }}" step="0.001"
                                                    required="">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        Kg
                                                    </div>
                                                </div>
                                            </div>
                                            @error('weight')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="price">Price</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="price"
                                                    id="price" step=".01" value="{{ $product->price }}"
                                                    required="">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>
                                            @error('price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="purchase_price">Purchase Price</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="purchase_price" id="purchase_price" step=".01"
                                                    value="{{ $product->purchase_price }}" required="">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        PKR
                                                    </div>
                                                </div>
                                            </div>
                                            @error('purchase_price')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="stock">Stock</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="stock"
                                                    id="stock" value="{{ $product->stock }}" required="">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        Unit
                                                    </div>
                                                </div>
                                            </div>
                                            @error('stock')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="form-group mt-2 mb-0">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox"
                                                                id="is_stock" name="is_stock" value="1"
                                                                {{ $product->in_stock == '1' ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_stock">
                                                                In Stock
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="discount">Discount</label>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="discount" id="discount" value="{{ $product->discount }}">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>
                                            @error('discount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            <div class="form-group mt-2 mb-0">
                                                <div class="form-row">
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox"
                                                                id="is_discount" name="is_discount" value="1"
                                                                {{ $product->is_discount == '1' ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_discount">
                                                                In Discount
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row mb-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" name="description" id="description" required>{{ $product->description }}</textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product Feature Image</label>
                                                <input id="file" type="file" name="image" class="form-control"
                                                    accept="image/png, image/gif, image/jpeg, image/jpg" />
                                                <input type="hidden" name="oldimage" class="form-control"
                                                    value="@if (!empty($product->image)) {{ $product->image }} @endif" />

                                                @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product Image</label>
                                                <input name="file[]" type="file" class="form-control"
                                                    accept="image/png, image/gif, image/jpeg, image/jpg" multiple>
                                            </div>
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        @if ($product->getMedia('images'))
                                            <div class="col-md-12">
                                                <div class="row gutters-sm">
                                                    @foreach ($product->getMedia('images') as $image)
                                                        <div class="col-md-3 col-sm-3 text-center">
                                                            <label class="imagecheck mb-4">
                                                                {{-- <input name="imagecheck" type="checkbox" value="1"
                                                            class="imagecheck-input" /> --}}
                                                                {{-- <span class="imagecheck-figure"> --}}
                                                                <img src="{{ $image->getUrl() }}"
                                                                    alt="{{ $image->name }}"
                                                                    class="imagecheck-image d-flex" width="100px"><br />
                                                                <button type="button" class="btn btn-danger"
                                                                    id="removeImage" data-productid="{{ $product->id }}"
                                                                    data-mediaid="{{ $image->id }}">
                                                                    <i class="fas fa-trash"></i> Remove</button>
                                                                {{-- </span> --}}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="form-row">
                                        @if ($product->is_approved == '1')
                                            <div class="form-group col-md-6">
                                                <label>Display</label>
                                                <div class="form-row mt-2">
                                                    <div class="col-md-12">
                                                        <div class="custom-control custom-checkbox">
                                                            <input class="custom-control-input" type="checkbox"
                                                                id="is_active" name="is_active" value="1"
                                                                {{ $product->is_active == '1' ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_active">
                                                                Is Active
                                                            </label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif


                                        {{-- <div class="form-group col-md-6">
                                            <label>Is Feature</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox"
                                                            id="is_feature" name="is_feature" value="1"
                                                            {{ $product->is_feature == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_feature">
                                                            Is Feature
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div> --}}
                                    </div>


                                    <div class="row">
                                        @if ($product->is_approved == '0')
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <button id="cancelButton" data-status="-1" data-id="{{ $product->id }}"
                                                    class="btn btn-block btn-secondary" type="button">Reject
                                                    Product</button>
                                            </div>
                                            <div class="col-xs-12 col-sm-12 col-md-6">
                                                <button id="actionButton" data-status="1" data-id="{{ $product->id }}"
                                                    data-status="-1" class="btn btn-block btn-primary"
                                                    type="button">Approved
                                                    Product</button>
                                            </div>
                                        @endif

                                        @if ($product->is_approved == '1')
                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <button data-id="{{ $product->id }}" id="deleteButton"
                                                    class="btn btn-block btn-danger" type="button">Delete</button>
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
    <script src="{{ asset('bundles/dropzonejs/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/page/multiple-upload.js') }}"></script>
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
                            var url = "{{ url('/admin/vendor/product/delete') }}" + '/' + id;
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
                                                    var locationReady =
                                                        "{{ url('/admin/vendor/product') }}"; // Ensure this is correctly processed by the templating engine
                                                    window.location.href =
                                                        locationReady;
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
                            let url = "{{ url('/admin/vendor/product/status') }}";
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


            $("body").on("click touchstart", "button#cancelButton", function() {
                $("input#id").val($(this).data("id"));
                $("input#status").val($(this).data("status"));
                $("#approvalModel").modal("show");
                // $("#approvalForm")[0].reset()
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
            let url = "{{ url('/admin/vendor/product/status') }}";
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
