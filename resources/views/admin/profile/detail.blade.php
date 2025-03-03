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
                                <h4>Admin Profile Information</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.profile.update') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="name">Full Name</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="name" id="name"
                                                value="{{ isset($profile->name) ? $profile->name : '' }}" readonly>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label for="email">Email</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="email" id="email"
                                                value="{{ isset($profile->email) ? $profile->email : '' }}" readonly>
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label>Profile</label>
                                            <input type="file" name="image" class="form-control"
                                                accept="image/png, image/gif, image/jpeg, image/jpg" />
                                            <input type="hidden" name="oldimage" class="form-control"
                                                value="@if (!empty($profile->image)) {{ $profile->image }} @endif" />

                                            @error('image')
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
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
@endsection
