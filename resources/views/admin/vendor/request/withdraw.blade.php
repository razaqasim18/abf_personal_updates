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
                        <div class="card">
                            <div class="card-header">
                                <h4>Withdraw Request List</h4>
                                <div class="card-header-action">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Requested Amount</th>
                                                <th>User Name</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($withdraw as $row)
                                                <tr>
                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        {{ 'PKR ' . $row->requested_amount }}
                                                    </td>
                                                    <td>
                                                        {{ $row->name }}
                                                    </td>
                                                    <td>
                                                        {{ $row->email }}
                                                    </td>
                                                    <td>
                                                        @if ($row->status == 1)
                                                            <div class="badge badge-success">Approved</div>
                                                        @elseif ($row->status == 2)
                                                            <div class="badge badge-danger">Disapproved</div>
                                                        @else
                                                            <div class="badge badge-primary">Pending</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ date('d M Y h:i A', strtotime($row->created_at)) }}
                                                    </td>
                                                    <td>
                                                        @if ($row->status == 0)
                                                            <button class="btn btn-sm btn-success" id="approveTransection"
                                                                data-id="{{ $row->withdrawsid }}" data-status="1"
                                                                data-userid="{{ $row->userid }}"
                                                                data-amount="{{ $row->requested_amount }}">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-warning" id="statusChangeEPIN"
                                                                data-id="{{ $row->withdrawsid }}" data-status="2">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        @endif
                                                        <a href="{{ route('admin.vendor.request.withdraw.detail', $row->withdrawsid) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger" id="deleteEPIN"
                                                            data-id="{{ $row->withdrawsid }}">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
                                    class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount"
                                    value="{{ old('amount') }}" required>
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
                                class="col-sm-8 form-control @error('image') is-invalid @enderror" name="image">
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
            let url = "{{ url('/admin/vendor/request/withdraw/approve') }}";
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
            $("#table-1").on("click", "button#approveTransection", function() {
                var userid = $(this).data("userid");
                $("input#amount").val($(this).data("amount"));
                $("input#id").val($(this).data("id"));

                // var token = $("meta[name='csrf-token']").attr("content");
                var url = "{{ url('/admin/request/get/user/payment/information') }}" + '/' +
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

            $("#table-1").on("click", "button#deleteEPIN", function() {
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
                            var url = "{{ url('/admin/vendor/request/withdraw/delete') }}" + '/' + id;
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

            $("#table-1").on("click", "button#statusChangeEPIN", function() {
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
                            var url = "{{ url('/admin/vendor/request/withdraw/change/status') }}";
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
