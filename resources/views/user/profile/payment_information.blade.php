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
                                <h4>Payment Information</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('payment.information.update') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="bank_id">Bank</label>
                                            <select class="form-control" id="bank_id" name="bank_id" required>
                                                <option value="">Select</option>
                                                @foreach ($bank as $row)
                                                    @if (isset($userpaymentinformation->bank_id))
                                                        <option value="{{ $row->id }}"
                                                            @if ($userpaymentinformation->bank_id == $row->id) selected @endif>
                                                            {{ $row->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $row->id }}"
                                                            @if (old('bank_id') == $row->id) selected @endif>
                                                            {{ $row->name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            @error('payment_method_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="account_holder_name">Account Holder Name</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="account_holder_name"
                                                id="account_holder_name"
                                                value="{{ isset($userpaymentinformation->account_holder_name) ? $userpaymentinformation->account_holder_name : '' }}"
                                                required>
                                            @error('account_holder_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="account_number">Account Number</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="account_number"
                                                id="account_number"
                                                value="{{ isset($userpaymentinformation->account_number) ? $userpaymentinformation->account_number : '' }}"
                                                required>
                                            @error('account_number')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="account_iban">Account IBAN</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="account_iban" id="account_iban"
                                                value="{{ isset($userpaymentinformation->account_iban) ? $userpaymentinformation->account_iban : '' }}"
                                                required>
                                            @error('account_iban')
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
