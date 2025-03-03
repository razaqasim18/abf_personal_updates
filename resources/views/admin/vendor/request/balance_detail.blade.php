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
                                    {{-- <a href="#" target="_blank" class="btn btn-sm btn-primary">
                                        User Detail
                                    </a> --}}
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
                                <h4>Balance Detail</h4>
                                <div class="card-header-action">
                                    @if ($balance->status == 0)
                                        <button class="btn btn-sm btn-success" id="statusChangeEPIN"
                                            data-id="{{ $balance->id }}" data-status="1"><i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" id="statusChangeEPIN"
                                            data-id="{{ $balance->id }}" data-status="2">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    <button class="btn btn-sm btn-danger" id="deleteEPIN" data-id="{{ $balance->id }}">
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
                                                    {{ $balance->transectionid }}
                                                </td>
                                                <th>
                                                    Transection Data
                                                </th>
                                                <td>
                                                    {{ date('d M Y h:i A', strtotime($balance->transectiondate)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Amount
                                                </th>
                                                <td>
                                                    {{ 'PKR ' . $balance->amount }}
                                                </td>
                                                <th>
                                                    Status
                                                </th>
                                                <td>
                                                    @if ($balance->status == 1)
                                                        <div class="badge badge-success">Approved</div>
                                                    @elseif ($balance->status == 2)
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
                                                    {{ date('d M Y h:i A', strtotime($balance->approved_at)) }}
                                                </td>

                                                <th>
                                                    Proof
                                                </th>
                                                <td class="text-left mr-5">
                                                    <a href="{{ asset('uploads/balance_proof') . '/' . $balance->proof }}"
                                                        target="_blank" class="btn btn-md btn-primary">
                                                        <i class="far fa-file-pdf"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Balance Remarks</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                <form id="save-review" method="post"
                                    action={{ route('admin.vendor.request.balance.remark', $balance->id) }}>
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="remark" id="remark" class="form-control" required>{{ $balance->remarks }}</textarea>
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
@endsection
@section('script')
    <script src="{{ asset('bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/page/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
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
                            var url = "{{ url('/admin/vendor/request/balance/delete') }}" + '/' + id;
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
                            var url = "{{ url('/admin/vendor/request/balance/change/status') }}";
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
