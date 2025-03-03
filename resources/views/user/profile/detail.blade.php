@extends('layouts.user')
@section('title')
    Dashboard
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
                                <h4>Profile Information</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('profile.update') }}">
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
                                            <label for="sponserid">Sponsor ID</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="sponserid" id="sponserid"
                                                value="{{ isset($profile->sponserid) ? 'ABF' . $profile->sponserid : 'NULL' }}"
                                                readonly>
                                            @error('sponserid')
                                                <span class="text-danger">{{ $message }}</span>
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
                                            <label for="cnic">Cnic</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="cnic" id="cnic"
                                                value="{{ isset($profile->cnic) ? $profile->cnic : '' }}" required>
                                            @error('cnic')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="dob">DOB</label>
                                            <span class="text-danger">*</span>
                                            <input type="date" class="form-control" name="dob" id="dob"
                                                value="{{ isset($profile->dob) ?date('d M Y h:i A', strtotime($profile->dob)) : '' }}"
                                                required>
                                            @error('dob')
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
                                        <div class="form-group col-md-6">
                                            <label for="city">City</label>
                                            <select class="form-control" id="city_id" name="city_id">
                                                <option value="">Select</option>
                                                @if (isset($profiledetail->city_id))
                                                    @foreach ($city as $row)
                                                        <option value="{{ $row->id }}"
                                                            @if ($profiledetail->city_id == $row->id) selected @endif>
                                                            {{ $row->city }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @foreach ($city as $row)
                                                        <option value="{{ $row->id }}">
                                                            {{ $row->city }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('city_id')
                                                <span class="invalid-feedback  col-sm-12" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="street">Street</label>
                                            <input type="text" class="form-control" name="street" id="street"
                                                value="{{ isset($profiledetail->street) ? $profiledetail->street : '' }}"
                                                required>
                                            @error('street')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="address">Address</label>
                                            <textarea name="address" class="form-control" id="address">{{ isset($profiledetail->address) ? $profiledetail->address : '' }}</textarea>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="shipping_address">Shipping Address</label>
                                            <textarea name="shipping_address" class="form-control" id="shipping_address">{{ isset($profiledetail->shipping_address) ? $profiledetail->shipping_address : '' }}</textarea>
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
