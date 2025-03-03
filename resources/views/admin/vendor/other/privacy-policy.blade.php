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
                            <h4>Privacy & Policy</h4>
                        </div>
                        <form action="{{ route('admin.vendor.other.privacy-policy.save') }}" method="POST"
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
                                    {{-- <label>Description</label> --}}
                                    <textarea name="description" id="description" class="summernote form-control @error('description') is-invalid @enderror"
                                        required>
@if ($response)
{!! $response->setting_value !!}
@endif
</textarea>
                                    @error('description')
                                        <div class="d-block invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-secondary" id="resetBtn" type="button">Reset</button>
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
    <script>
        $(document).ready(function() {
            // Initialize CKEditor
            // Initialize Summernote
            $('#description').summernote({
                height: "150px"
            });

            // Attach click event handler to the reset button
            $('#resetBtn').click(function() {
                // Get the CKEditor instance and reset it
                //         var markupStr = 'hello world';
                //         $sumNote.reset();
                // $("#editor").empty();
                //         $('#editor').summernote('code', markupStr);
                console.log("sss");
                $('#description').summernote('code', ''); // Change 'editor' with your textarea id
            });
        });
    </script>
@endsection
