@extends('layouts.user')
@section('title')
    User || Dasboard
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
                                <h4>Fund Transfer</h4>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success" role="alert">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                                        <form method="POST" action="{{ route('transfer.insert') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row mb-1">
                                                <h4 class="col-6">Balance in Wallet:</h4>
                                                <h2 class="col-6 text-right text-primary">
                                                    {!! CustomHelper::getUserWalletAmountByid(Auth::user()->id) !!}
                                                </h2>
                                            </div>
                                            <div class="form-group">
                                                <label for="user">User</label>
                                                <select class="form-control select2" name="user" id="user">
                                                    <option value="">Select User</option>
                                                    @foreach ($user as $row)
                                                        <option value="{{ $row->id }}">{{ 'ABF-' . $row->id }}
                                                            ({{ $row->name }})</option>
                                                    @endforeach
                                                </select>
                                                @error('user')
                                                    <span class="text-danger" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span><br />
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="amount">Amount Transfer</label>
                                                <div class="input-group">
                                                    <input type="number" min="0" step=".01"
                                                        max="{!! CustomHelper::getUserWalletAmountByid(Auth::user()->id) !!}"
                                                        class="form-control @error('amount') is-invalid @enderror"
                                                        name="amount" value="{{ old('amount') }}" required>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            PKR
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('amount')
                                                    <span class="text-danger" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span><br />
                                                @enderror
                                            </div>
                                            <div class="form-group mt-2 mb-2">
                                                <button type="submit" class="btn btn-primary btn-lg btn-block"
                                                    tabindex="4">
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
