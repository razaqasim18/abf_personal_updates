@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('/bundles/summernote/summernote-bs4.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('/bundles/jquery-selectric/selectric.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Add Success Stories</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.success.story.list') }}" class="btn btn-primary">List
                                    Success Stories</a>
                            </div>
                        </div>
                        <form action="{{ route('admin.success.story.insert') }}" method="POST"
                            enctype="multipart/form-data">
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                @csrf
                                <div class="form-group">
                                    <label for="user">User</label>
                                    <span class="text-danger">*</span>
                                    <select class="form-control" name="user" id="user" required>
                                        <option value="">Select</option>
                                        @foreach ($user as $row)
                                            <option value="{{ $row->id }}"
                                                @if (old('user') == $row->id) selected @endif>
                                                {{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" id="description" class="summernote form-control @error('description') is-invalid @enderror"
                                        required></textarea>
                                    @error('description')
                                        <div class="d-block invalid-feedback">{{ $message }}</div>
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
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/bundles/summernote/summernote-bs4.js') }}"></script>
    {{-- <script src="{{ asset('/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script> --}}
    <script src="{{ asset('/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
    <script src="{{ asset('/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('/js/page/create-post.js') }}"></script>
@endsection
