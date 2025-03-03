@extends('layouts.user')
@section('title')
    Dashboard
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
                                <h4>Order List</h4>
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
                                                <th>Order No</th>
                                                <th>Order Points</th>
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
                                                        {{ $row->points }}
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
                                                        @php
                                                            $isvendor = $row->getTable() == 'vendor_orders' ? 1 : 0;
                                                        @endphp
                                                        <a href="{{ route('order.detail', ['id' => $row->id, 'type' => $isvendor]) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('order.print.pdf', ['id' => $row->id, 'type' => $isvendor]) }}"
                                                            class="btn btn-sm btn-secondary">
                                                            <i class="fas fa-print"></i>
                                                        </a>
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
            $("#table-1").on("click", "button#deletePaymentMethod", function() {
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
                            var url = '{{ url('/admin/product/delete') }}' + '/' + id;
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
    </script>
@endsection
