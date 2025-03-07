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
                                <h4>Add Category</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.category.list') }}" class="btn btn-primary">Category List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.category.insert') }}">
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
                                            <input type="text" class="form-control" name="category" id="category"
                                                value="{{ old('category') }}" required>
                                            @error('category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <label for="category">Page</label>
                                            <span class="text-danger">*</span>
                                            <select id="page_type" name="page_type" class="form-control">
                                                <option value="0" selected>Shop</option>
                                                <option value="1" selected>Other Brand</option>
                                                <option value="2" selected>Customized</option>
                                            </select>
                                            @error('category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>Active</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="is_active"
                                                            name="is_active" value="1"
                                                            {{ old('is_active') == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_active">
                                                            Is Active
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
@endsection
