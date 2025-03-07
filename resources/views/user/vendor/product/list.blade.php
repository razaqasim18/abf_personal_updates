@extends('layouts.vendor')
@section('title')
    Vendor || Dashboard
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
                                <h4>Product List</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('vendor.product.add') }}" class="btn btn-primary">Add
                                        Product
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category</th>
                                                <th>Product</th>
                                                <th>Product Price</th>
                                                <th>Product Quantity</th>
                                                <th>Product Points</th>
                                                <th>Image</th>
                                                <th>Display</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($product as $row)
                                                <tr>
                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        {{ $row->vendorcategory->category }}
                                                    </td>
                                                    <td>
                                                        {{ $row->product }}
                                                    </td>
                                                    <td>
                                                        {{ 'PKR ' . $row->price }}
                                                    </td>
                                                    <td>
                                                        {{ $row->stock }}
                                                    </td>
                                                    <td>
                                                        {{ $row->points }}
                                                    </td>
                                                    <td>
                                                        @if ($row->image)
                                                            <img alt="image"
                                                                src="{{ asset('uploads/product') . '/' . $row->image }}"
                                                                class="user-img-radious-style" width="50px">
                                                        @else
                                                            <img alt="image"
                                                                src="{{ asset('img/products/product-1.png') }}"
                                                                class="user-img-radious-style" width="50px">
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->is_active)
                                                            <div class="badge badge-success">Active</div>
                                                        @else
                                                            <div class="badge badge-danger">Inactive</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($row->is_approved == 0)
                                                            <div class="badge badge-primary">Pending</div>
                                                        @elseif($row->is_approved == '1')
                                                            <div class="badge badge-success">Approved</div>
                                                        @else
                                                            <div class="badge badge-danger">Rejected</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('vendor.product.edit', $row->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger" id="deletePaymentMethod"
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
                            var url = "{{ url('/vendor/product/delete') }}" + '/' + id;
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
