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
                                <h4>Add Payment Ledger</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.vendor.paymentledger.list') }}" class="btn btn-primary">Payment
                                        Ledger
                                        List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.vendor.paymentledger.insert') }}">
                                    @csrf
                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="user">User</label>
                                            <select class="form-control select2" name="user" id="user">
                                                <option value="">Select User</option>
                                                @foreach ($user as $row)
                                                    <option value="{{ $row->id }}">{{ 'ABF-' . $row->id }}
                                                        ({{ $row->name }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user')
                                                <span class="text-danger" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span><br />
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-12 col-md-6">
                                            <label for="image" class="col-sm-3">Image Proof</label>
                                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                                class="col-sm-8 form-control @error('image') is-invalid @enderror"
                                                name="image">
                                            @error('image')
                                                <span class="invalid-feedback  col-sm-12" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-12 col-md-6">
                                            <div class="form-group">
                                                <label for="amount">Amount Deposit</label>
                                                <div class="input-group">
                                                    <input type="number" id="amount" min="0" step=".01"
                                                        class="form-control" name="amount" value="" required=""
                                                        readonly>
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
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="form-group col-md-12">
                                            <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" colspan="2">User Payment Account Information
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody id="paymentOutput"></tbody>
                                            </table>
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
    <script>
        $("select#user").on("change", function() {
            var userid = $(this).val();

            // var token = $("meta[name='csrf-token']").attr("content");
            var url = '{{ url('/admin/vendor/paymentledger/get/account/information') }}' + '/' +
                userid;
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                // data: {
                //     "id": id,
                //     "status": status,
                //     "_token": token,
                // },
                beforeSend: function() {
                    $(".loader").show();
                },
                complete: function() {
                    $(".loader").hide();
                },
                success: function(response) {
                    var typeOfResponse = response.type;
                    var res = response.msg;
                    var output = "";
                    if (typeOfResponse == 0) {
                        output += '<tr><td>' + res + '</td></tr>'
                    } else if (typeOfResponse == 1) {
                        var vendor = response.object.vendor;
                        var object = response.object.user;
                        output += '<tr><th>Account Name</th><td class="text-right">' +
                            object.bankName + '</td></tr>';
                        output +=
                            '<tr><th>Account Holder Name</th><td class="text-right">' +
                            object.userAccountHolderName + '</td></tr>';
                        output += '<tr><th>Account Number</th><td class="text-right">' +
                            object.userAccountNumber + '</td></tr>';
                        output += '<tr><th>Account IBAN</th><td class="text-right">' +
                            object.useraccountIBAN + '</td></tr>';
                    }
                    $("#paymentOutput").html(output);
                    $("input#amount").val(vendor);
                }
            });
            $("#approvalModel").modal("show");
        });
    </script>
@endsection
