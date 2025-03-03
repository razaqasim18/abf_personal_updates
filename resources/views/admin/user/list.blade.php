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
                                <h4>Client List</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.client.add') }}" class="btn btn-primary">Add
                                        Client
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Client ID</th>
                                                <th>Client Name</th>
                                                <th width="20px">Client Email</th>
                                                <th width="20px">Client Phone</th>
                                                <th width="10px">Client Point</th>
                                                <th width="10px">Client Personal Point</th>
                                                <th>Client Rank</th>
                                                <th>Order Return</th>
                                                <th>Image</th>
                                                <th>Is Vendor</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($user as $row)
                                                <tr>
                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        {{ 'ABF' . $row->id }}
                                                    </td>
                                                    <td>
                                                        {{ $row->name }}
                                                    </td>
                                                    <td width="20px">
                                                        {{ $row->email }}
                                                    </td>
                                                    <td width="20px">
                                                        {{ $row->phone }}
                                                    </td>
                                                    <td>
                                                        {{ $row->userpoint != null ? $row->userpoint->point : 0 }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $result = CustomHelper::calculateUserPersonalPoint(
                                                                $row->id,
                                                            );
                                                        @endphp
                                                        {{ $result->count ? $result->count : 0 }}
                                                    </td>
                                                    <td>
                                                        {{ $row->userpoint != null ? ($row->userpoint->commission ? $row->userpoint->commission->title : null) : null }}
                                                    </td>
                                                    <td>
                                                        {{ $row->order_return }}
                                                    </td>
                                                    <td>
                                                        @if ($row->image)
                                                            <img alt="image"
                                                                src="{{ asset('uploads/user_profile') . '/' . $row->image }}"
                                                                class="user-img-radious-style" width="50px"
                                                                height="50px">
                                                        @else
                                                            <img alt="image" src="{{ asset('img/users/user-3.png') }}"
                                                                class="user-img-radious-style" width="50px"
                                                                height="50px">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->is_blocked)
                                                            <div class="badge badge-danger">Block</div>
                                                        @else
                                                            <div class="badge badge-success">Not Block</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->is_vendor)
                                                            <div class="badge badge-success">Vendor</div>
                                                        @else
                                                            <button class="badge badge-secondary" id="makeVendor"
                                                                data-id="{{ $row->id }}">Not Vendor
                                                            </button>
                                                        @endif
                                                    </td>
                                                    <td style="display: flex">
                                                        <a href="{{ route('admin.client.edit', $row->id) }}"
                                                            class="btn btn-sm btn-primary m-1">
                                                            <i class="far fa-edit"></i>
                                                        </a>

                                                        <a href="{{ route('admin.client.detail', $row->id) }}"
                                                            class="btn btn-sm btn-primary m-1">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                        @if ($row->is_blocked)
                                                            <button id="statusChangeUser"
                                                                class="btn btn-sm btn-success text-color m-1"
                                                                data-id="{{ $row->id }}" data-status="0">
                                                                <i class="fas fa-user-check"></i>
                                                            </button>
                                                        @else
                                                            <button id="statusChangeUser"
                                                                class="btn btn-sm btn-danger text-color m-1"
                                                                data-id="{{ $row->id }}" data-status="1">
                                                                <i class="fas fa-user-lock"></i>
                                                            </button>
                                                        @endif
                                                        <button class="btn btn-sm btn-danger m-1" id="deleteUser"
                                                            data-id="{{ $row->id }}">
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
@endsection
@section('script')
    <script src="{{ asset('bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/page/datatables.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#table-1").on("click", "button#deleteUser", function() {
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
                            var url = '{{ url('/admin/client/delete') }}' + '/' + id;
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
            $("#table-1").on("click", "button#statusChangeUser", function() {
                let id = $(this).data("id");
                let status = $(this).data("status");
                let msg = (status) ? "Your want to block this user" : "Your want to unblock this user"
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
                            var url = '{{ url('/admin/client/block') }}' + '/' + id + "/" + status;
                            $.ajax({
                                url: url,
                                type: 'GET',
                                dataType: 'json',
                                // data: {
                                //     "id": id,
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
            $("#table-1").on("click", "button#makeVendor", function() {
                let id = $(this).data("id");
                let msg = "Your want to allow user to become vendor";
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
                            var url = '{{ url('/admin/client/vendor/allowed/') }}' + '/' + id;
                            $.ajax({
                                url: url,
                                type: 'GET',
                                dataType: 'json',
                                // data: {
                                //     "id": id,
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
