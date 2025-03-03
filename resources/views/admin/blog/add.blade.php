@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('/bundles/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('/bundles/jquery-selectric/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Add Blog</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.blog.list') }}" class="btn btn-primary">List Blogs</a>
                            </div>
                        </div>
                        <form action="{{ route('admin.blog.insert') }}" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                @csrf
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="title" id="title"
                                        class="form-control @error('title') is-invalid @enderror" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea name="content" id="content" class="summernote form-control @error('content') is-invalid @enderror" required></textarea>
                                    @error('content')
                                        <div class="d-block invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Thumbnail</label>
                                    <div id="image-preview" class="image-preview">
                                        <label for="image-upload" id="image-label">Choose File</label>
                                        <input type="file" name="image" id="image-upload"
                                            accept="image/png, image/gif, image/jpeg" required />
                                    </div>
                                    @error('image')
                                        <div class="d-block invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Active</label>
                                    <div class="form-row mt-2">
                                        <div class="col-md-12">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="is_active"
                                                    name="is_active" value="1">
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
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/bundles/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
    <script src="{{ asset('/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('/js/page/create-post.js') }}"></script>
@endsection
