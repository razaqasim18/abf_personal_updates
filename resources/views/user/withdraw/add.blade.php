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
                                <h4>Request A WithDraw</h4>
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
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                                        <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                            <thead>
                                                <tr>
                                                    <th scope="col" colspan="2">Your Payment Account Information
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($useraccount as $row)
                                                    <tr>
                                                        <th>Bank</th>
                                                        <td class="text-right">{{ $row->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Account Holder Name</th>
                                                        <td class="text-right">{{ $row->account_holder_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Account Number</th>
                                                        <td class="text-right">{{ $row->account_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Account IBAN</th>
                                                        <td class="text-right">{{ $row->account_iban }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td>Your Payment Account Detail Is Missing . Add Your Account Detail
                                                            first To Make WithDraw Request
                                                            <a href="{{ route('payment.information.load') }}">Payment
                                                                Account</a>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pr-0 pt-3">
                                        <form method="POST" action="{{ route('withdraw.insert') }}"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group row mb-1">
                                                <h4 class="col-6">Balance in Wallet:</h4>
                                                <h2 class="col-6 text-right text-primary">
                                                    {!! CustomHelper::getUserWalletAmountByid(Auth::user()->id) !!}
                                                    </h5>
                                            </div>
                                            <div class="form-group">
                                                <label for="amount">Amount Deposit</label>
                                                <div class="input-group">
                                                    <input type="number" min="0" step=".01"
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
                                                <span class="col-sm-12 pl-0 pr-0 mt-2">
                                                    <strong>Amount: (Must be greater then 1000 Rs.)</strong>
                                                </span><br />

                                            </div>
                                            <div class="form-group mt-2 mb-2">
                                                <button type="submit" class="btn btn-primary btn-lg btn-block"
                                                    tabindex="4">
                                                    Submit
                                                </button>
                                            </div>
                                            <span class="col-sm-12 pl-0 pr-0 mt-3 text-danger">
                                                <strong>Note:
                                                    {{ SettingHelper::getSettingValueBySLug('transection_charges') }}%
                                                    Transaction Charges Applied on each transaction.</strong>
                                            </span>
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
