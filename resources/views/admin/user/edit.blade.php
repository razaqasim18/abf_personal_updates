@extends('layouts.admin')
@section('title')
    Admin || Dashboard
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
                                <h4>Edit Client</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.client.list') }}" class="btn btn-primary">
                                        Client List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (session('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('admin.client.update', $client->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">

                                        <div class="form-group col-6">
                                            <label for="email">Email <span class="text-danger">*</span></label>
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ $client->email }}" required readonly>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="password">Password</label>
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                value="{{ old('password') }}" autocomplete="password" autofocus>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="sponsor">Sponsor ID <span class="text-danger">*</span></label>
                                            <select class="form-control" name="sponsor" id="sponsor">
                                                <option value="">Select</option>
                                                @foreach ($user as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if ($client->sponserid == $row->id) selected @endif>
                                                        {{ 'ABF ' . $row->id }}</option>
                                                @endforeach
                                            </select>
                                            @error('sponsor')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="name">Full Name <span class="text-danger">*</span></label>
                                            <input id="name" type="text"
                                                class="form-control @error('name') is-invalid @enderror" name="name"
                                                value="{{ $client->name }}" required autocomplete="name" autofocus>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="phone">Phone No <span class="text-danger">*</span></label>
                                            <input id="phone" type="number"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="{{ $client->phone }}" required autocomplete="phone" autofocus>
                                            @error('phone')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="cnic">CNIC No <span class="text-danger">*</span></label>
                                            <input id="cnic" type="number"
                                                class="form-control @error('cnic') is-invalid @enderror" name="cnic"
                                                value="{{ $client->cnic }}" required autocomplete="cnic" autofocus>
                                            @error('cnic')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="dob">Date of birth <span class="text-danger">*</span></label>
                                            <input id="dob" type="date"
                                                class="form-control @error('dob') is-invalid @enderror" name="dob"
                                                value="{{ $client->dob }}" required autocomplete="dob" autofocus>
                                            @error('dob')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="phone">Cnic Front Image <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="cnic_image_front" id="cnic_image_front"
                                                value="{{ old('cnic_image_front') }}"
                                                accept="image/png, image/gif, image/jpeg, image/x-icon">

                                            <input id="cnic_image_front_showimage" type="hidden" class="form-control"
                                                name="cnic_image_front_showimage"
                                                value="{{ $client->cnic_image_front }}">
                                            @error('cnic_image_front')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-6">
                                            <label for="phone">Cnic Back Image <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control" name="cnic_image_back" id="cnic_image_back"
                                                value="{{ old('cnic_image_back') }}"
                                                accept="image/png, image/gif, image/jpeg, image/x-icon">
                                            <input id="cnic_image_back_showimage" type="hidden" class="form-control"
                                                name="cnic_image_back_showimage" value="{{ $client->cnic_image_back }}">
                                            @error('cnic_image_back')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

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
@endsection
