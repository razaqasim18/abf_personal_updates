@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <style>
        @media (max-width: 767px) {
            .swal-top {
                position: relative !important;
                top: -170px;

            }
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
                                <h4>Order List</h4>
                                <div class="card-header-action">

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Order No</th>
                                                <th>User Name</th>
                                                <th>Order Shipping Charges</th>
                                                <th>Order Total</th>
                                                <th>Payment Method</th>
                                                <th>Order Status</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($order as $row)
                                                <tr>
                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        {{ $row->order_no }}
                                                    </td>
                                                    <td>
                                                        {{ $row->name }}
                                                    </td>
                                                    <td>
                                                        {{ 'PKR ' . $row->shippingcharges }}
                                                    </td>
                                                    <td>
                                                        {{ 'PKR ' . $row->total_bill }}
                                                    </td>
                                                    <td>
                                                        @if ($row->payment_by == 1)
                                                            Wallet
                                                        @elseif ($row->payment_by == 2)
                                                            Reward
                                                        @else
                                                            Cash
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->status == '0')
                                                            <div class="badge badge-primary">Pending</div>
                                                        @elseif ($row->status == '1')
                                                            <div class="badge badge-info">Processing</div>
                                                        @elseif ($row->status == '2')
                                                            <div class="badge badge-secondary">Approved</div>
                                                        @elseif ($row->status == '3')
                                                            <div class="badge badge-success">Delivered</div>
                                                        @elseif ($row->status == '4')
                                                            <div class="badge badge-warning">Re-Ordered</div>
                                                        @elseif ($row->status == '5')
                                                            <div class="badge badge-info">Re-Approved</div>
                                                        @elseif ($row->status == '6')
                                                            <div class="badge badge-success">Re-Delivered</div>
                                                        @elseif ($row->status == '-1')
                                                            <div class="badge badge-danger">Cancelled</div>
                                                        @elseif ($row->status == '-2')
                                                            <div class="badge badge-dark">Returned</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ date('d M Y h:i A', strtotime($row->created_at)) }}<br><br>
                                                    </td>
                                                    <td>
                                                        <button id="deliveryModal" data-id="{{ $row->id }}" 
                                                            class="btn btn-sm btn-warning">
                                                             <i class="fa fa-paper-plane"></i>
                                                        </button>
                                                        <a href="{{ route('admin.order.detail', $row->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.order.print.pdf', $row->id) }}"
                                                            class="btn btn-sm btn-secondary">
                                                            <i class="fas fa-print"></i>
                                                        </a>
                                                        <button id="deleteButton" data-id="{{ $row->id }}"
                                                            href="{{ route('admin.order.delete', $row->id) }}"
                                                            class="btn btn-sm btn-danger text">
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
                    <h5 class="modal-title" id="myLargeModalLabel">Add Delivery Detail</h5>
                    <button type="button" class="close" onclick="approvalModelClose()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="approvalForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control" id="orderid" name="orderid" required>
                           
                        <div id="customerrror"></div>
                        <div class="form-group row">
                            <label for="delivery_by" class="col-sm-3">Courier Company</label>
                            <input id="delivery_by" type="text"
                                class="col-sm-8 form-control @error('delivery_by') is-invalid @enderror"
                                name="delivery_by" value="{{ old('delivery_by') }}" required>
                        </div>
                        <div class="form-group row">
                            <label for="delivery_trackingid" class="col-sm-3">Order Tracking ID</label>
                            <input id="delivery_trackingid" type="text"
                                class="col-sm-8 form-control @error('delivery_trackingid') is-invalid @enderror"
                                name="delivery_trackingid" value="{{ old('delivery_trackingid') }}" required>
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
        $(document).ready(function() {
            
            $("body").on("click touchstart", "button#deliveryModal", function() {
                $("input#orderid").val($(this).data("id"));
                $("#approvalModel").modal("show");
                // $("#approvalForm")[0].reset()
            });
            
            $("#table-1").on("click", "button#deleteButton", function() {
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
                            var url = '{{ url('/admin/order/delete') }}' + '/' + id;
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
        });
        
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
                let url = "{{ url('/admin/order/change/delivery') }}";
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
                                        showConfirmButton: true, // There won't be any confirm button
                                        closeOnClickOutside: false,
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
        
    </script>
@endsection
