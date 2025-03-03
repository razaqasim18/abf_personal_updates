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
                    @if ($user)
                    <div class="card">
                        <div class="card-header">
                            <h4>User Detail</h4>
                            <div class="card-header-action">
                                <a href="#" target="_blank" class="btn btn-sm btn-primary">
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
                    @endif
                    <div class="card">
                        <div class="card-header">
                            <h4>RG Code Detail</h4>
                            <div class="card-header-action">
                                @if ($epin->status == 0)
                                <button class="btn btn-sm btn-success" id="statusChangeEPIN" data-id="{{ $epin->id }}"
                                    data-status="1"><i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" id="statusChangeEPIN" data-id="{{ $epin->id }}"
                                    data-status="2">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif

                                <button class="btn btn-sm btn-danger" id="deleteEPIN" data-id="{{ $epin->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                    <tbody>
                                        @if ($epin->transectionid && $epin->transectiondate)
                                        <tr>
                                            <th>
                                                Transection ID
                                            </th>
                                            <td>
                                                {{ $epin->transectionid }}
                                            </td>
                                            <th>
                                                Transection Data
                                            </th>
                                            <td>
                                                {{ $epin->transectiondate ?date('d M Y h:i A',
                                                strtotime($epin->transectiondate)) : 'NULL' }}
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <th>
                                                Payed By
                                            </th>
                                            <td>
                                                {{ $epin->referred_by ? $epin->referred->name : 'NULL' }}
                                            </td>
                                            <th>
                                                Payed with
                                            </th>
                                            <td>
                                                @if ($epin->referred_payed_by)
                                                Reward
                                                @else
                                                Wallet
                                                @endif

                                            </td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <th>
                                                RG-code
                                            </th>
                                            <td>
                                                {{ $epin->epin ? $epin->epin : 'NULL' }}
                                            </td>
                                            <th>
                                                Allocated to user
                                            </th>
                                            <td>
                                                {{ $epin->allotted_to_user_id ? ($user ? $user->name : '') : 'NULL' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Email
                                            </th>
                                            <td>
                                                {{ $epin->email }}
                                            </td>
                                            <th>
                                                Phone
                                            </th>
                                            <td>
                                                {{ $epin->phone }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                Amount
                                            </th>
                                            <td>
                                                {{ 'PKR ' . $epin->amount }}
                                            </td>
                                            <th>
                                                Status
                                            </th>
                                            <td>
                                                @if ($epin->status == 1)
                                                <div class="badge badge-success">Approved</div>
                                                @elseif ($epin->status == 2)
                                                <div class="badge badge-danger">Denied</div>
                                                @else
                                                <div class="badge badge-primary">Pending</div>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th @if (!$epin->proof) colspan="2" class="text-center" @endif>
                                                Approved At
                                            </th>
                                            <td @if (!$epin->proof) colspan="2" class="text-center" @endif>
                                                {{ $epin->approved_at != null ? date('d M Y h:i A',
                                                strtotime($epin->approved_at)) : 'NULL' }}
                                            </td>
                                            @if ($epin->proof)
                                            <th>
                                                Proof
                                            </th>
                                            <td class="text-left mr-5">
                                                <a href="{{ asset('uploads/epin_proof') . '/' . $epin->proof }}"
                                                    target="_blank" class="btn btn-md btn-primary">
                                                    <i class="far fa-file-pdf"></i>
                                                </a>
                                            </td>
                                            @endif
                                        </tr>
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
                            var url = '{{ url('/admin/request/epin/delete') }}' + '/' + id;
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
                            var url = '{{ url('/admin/request/epin/change/status') }}';
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
