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
                                <h4>Vendor List</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User ID</th>
                                                <th>User Name</th>
                                                <th>Business Name</th>
                                                <th>Business Mail</th>
                                                <th>Category</th>
                                                <th>Shop phone</th>
                                                <th>Status</th>
                                                <th>Manage By</th>
                                                <th>Created at</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($vendor as $row)
                                                <tr>
                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        {{ $row->user->id }}
                                                    </td>
                                                    <td>
                                                        {{ $row->user->name }}
                                                    </td>
                                                    <td>
                                                        {{ $row->business_name }}
                                                    </td>
                                                    <td>
                                                        {{ $row->business_mail }}
                                                    </td>
                                                    <td>
                                                        {{ $row->category }}
                                                    </td>
                                                    <td>
                                                        {{ $row->shop_phone }}
                                                    </td>
                                                    <td>
                                                        @if ($row->is_blocked)
                                                            <button id="statusChange" data-id="{{ $row->id }}"
                                                                data-status="0" class="badge badge-danger">Blocked</button>
                                                        @else
                                                            <button id="statusChange" data-id="{{ $row->id }}"
                                                                data-status="1"
                                                                class="badge badge-secondary">Un-Blocked</button>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">
                                                            {{ $row->is_order_handle_by_admin ? 'Admin' : 'Vendor' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        {{ date('d M Y h:i A', strtotime($row->created_at)) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.vendor.detail', $row->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger" id="delete"
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

            $("#table-1").on("click", "button#delete", function() {
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
                            var url = "{{ url('/admin/vendor/delete') }}" + '/' + id;
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

            $("#table-1").on("click", "button#statusChange", function() {
                var id = $(this).data("id");
                var status = $(this).data("status");
                var text = status == 0 ? "You want to unblocked the vendor" :
                    "You want to block the vendor";
                swal({
                        title: 'Are you sure?',
                        text: text,
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = "{{ url('admin/vendor/status') }}";
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
