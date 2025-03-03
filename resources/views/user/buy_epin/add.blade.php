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
                                <h4>Buy RG-code</h4>
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
                                        <form method="POST" action="{{ route('buy.rgcode.insert') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row mb-1">
                                                <div class="form-group col-12 col-md 6">
                                                    <label for="email">Email</label>
                                                    <input id="email" type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" required>
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-12 col-md 6">
                                                    <label for="phone">Phone</label>
                                                    <input id="phone" type="number"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone" value="{{ old('phone') }}" required>
                                                    @error('phone')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="form-group col-12 p-0">
                                                <label for="amount">Amount Deposit</label>
                                                <div class="input-group">
                                                    <input type="number" min="0" step=".01"
                                                        class="form-control @error('amount') is-invalid @enderror"
                                                        name="amount" value="{!! SettingHelper::getSettingValueBySLug('epin_panel_charges') !!}" required readonly>
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            PKR
                                                        </div>
                                                    </div>
                                                </div>
                                                @error('amount')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="form-group row mb-1">
                                                <div class="form-group col-12 col-md 6">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="referred_payed_by" class="custom-control-input referred_payed_by" tabindex="3"
                                                            id="referred_payed_by_wallet" value="0" {{ old('referred_payed_by')==0 ? 'checked' : '' }}>
                                                        <label class="custom-control-label d-flex" for="referred_payed_by_wallet">
                                                            <span id="walletspan" class="">Payment By Wallet</span>
                                                            &nbsp;&nbsp;
                                                            <h6 id="walletheader" class="text-right text-primary mt-1">
                                                                Amount in Wallet({!! CustomHelper::getUserWalletAmountByid(Auth::user()->id) !!})
                                                            </h6>
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="form-group col-12 col-md 6">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="referred_payed_by" class="custom-control-input referred_payed_by" tabindex="3"
                                                            id="referred_payed_by_reward" value="1" {{ old('referred_payed_by')==1 ? 'checked' : '' }}>
                                                        <label class="custom-control-label d-flex" for="referred_payed_by_reward">
                                                            <span id="rewardspan" class="">Payment By Reward</span>
                                                            &nbsp;&nbsp;
                                                            <h6 id="rewardheader" class="text-right text-primary mt-1">
                                                                Amount in Reward({!! CustomHelper::getUserWalletGiftByid(Auth::user()->id) !!})
                                                            </h6>
                                                        </label>
                                                    </div>
                                                </div>
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
    <script>
        $(document).ready(function() {
        });
    </script>
@endsection
