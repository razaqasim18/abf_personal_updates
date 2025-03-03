@extends('layouts.vendor')
@section('title')
    Vendor || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('bundles/dropzonejs/dropzone.css') }}">
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
                                    <a href="{{ route('vendor.product.list') }}" class="btn btn-primary">
                                        Product List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" id="form" method="POST"
                                    enctype="multipart/form-data" action="{{ route('vendor.product.insert') }}">
                                    @csrf
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
                                                        @if (old('vendor_category_id') == $row->id) selected @endif>
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
                                                        data-id="{{ $row->vendor_category_id }}">
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
                                                value="{{ old('product') }}" required>
                                            @error('product')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="weight">Weight</label>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="weight"
                                                    id="weight" value="{{ old('weight') }}" step="0.001">
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
                                        <div class="form-group col-md-6">
                                            <label for="points">Points</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="points" id="points"
                                                value="{{ old('points') }}" required readonly>
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
                                                    id="price" step=".01" value="{{ old('price') }}"
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
                                                    value="{{ old('purchase_price') }}" required="">
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
                                                    id="stock" value="{{ old('stock') }}" required="">
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
                                                                {{ old('is_stock') == '1' ? 'checked' : '' }}>
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
                                            <span class="text-danger">*</span>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control"
                                                    name="discount" id="discount" value="{{ old('discount') }}"
                                                    required="">
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
                                                                {{ old('is_discount') == '1' ? 'checked' : '' }}>
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
                                                <textarea class="form-control" name="description" id="description" required>{{ old('description') }}</textarea>
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
                                                <input name="image" type="file" class="form-control"
                                                    accept="image/png, image/gif, image/jpeg, image/jpg" />
                                            </div>
                                            @error('image')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product Image</label>
                                                <input id="file" name="file[]" type="file" class="form-control"
                                                    accept="image/png, image/gif, image/jpeg, image/jpg" multiple>
                                            </div>
                                            @error('file')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        {{-- working --}}
                                        {{-- <div class="col-md-12">
                                            <label>Images</label>
                                            <div class="dropzone" id="mydropzone">
                                                <div class="fallback">
                                                    <input name="file" type="file" multiple />
                                                </div>
                                            </div>
                                        </div>
                                          --}}
                                    </div>

                                    {{-- <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label>Is Feature</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox"
                                                            id="is_feature" name="is_feature" value="1"
                                                            {{ old('is_feature') == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_feature">
                                                            Is Feature
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="card-footer text-right">
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                        <button class="btn btn-primary mr-1" id="dropzoneSubmit">Submit</button>
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


            $('select#vendor_sub_category_id option').hide();

            $("select#vendor_category_id").change(function() {
                var val = $(this).val(); // Get the selected value

                // Deselect the selected option in the vendor_sub_category_id select
                $('select#vendor_sub_category_id option:selected').prop('selected', false);

                // Hide all options in vendor_sub_category_id
                $('select#vendor_sub_category_id option').hide();

                // Show only the option with the matching data-id
                $('select#vendor_sub_category_id option[data-id="' + val + '"]').show();

            }).change(); // Trigger the change event on page load



            $("#price").on('input', function() {
                let priceValue = Number($("#price").val());
                let points = (priceValue) ? Math.ceil((priceValue + 1) / 750) : 0;
                $("#points").val(points);
            });
        });
    </script>
@endsection
