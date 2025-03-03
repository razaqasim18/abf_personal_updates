@extends('layouts.auth')
@section('title')
    User Register
@endsection
@section('content')
    
    <section class="section"
        @if (SettingHelper::getSettingValueBySLug('site_register_backgroundimage')) style="background-image:url('{{ SettingHelper::getSettingValueBySLug('site_register_backgroundimage') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_register_backgroundimage')) : '' }}');
         position: relative;
    width: 100%;
    height: 100vh;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    margin: 0; /* Reset margins */
    padding: 0; /* Reset padding */
    overflow: auto; /* Hide overflow */
        " @endif>
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-10 offset-sm-1 col-md-10 offset-md-1 col-lg-10 offset-lg-1 col-xl-10 offset-xl-1">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>User Registration</h4>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="row">

                                    <div class="form-group col-6">
                                        <label for="epin">RG-code <span class="text-danger">*</span></label>
                                        <input id="epin" type="text"
                                            class="form-control @error('epin') is-invalid @enderror" name="epin"
                                            value="{{ old('epin') }}" required autocomplete="epin" autofocus>
                                        @error('epin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="email">Email <span class="text-danger">*</span></label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            value="{{ old('password') }}" required autocomplete="password" autofocus>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="sponsor">Sponsor ID <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    ABF
                                                </div>
                                            </div>
                                            <input type="text"class="form-control" name="sponsor" id="sponsor"
                                                value="{{ old('sponsor') }}" required autocomplete="sponsor" autofocus>
                                        </div>
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
                                            value="{{ old('name') }}" required autocomplete="name" autofocus>
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
                                            value="{{ old('phone') }}" required autocomplete="phone" autofocus>
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
                                            value="{{ old('cnic') }}" required autocomplete="cnic" autofocus>
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
                                            value="{{ old('dob') }}" required autocomplete="dob" autofocus>
                                        @error('dob')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="phone">Cnic Front Image <span class="text-danger">*</span></label>
                                        <input type="file" accept="image/png, image/gif, image/jpeg"
                                            class="form-control" name="cnic_image_front" id="cnic_image_front"
                                            value="{{ old('cnic_image_front') }}"
                                            accept="image/png, image/gif, image/jpeg, image/x-icon" required>
                                        @error('cnic_image_front')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-6">
                                        <label for="phone">Cnic Back Image <span class="text-danger">*</span></label>
                                        <input type="file" accept="image/png, image/gif, image/jpeg"
                                            class="form-control" name="cnic_image_back" id="cnic_image_back"
                                            value="{{ old('cnic_image_back') }}"
                                            accept="image/png, image/gif, image/jpeg, image/x-icon" required>
                                        @error('cnic_image_back')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="termcondition" class="custom-control-input"
                                                tabindex="3" id="termcondition" required>
                                            <label class="custom-control-label" for="termcondition">
                                                I have read and agree to the all <b><a
                                                        href="{{ route('terms.condition') }}">Terms and Conditions.</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-5 text-muted text-center">
                        Already have an account? <a href="{{ route('login') }}">Login Your Account</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
