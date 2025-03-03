@extends('layouts.vendor')
@section('title')
    Dashboard
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
                            <h4>Add Service Request</h4>
                            <div class="card-header-action">
                                <a href="{{ route('vendor.ticket.list') }}" class="btn btn-primary">List Service Request</a>
                            </div>
                        </div>
                        <form action="{{ route('vendor.ticket.insert') }}" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                @csrf
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="text" name="subject" id="subject"
                                        class="form-control @error('subject') is-invalid @enderror" required>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Content</label>
                                    <textarea name="content" id="content" class="summernote form-control @error('content') is-invalid @enderror"></textarea>
                                    @error('content')
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
    <script src="{{ asset('/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
    <script src="{{ asset('/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('/js/page/create-post.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#content').summernote();
        });
    </script>
@endsection
