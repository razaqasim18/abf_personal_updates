@extends('layouts.auth')
@section('title')
    User Login
@endsection
@section('content')
    <section class="section"
        @if (SettingHelper::getSettingValueBySLug('site_login_backgroundimage')) style="background-image:url('{{ SettingHelper::getSettingValueBySLug('site_login_backgroundimage') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_login_backgroundimage')) : '' }}');
            position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 102vh;
    background-size: cover;
    background-position: center;" @endif>
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>User Login</h4>
                        </div>
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>

                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="float-right">
                                        <a href="{{ route('admin.password.request') }}" class="text-small">
                                            Forgot Password?
                                        </a>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                        Login
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="mt-5 text-muted text-center">
                        Don't have an account? <a href="{{ route('register') }}">Create One</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
