@extends('layouts.vendor')
@section('title')
    Vendor || Dashboard
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
                <div class="invoice">
                    <div class="invoice-print">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="invoice-title">
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 col-md-6">
                                            @php
                                                $customize = false;
                                                foreach ($order->orderDetail as $row) {
                                                    if ($row->product_type) {
                                                        $customize = true;
                                                    }
                                                }
                                            @endphp
                                            <h2>Vendor Invoice</h2>
                                        </div>
                                        <div class="col-sm-12 col-xs-12 col-md-6">
                                            <h3 class="float-right">Order #{{ $order->order_no }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Billed To:</strong><br>
                                            {{ $order->orderShippingDetail->name }}<br>
                                            {{ $order->orderShippingDetail->email }},<br>
                                            {{ $order->orderShippingDetail->phone }}<br>
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>Shipped To:</strong><br>
                                            {{ $order->orderShippingDetail->address }}<br>
                                            {{ $order->orderShippingDetail->street }}<br>
                                            {{ $order->orderShippingDetail->city->city }}<br>
                                            {{ $order->orderShippingDetail->other_information }}<br>
                                        </address>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <address>
                                            <strong>Payment Method:</strong><br>
                                            @if ($order->payment_by == 1)
                                                Wallet
                                            @elseif ($order->payment_by == 2)
                                                Reward
                                            @else
                                                Cash
                                            @endif
                                            <br>
                                        </address>
                                        <address>
                                            <strong>Manage By:</strong><br>
                                            @if ($order->is_order_handle_by_admin == 1)
                                                Admin
                                            @else
                                                Vendor
                                            @endif
                                            <br>
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-md-right">
                                        <address>
                                            <strong>Order Date:</strong><br>
                                            {{ date('d M Y h:i A', strtotime($order->created_at)) }}<br><br>
                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="section-title">Order Summary</div>
                                <p class="section-lead">All items here cannot be deleted.</p>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-md">
                                        <tr>
                                            <th data-width="40">#</th>
                                            <th>Item</th>
                                            <th class="text-center">Point</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-right">Totals</th>
                                        </tr>
                                        @php $i = 1; @endphp
                                        @foreach ($order->orderDetail as $row)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $row->product }}
                                                    <b><i class="fab fa-viacoin"></i></b>
                                                </td>
                                                <td class="text-center">{{ $row->points }}</td>
                                                <td class="text-center">{{ $row->quantity }}</td>
                                                <td class="text-right"> {{ 'PKR ' . $row->price }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th class="text-center">{{ $order->points }}</th>
                                            @php
                                                $totalQuantity = 0;
                                                foreach ($order->orderDetail as $row) {
                                                    $totalQuantity += $row->quantity;
                                                }
                                            @endphp
                                            <th class="text-center">{{ $totalQuantity }}</th>
                                            <th></th>
                                        </tr>
                                    </table>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-lg-8">
                                        @if ($order->getTable() == 'vendor_orders')
                                            <div class="invoice-detail-item">
                                                <strong class="section-title">Vendor Detail</strong>
                                                <address>
                                                    <strong>Vendor Name:</strong>
                                                    {{ $order->vendorDetail->user->name }}
                                                    {{ '(ABF-' . $order->vendorDetail->user->id . ')' }}<br>
                                                    <strong>Business Name:</strong>
                                                    {{ $order->vendorDetail->business_name }}<br>
                                                    <strong>Email:</strong>
                                                    {{ $order->vendorDetail->business_mail }}<br>
                                                </address>
                                            </div>
                                        @endif
                                        @if ($order->delivery_trackingid && $order->discount)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Order Tracking ID</div>
                                                <div class="invoice-detail-value">{{ $order->delivery_trackingid }}</div>
                                            </div>
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Courier Company</div>
                                                <div class="invoice-detail-value">{{ $order->delivery_by }}</div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-4 text-right">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Subtotal</div>
                                            <div class="invoice-detail-value">{{ 'PKR ' . $order->subtotal }}</div>
                                        </div>
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Shipping</div>
                                            <div class="invoice-detail-value">{{ 'PKR ' . $order->shippingcharges }}</div>
                                        </div>
                                        @if ($order->discount)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Discount</div>
                                                <div class="invoice-detail-value">{{ 'PKR ' . $order->discount }}</div>
                                            </div>
                                        @endif
                                        <hr class="mt-2 mb-2">
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Total</div>
                                            <div class="invoice-detail-value invoice-detail-value-lg">
                                                {{ 'PKR ' . $order->total_bill }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<hr>-->
                    <!--<div class="text-md-right">-->
                    <!--    <div class="float-lg-left mb-lg-0 mb-3" style="display:flex;">-->


                    <!--    </div>-->
                    <!--</div>-->
                    @if (!$order->is_order_handle_by_admin)
                        <div class="row" style="display: flex; justify-content: center;">
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                @if ($order->status == '0')
                                    <button id="statusChange" data-id="{{ $order->id }}" data-status="1"
                                        class="btn btn-info btn-icon icon-left btn-block m-2">
                                        Processing
                                    </button>
                                @elseif ($order->status == '1')
                                    <button id="deliveryModal" data-id="{{ $order->id }}" data-status="2"
                                        class="btn btn-warning btn-icon icon-left btn-block m-2">
                                        Approved
                                    </button>
                                    <button id="statusChange" data-id="{{ $order->id }}" data-status="-1"
                                        class="btn btn-danger btn-icon icon-left btn-block m-2">
                                        Cancel
                                    </button>
                                @elseif ($order->status == '2')
                                    <button id="statusChange" data-id="{{ $order->id }}" data-status="3"
                                        class="btn btn-success btn-icon icon-left btn-block m-2">
                                        Delivered
                                    </button>
                                    <button id="statusChange" data-id="{{ $order->id }}" data-status="-2"
                                        class="btn btn-dark btn-icon icon-left btn-block m-2">
                                        Returned
                                    </button>
                                @elseif ($order->status == '4')
                                    <button id="deliveryModal" data-id="{{ $order->id }}" data-status="5"
                                        class="btn btn-warning btn-icon icon-left btn-block m-2">
                                        Re-Approved
                                    </button>
                                    <button id="statusChange" data-id="{{ $order->id }}" data-status="-1"
                                        class="btn btn-danger btn-icon icon-left btn-block m-2">
                                        Cancel
                                    </button>
                                @elseif ($order->status == '5')
                                    <button id="statusChange" data-id="{{ $order->id }}" data-status="6"
                                        class="btn btn-success btn-icon icon-left btn-block m-2">
                                        Re-Delivered
                                    </button>
                                    <button id="statusChange" data-id="{{ $order->id }}" data-status="-2"
                                        class="btn btn-dark btn-icon icon-left btn-block m-2">
                                        Returned
                                    </button>
                                @endif
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <button id="deleteButton" data-id="{{ $order->id }}"
                                    class="btn btn-danger btn-icon icon-left btn-block m-2">Delete</button>

                                <a href="{{ route('vendor.order.print.pdf', $order->id) }}">
                                    <button class="btn btn-warning btn-icon icon-left btn-block m-2"><i
                                            class="fas fa-print"></i> Print</button>
                                </a>
                            </div>
                        </div>
                    @endif
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
                        <input type="hidden" class="form-control" id="status" name="status" value="2"
                            required>
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
    <script>
        $(document).ready(function() {

            $("body").on("click touchstart", "button#deleteButton", handleClickOrTouchdeleteButton);

            function handleClickOrTouchdeleteButton(event) {
                var id = $(this).data("id");
                swal({
                        title: 'Are you sure?',
                        text: "Once deleted, you will not be able to recover",
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                        closeOnClickOutside: false,
                        className: "swal-top",
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = "{{ url('/vendor/order/delete') }}" + '/' + id;
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
                                                showConfirmButton: true, // There won't be any confirm button
                                                closeOnClickOutside: false,
                                            })
                                            .then((ok) => {
                                                if (ok) {
                                                    // location.reload();
                                                    window.location.href =
                                                        "{{ url('vendor/order') }}";
                                                }
                                            });
                                    }
                                }
                            });
                        }
                    });
            }

            $("body").on("click touchstart", "button#statusChange", handleClickOrTouchstatusChange);


            function handleClickOrTouchstatusChange(event) {
                var id = $(this).data("id");
                var status = $(this).data("status");
                swal({
                        title: 'Are you sure?',
                        text: "Once Continue, you will not be able to recover",
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                        closeOnClickOutside: false,
                        className: "swal-top",
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            var token = $("meta[name='csrf-token']").attr("content");
                            var url = "{{ url('/vendor/order/change') }}" + '/' + status + '/' + id;
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
                            });
                        }
                    });
            }

            $("body").on("click touchstart", "button#deliveryModal", function() {
                $("input#orderid").val($(this).data("id"));
                $("input#status").val($(this).data("status"));
                $("#approvalModel").modal("show");
                // $("#approvalForm")[0].reset()
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
            let url = "{{ url('/vendor/order/approve') }}";
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
