@extends('layouts.user')
@section('title')
    Dasboard
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
                                <h4>Add Balance</h4>
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
                                <form method="POST" action="{{ route('balance.insert') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-3">

                                            @foreach ($businessaccount as $row)
                                                <div style="width:100%; overflow:auto;">
                                                <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" colspan="2">{{ $row->bankname }}
                                                                Business Account</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
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
                                                    </tbody>
                                                </table>
                                                </div>
                                            @endforeach

                                        </div>
                                        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 pt-3">

                                            <div class="form-group">
                                                <label for="bank_id">Bank</label>
                                                <select class="form-control" id="bank_id" name="bank_id">
                                                    <option value="">Select</option>
                                                    @foreach ($bank as $row)
                                                        <option value="{{ $row->id }}"
                                                            @if (old('payment_method_id') == $row->id) selected @endif>
                                                            {{ $row->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('payment_method_id')
                                                    <span class="invalid-feedback  col-sm-12" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="transectionid" >Transection ID</label>
                                                <input id="transectionid" type="text"
                                                    class="form-control @error('transectionid') is-invalid @enderror"
                                                    name="transectionid" value="{{ old('transectionid') }}" required>
                                                @error('transectionid')
                                                    <span class="invalid-feedback  col-sm-12" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
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
                                                    <span class="invalid-feedback col-sm-12" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="amount">Transection Date</label>
                                                <input type="date"
                                                    class="form-control @error('date') is-invalid @enderror"
                                                    name="date" value="{{ old('date') }}" required>
                                                @error('date')
                                                    <span class="invalid-feedback  col-sm-12" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image Proof</label>
                                                <input type="file" accept="image/png, image/gif, image/jpeg, image/jpg"
                                                    class="form-control @error('image') is-invalid @enderror"
                                                    name="image" required>
                                                @error('image')
                                                    <span class="invalid-feedback  col-sm-12" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                                Add Amount To Wallet
                                            </button>
                                        </div>
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
