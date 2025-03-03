@extends('layouts.user')
@section('title')
    Vendor Payment || Dasboard
@endsection
@section('style')
    <style>
        .hide {
            display: none;
        }

        .show {
            display: block;
        }
    </style>
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Vendor Request Payment</h4>
                                <div class="card-header-action">
                                </div>
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

                                <form method="POST" action="{{ route('vendor.request.payment.save') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" id="id" name="id" value="{{ $vendor->id }}" />
                                        <div class="form-group col-6">
                                            <label for="bank_id">Bank</label>
                                            <select class="form-control" id="bank_id" name="bank_id"
                                                @if ($vendor->bank_id)  @endif>
                                                <option value="">Select</option>
                                                @foreach ($bank as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if (old('bank_id') == $row->id) selected
                                                        @else
                                                            @if ($vendor->bank_id == $row->id)
                                                            selected @endif
                                                        @endif>
                                                        {{ $row->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('bank_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="transectionid">Transection ID</label>
                                            <input id="transectionid" type="text"
                                                class="form-control @error('transectionid') is-invalid @enderror"
                                                name="transectionid"
                                                value="@if ($vendor->transectionid) {{ $vendor->transectionid }}@else{{ old('transectionid') }} @endif"
                                                @if ($vendor->transectionid) @else required @endif>
                                            @error('transectionid')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <!--<div class="form-group col-12 d-flex">-->
                                        @foreach ($bank as $row)
                                            <div class="form-group col-12 divBank hide" id="bankrow_{{ $row->id }}">
                                                <div class="row">
                                                    <div class="form-group col-4 m-0">
                                                        <p class="m-0"><b>Account holder name</b> :
                                                            {{ $row->account_holder_name }}</p>
                                                    </div>
                                                    <div class="form-group col-4 m-0">
                                                        <p class="m-0"><b>Account number</b> :
                                                            {{ $row->account_number }}
                                                        </p>
                                                    </div>
                                                    <div class="form-group col-4 m-0">
                                                        <p class="m-0"><b>Account IBAN</b> : {{ $row->account_iban }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        <!--</div>-->
                                        <div class="form-group col-4">
                                            <label for="amount">Amount Deposit</label>
                                            <div class="input-group">
                                                <input type="number" min="0" step=".01"
                                                    class="form-control @error('amount') is-invalid @enderror"
                                                    name="amount" value="{!! SettingHelper::getSettingValueBySLug('vendor_registration_charges') !!}" required readonly>
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
                                        <div class="form-group col-4">
                                            <label for="amount">Date</label>
                                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                                name="date"
                                                value="@if ($vendor->transectiondate) {{ $vendor->transectiondate }}@else{{ old('date') }} @endif"
                                                @if ($vendor->transectiondate) @else required @endif>
                                            @error('date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-4">
                                            <label for="image">Image Proof</label>
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="form-control @error('image') is-invalid @enderror" name="image"
                                                required>
                                            @if ($vendor->proof)
                                                <br />
                                                <a href="{{ url('uploads/vendor/payment_proof') . '/' . $vendor->proof }}"
                                                    target="_blank">
                                                    <img class="rounded-circle"
                                                        src="{{ url('uploads/vendor/payment_proof') . '/' . $vendor->proof }}"
                                                        width="100" height="100" />
                                                </a>
                                                <input type="hidden" class="form-control" name="payment_proof_show"
                                                    value="{{ url('uploads/vendor/payment_proof') . '/' . $vendor->proof }}">


                                            @endif

                                            @error('image')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    @if ($vendor->status == '-1' || $vendor->status == '2')
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                                Submit
                                            </button>
                                        </div>
                                    @endif

                                </form>
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
            $("#bank_id").change(function(e) {
                e.preventDefault();
                val = $(this).val();
                console.log(val)
                if (val) {
                    $("div.divBank").addClass("hide");
                    $("div.divBank").removeClass("show")
                    $("div#bankrow_" + val).removeClass("hide")
                    $("div#bankrow_" + val).addClass("show")
                } else {
                    $("div.divBank").removeClass("show");
                    $("div.divBank").addClass("hide");
                }
            })
        });
    </script>
@endsection
