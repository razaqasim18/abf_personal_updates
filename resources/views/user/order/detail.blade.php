@extends('layouts.user')
@section('title')
    Dashboard
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
                                            <h2>{{ $customize ? 'Customized ' : '' }}Invoice</h2>
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
                                                <td>{{ $row->product }} @if ($row->product_type)
                                                        <b>©</b>
                                                    @endif
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
                                                    <strong>Business Name:</strong>
                                                    {{ $order->vendorDetail->business_name }}<br>
                                                    <strong>Phone:</strong>
                                                    {{ $order->vendorDetail->shop_phone }}<br>
                                                    <strong>Email:</strong>
                                                    {{ $order->vendorDetail->business_mail }}<br>
                                                </address>
                                            </div>
                                        @endif

                                        @if ($order->delivery_trackingid)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Order Tracking ID</div>
                                                <div class="invoice-detail-value">{{ $order->delivery_trackingid }}</div>
                                            </div>
                                        @endif
                                        @if ($order->discount)
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
                                        @if ($order->discount)
                                            <div class="invoice-detail-item">
                                                <div class="invoice-detail-name">Discount</div>
                                                <div class="invoice-detail-value">{{ 'PKR ' . $order->discount }}</div>
                                            </div>
                                        @endif
                                        <div class="invoice-detail-item">
                                            <div class="invoice-detail-name">Shipping</div>
                                            <div class="invoice-detail-value">{{ 'PKR ' . $order->shippingcharges }}</div>
                                        </div>
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
                    <hr>

                    <div class="row" style="display: flex; justify-content: center;">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            @if ($order->status == '0')
                                <button id="statusChange" data-id="{{ $order->id }}" data-status="-1"
                                    data-isvendor="{{ $order->getTable() == 'vendor_orders' ? 1 : 0 }}"
                                    class="btn btn-danger btn-icon icon-left btn-block m-2"><i
                                        class="far fa-times-circle"></i>
                                    Cancel
                                </button>
                            @elseif ($order->status == '-2')
                                <button id="statusChange" data-id="{{ $order->id }}" data-status="4"
                                    data-isvendor="{{ $order->getTable() == 'vendor_orders' ? 1 : 0 }}"
                                    class="btn btn-info btn-icon icon-left btn-block m-2">
                                    Re-Ordered
                                </button>
                            @endif
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-5">
                            @php $isvendor = ($order->getTable() == 'vendor_orders') ? 1 : 0  @endphp
                            <a href="{{ route('order.print.pdf', ['id' => $order->id, 'type' => $isvendor]) }}">
                                <button class="btn btn-warning btn-icon icon-left btn-block  m-2"><i
                                        class="fas fa-print"></i>
                                    Print</button>
                            </a>
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


            $("body").on("click touchstart", "button#statusChange", function() {
                var id = $(this).data("id");
                var status = $(this).data("status");
                var isvendor = $(this).data("isvendor");
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
                            var url = "{{ url('/order/change') }}" + '/' + status + '/' + id + '/' +
                                isvendor;
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
