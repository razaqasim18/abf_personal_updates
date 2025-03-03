@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h4>User Detail</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.client.detail',$user->id) }}" target="_blank" class="btn btn-sm btn-primary">
                                        User Detail
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                        <tbody>
                                            <tr>
                                                <th>
                                                    User Name
                                                </th>
                                                <td>
                                                    {{ $user->name }}
                                                </td>
                                                <th>
                                                    Email
                                                </th>
                                                <td>
                                                    {{ $user->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Cnic
                                                </th>
                                                <td>
                                                    {{ $user->cnic }}
                                                </td>
                                                <th>
                                                    Phone
                                                </th>
                                                <td>
                                                    {{ $user->phone }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Withdraw Detail</h4>
                                <div class="card-header-action">
                                    @if ($withdraw->status == 0)
                                        <button class="btn btn-sm btn-success" id="approveTransection"
                                            data-id="{{ $withdraw->id }}" data-status="1"
                                            data-userid="{{ $withdraw->user_id }}"
                                            data-amount="{{ $withdraw->requested_amount }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" id="statusChangeEPIN"
                                            data-id="{{ $withdraw->id }}" data-status="2">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    <button class="btn btn-sm btn-danger" id="deleteEPIN" data-id="{{ $withdraw->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                        <tbody>
                                            <tr>
                                                <th>
                                                    Transection ID
                                                </th>
                                                <td>
                                                    {{ $withdraw->transectionid != null ? $withdraw->transectionid : 'NULL' }}
                                                </td>
                                                <th>
                                                    Transection Charges
                                                </th>
                                                <td>
                                                    {{ $withdraw->transectioncharges . ' %' }}
                                                </td>
                                                <th>
                                                    Transection Date
                                                </th>
                                                <td>
                                                    {{ $withdraw->transectiondate != null ?date('d M Y h:i A', strtotime($withdraw->transectiondate)) : 'NULL' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Requested Amount
                                                </th>
                                                <td>
                                                    {{ 'PKR ' . $withdraw->requested_amount }}
                                                </td>
                                                <th>
                                                    Cashout Amount
                                                </th>
                                                <td>
                                                    {{ $withdraw->cashout_amount != null ? 'PKR ' . $withdraw->cashout_amount : 'NULL' }}
                                                </td>
                                                <th>
                                                    Status
                                                </th>
                                                <td>
                                                    @if ($withdraw->status == 1)
                                                        <div class="badge badge-success">Approved</div>
                                                    @elseif ($withdraw->status == 2)
                                                        <div class="badge badge-danger">Denied</div>
                                                    @else
                                                        <div class="badge badge-primary">Pending</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Approved At
                                                </th>
                                                <td>
                                                    {{ $withdraw->approved_at != null ?date('d M Y h:i A', strtotime($withdraw->approved_at)) : 'NULL' }}
                                                </td>
                                                <th>
                                                    Created At
                                                </th>
                                                <td>
                                                    {{date('d M Y h:i A', strtotime($withdraw->created_at)) }}
                                                </td>
                                                <th>
                                                    Proof
                                                </th>
                                                <td class="text-center">
                                                    @if ($withdraw->proof)
                                                        <a href="{{ asset('uploads/withdraw_proof') . '/' . $withdraw->proof }}"
                                                            target="_blank" class="btn btn-md btn-primary">
                                                            <i class="far fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>User Account Detail</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                        <tbody>
                                            @forelse ($useraccount as $row)
                                                <tr>
                                                    <th>Bank</th>
                                                    <td class="text-right">{{ $row->name }}</td>
                                                    <th>Account Holder Name</th>
                                                    <td class="text-right">{{ $row->account_holder_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Account Number</th>
                                                    <td class="text-right">{{ $row->account_number }}</td>
                                                    <th>Account IBAN</th>
                                                    <td class="text-right">{{ $row->account_iban }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td>User Payment Account Detail Is Not Added .
                                                    </td>
                                                </tr>
                                            @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Withdraw Remarks</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                <form id="save-review" method="post"
                                    action={{ route('admin.request.withdraw.remark', $withdraw->id) }}>
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="remark" id="remark" class="form-control" required>{{ $withdraw->remarks }}</textarea>
                                    </div>
                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary" id="save_review">Add Remark</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="approvalModel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Add Customer</h5>
                    <button type="button" class="close" onclick="approvalModelClose()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="approvalForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
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
                        <input type="hidden" class="form-control" id="id" name="id" required>
                        <div id="customerrror"></div>

                        <div class="form-group row">
                            <label for="transectionid" class="col-sm-3">Transection ID</label>
                            <input id="transectionid" type="text"
                                class="col-sm-8 form-control @error('transectionid') is-invalid @enderror"
                                name="transectionid" value="{{ old('transectionid') }}" required>
                            @error('transectionid')
                                <span class="invalid-feedback  col-sm-12" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="amount" class="col-sm-3">Amount Deposit</label>
                            <div class="input-group col-sm-8 p-0">
                                <input type="number" min="0" step=".01"
                                    class="form-control @error('amount') is-invalid @enderror" id="amount"
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
                        <div class="form-group row">
                            <label for="amount" class="col-sm-3">Transection Date</label>
                            <input type="date" class="col-sm-8 form-control @error('date') is-invalid @enderror"
                                name="date" value="{{ old('date') }}" required>
                            @error('date')
                                <span class="invalid-feedback  col-sm-12" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="image" class="col-sm-3">Image Proof</label>
                            <input type="file" accept="image/png, image/gif, image/jpeg"
                                class="col-sm-8 form-control @error('image') is-invalid @enderror" name="image"
                                >
                            @error('image')
                                <span class="invalid-feedback  col-sm-12" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary mr-1" type="button"
                                onclick="submitApproval()">Submit</button>
                            <button class="btn btn-secondary" onclick="approvalModelClose()">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/page/datatables.js') }}"></script>
    <script>
        function approvalModelClose() {
            $("#approvalModel").modal("hide");
            $("#approvalForm")[0].reset()
        }

        function submitApproval() {
            var myForm = $('form#approvalForm')
            if (!myForm[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                // $myForm.find(':submit').click();
                myForm[0].reportValidity();
                return false;
            }
            let token = "{{ csrf_token() }}";
            let url = "{{ url('/admin/request/withdraw/approve') }}";
            var form = $('#approvalForm')[0];
            var data = new FormData(form);
            $.ajax({
                enctype: 'multipart/form-data',
                type: "POST",
                url: url,
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 600000,
                beforeSend: function() {
                    $(".loader").show();
                },
                complete: function() {
                    $(".loader").hide();
                },
                success: function(response) {
                    var typeOfResponse = response.type;
                    if (!typeOfResponse) {
                        if (response.validator_error) {
                            let errors = response.errors;
                            $.each(response.errors, function(key, value) {
                                $('#customerrror').append('<div class="alert alert-danger">' +
                                    value + '</div>');
                            });
                        } else {
                            let msg = response.msg;
                            swal('Error', msg, 'error');
                        }
                    } else {
                        if (typeOfResponse == 0) {
                            var res = response.msg;
                            swal('Error', res, 'error');
                        } else if (typeOfResponse == 1) {
                            var res = response.msg;
                            swal({
                                    title: 'Success',
                                    text: res,
                                    icon: 'success',
                                    type: 'success',
                                    showCancelButton: false, // There won't be any cancel button
                                    showConfirmButton: true // There won't be any confirm button
                                })
                                .then((ok) => {
                                    if (ok) {
                                        location.reload();
                                    }
                                });
                        }
                    }
                }
            });
        }

        $(document).ready(function() {
            $("button#approveTransection").on("click", function() {
                var userid = $(this).data("userid");
                $("input#amount").val($(this).data("amount"));
                $("input#id").val($(this).data("id"));

                // var token = $("meta[name='csrf-token']").attr("content");
                var url = '{{ url('/admin/request/get/user/payment/information') }}' + '/' +
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
                            var object = response.object;
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
                    }
                });
                $("#approvalModel").modal("show");
            });

            $("button#deleteEPIN").on("click", function() {
                var id = $(this).data("id");
                swal({
                        title: 'Are you sure?',
                        text: "Once deleted, you will not be able to recover",
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = '{{ url('/admin/request/withdraw/delete') }}' + '/' + id;
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                dataType: 'json',
                                data: {
                                    "id": id,
                                    "_token": token,
                                },
                                beforeSend: function() {
                                    $(".loader").show();
                                },
                                complete: function() {
                                    $(".loader").hide();
                                },
                                success: function(response) {
                                    var typeOfResponse = response.type;
                                    var res = response.msg;
                                    if (typeOfResponse == 0) {
                                        swal('Error', res, 'error');
                                    } else if (typeOfResponse == 1) {
                                        swal({
                                                title: 'Success',
                                                text: res,
                                                icon: 'success',
                                                type: 'success',
                                                showCancelButton: false, // There won't be any cancel button
                                                showConfirmButton: true // There won't be any confirm button
                                            })
                                            .then((ok) => {
                                                if (ok) {
                                                    location.reload();
                                                }
                                            });
                                    }
                                }
                            });
                        }
                    });
            });

            $("button#statusChangeEPIN").on("click", function() {
                var id = $(this).data("id");
                var status = $(this).data("status");
                var msg = (status == '1') ? "You want to approve this transection" :
                    "You want to disapprove this transection";
                swal({
                        title: 'Are you sure?',
                        text: msg,
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = '{{ url('/admin/request/withdraw/change/status') }}';
                            $.ajax({
                                url: url,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    "id": id,
                                    "status": status,
                                    "_token": token,
                                },
                                beforeSend: function() {
                                    $(".loader").show();
                                },
                                complete: function() {
                                    $(".loader").hide();
                                },
                                success: function(response) {
                                    var typeOfResponse = response.type;
                                    var res = response.msg;
                                    if (typeOfResponse == 0) {
                                        swal('Error', res, 'error');
                                    } else if (typeOfResponse == 1) {
                                        swal({
                                                title: 'Success',
                                                text: res,
                                                icon: 'success',
                                                type: 'success',
                                                showCancelButton: false, // There won't be any cancel button
                                                showConfirmButton: true // There won't be any confirm button
                                            })
                                            .then((ok) => {
                                                if (ok) {
                                                    location.reload();
                                                }
                                            });
                                    }
                                }
                            });
                        }
                    });
            });
        });
    </script>
@endsection
