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
                                <h4>Edit Business Account</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.business.account.list') }}" class="btn btn-primary">
                                        Business Account List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.business.account.update', $busienssaccount->id) }}">
                                    @csrf
                                    @method('PUT')
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="account_name">Bank</label>
                                            <span class="text-danger">*</span>
                                            <select class="form-control" name="bank" id="bank">
                                                <option value="">Select</option>
                                                @foreach ($bank as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if ($busienssaccount->bank_id == $row->id) selected @endif>
                                                        {{ $row->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('bank')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="account_holder_name">Account Holder Name</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="account_holder_name"
                                                id="account_holder_name" value="{{ $busienssaccount->account_holder_name }}"
                                                required>
                                            @error('account_holder_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="account_number">Account Number</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="account_number"
                                                id="account_number" value="{{ $busienssaccount->account_number }}" required>
                                            @error('account_number')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="account_iban">Account IBAN</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="account_iban" id="account_iban"
                                                value="{{ $busienssaccount->account_iban }}" required>
                                            @error('account_iban')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <div class="form-group col-md-6">
                                            <label>Active</label>
                                            <div class="form-row mt-2">
                                                <div class="col-md-12">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" id="is_active"
                                                            name="is_active" value="1"
                                                            {{ $busienssaccount->is_active == '1' ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_active">
                                                            Is Active
                                                        </label>
                                                    </div>
                                                </div>

                                            </div>
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
