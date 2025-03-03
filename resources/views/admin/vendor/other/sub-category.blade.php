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
                                <h4>Vendor Sub Category List</h4>
                                <div class="card-header-action">
                                    <button id="addModalbutton" type="button" class="btn btn-primary">Add Sub Category</a>
                                </div>
                            </div>
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Category</th>
                                                <th>SubCategory</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($subcategory as $row)
                                                <tr>
                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        {{ $row->category->category }}
                                                    </td>
                                                    <td>
                                                        {{ $row->sub_category }}
                                                    </td>

                                                    <td>
                                                        <button data-id=" {{ $row->id }}"
                                                            data-sub_category=" {{ $row->sub_category }}"
                                                            data-category="{{ $row->vendor_category_id }}" id="editCategory"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="far fa-edit"></i>
                                                            </a>
                                                            <button class="btn btn-sm btn-danger" id="deleteCategory"
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
    <div id="addModal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add Sub Category Detail</h5>
                    <button type="button" class="close" onclick="approvalModelClose()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control" id="categoryid" name="categoryid" required>
                        <div id="customerrror"></div>
                        <div class="form-group row">
                            <label for="category_id" class="col-sm-3">Category <span class="text-danger">*</span></label>

                            <select class="col-sm-8 form-control" name="category_id" id="category_id" required>
                                <option value="">Select</option>
                                @foreach ($category as $row)
                                    <option value="{{ $row->id }}">
                                        {{ $row->category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="sub_category" class="col-sm-3">Sub Category</label>
                            <input id="sub_category" type="text"
                                class="col-sm-8 form-control @error('sub_category') is-invalid @enderror"
                                name="sub_category" value="{{ old('sub_category') }}" required>
                        </div>

                        <div class="text-right">
                            <button class="btn btn-primary mr-1" type="button" onclick="submitApproval()">Submit</button>
                            <button class="btn btn-secondary" type="button" onclick="approvalModelClose()">Close</button>
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
        function submitApproval() {
            var myForm = $('form#addForm')
            if (!myForm[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                // $myForm.find(':submit').click();
                myForm[0].reportValidity();
                return false;
            }
            let token = "{{ csrf_token() }}";
            let url = "{{ url('/admin/vendor/subcategory/save') }}";
            var form = $('#addForm')[0];
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
                                $('#customerrror').append(
                                    '<div class="alert alert-danger">' +
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

        function approvalModelClose() {
            $("#addModal").modal("hide");
            $("#addForm")[0].reset();
        }


        $(document).ready(function() {
            $("#table-1").on("click", "button#deleteCategory", function() {
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
                            var url = '{{ url('/admin/vendor/subcategory/delete') }}' + '/' + id;
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


            $("body").on("click touchstart", "button#editCategory", function() {
                $("#categoryid").val($(this).attr("data-id"));
                $("select#category_id").val($(this).attr("data-category")).trigger('change');
                $("#sub_category").val($(this).attr("data-sub_category"));
                $("#addModalLabel").text("Edit Sub Category");
                $("#addModal").modal("show");
                // $("#approvalForm")[0].reset()
            });

            $("body").on("click touchstart", "button#addModalbutton", function() {
                $("#addModalLabel").text("Add Sub Category");
                $("#addModal").modal("show");
                // $("#approvalForm")[0].reset()
            });


        });
    </script>
@endsection
