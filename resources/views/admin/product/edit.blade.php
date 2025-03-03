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
                                <h4>Add Product</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.product.list') }}" class="btn btn-primary">
                                        Product List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.product.update', $product->id) }}">
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
                                            <label for="brand_id">Brand</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-control" name="brand_id" id="brand_id">
                                                <option value="">Select</option>
                                                @foreach ($brand as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if ($product->brand_id == $row->id) selected @endif>
                                                        {{ $row->brand }}</option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="category_id">Category</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-control" name="category_id" id="category_id">
                                                <option value="">Select</option>
                                                @foreach ($category as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if ($product->category_id == $row->id) selected @endif>
                                                        {{ $row->category }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="product">Product</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="product" id="product"
                                                value="{{ $product->product }}" required>
                                            @error('product')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="points">Points</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="points" id="points"
                                                value="{{ $product->points }}" required>
                                            @error('points')
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
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="weight">Weight</label>
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="weight"
                                                    id="weight" value="{{ $product->weight }}"  step="0.001" required="">
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
                                        <div class="col-md-6">
                                            <label for="description">Description</label>
                                            <input type="text" class="form-control" name="description"
                                                id="description" value="{{ $product->description }}">
                                            @error('description')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
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
                                        <div class="form-group col-md-6">
                                            <label>Active</label>
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

                                        <div class="form-group col-md-6">
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
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label>In Store</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="radio" id="is_other2" name="is_other" value="0" {{
                                                            $product->is_other == 0 ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_other2">
                                                            Is Store
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Is Other</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="radio"
                                                            id="is_other1" name="is_other" value="1"
                                                            {{ $product->is_other == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_other1">
                                                            Is Other
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Is Customized</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="radio" id="is_other" name="is_other" value="2" {{
                                                            $product->is_other == '2' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_other">
                                                            Is Customized
                                                        </label>
                                                    </div>
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
    <script src="{{ asset('bundles/dropzonejs/min/dropzone.min.js') }}"></script>
    <script src="{{ asset('js/page/multiple-upload.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("body").on("click", "button#removeImage", function() {
                var mediaid = $(this).data("mediaid");
                var productid = $(this).data("productid");
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
                            var url = '{{ url("/admin/product/delete/media") }}' + '/' + productid +
                                '/' + mediaid;
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
        // const input = document.querySelector('#file');
        // // Listen for files selection
        // input.addEventListener('change', (e) => {
        //     // Retrieve all files
        //     const files = input.files;
        //     // Check files count
        //     if (files.length > 3) {
        //         swal('Error', 'Only 3 files are allowed to upload.', 'error');
        //         $("#file").val(null);
        //         return false;
        //     }
        // });
        // Initialize Dropzone instance with custom options
        // if (window.Dropzone) {
        //     Dropzone.autoDiscover = false;
        // }
        // Disable auto-discover of dropzone elements

        // var myDropzone = new Dropzone("#mydropzone", {
        //     url: "{{ route('admin.product.insert') }}",
        //     paramName: "file",
        //     maxFilesize: 2, // Max file size in MB
        //     maxFiles: 10, // Max number of files
        //     parallelUploads: 1, // Number of files to upload at once
        //     acceptedFiles: ".png, .jpg, .jpeg, .gif", // Allowed file types
        //     addRemoveLinks: true,

        // });

        // Add event listener for sending event to include additional form data
        // myDropzone.on("sending", function(file, xhr, formData) {
        //     formData.append("_token", "{{ csrf_token() }}");
        //     formData.append("brand_id", $("#brand_id").val());
        //     formData.append("category_id", $("#category_id").val());
        //     formData.append("product", $("#product").val());
        //     formData.append("points", $("#points").val());
        //     formData.append("price", $("#price").val());
        //     formData.append("purchase_price", $("#purchase_price").val());
        //     formData.append("is_active", $("#is_active").val());
        //     formData.append("is_other", $("#is_other").val());
        //     formData.append("is_stock", $("#product").val());
        //     formData.append("is_feature", $("#is_feature").val());
        //     formData.append("description", $("#description").val());
        // });
    </script>
@endsection
