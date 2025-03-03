@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Blog List</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.blog.add') }}" class="btn btn-primary">Add Blog</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="save-stage" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Sr#</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Content</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($blogs as $row)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>
                                                    <img class="rounded-circle" width="35" data-toggle="title"
                                                        title=""
                                                        src="{{ asset('uploads/blog') . '/' . $row->image }}" />
                                                </td>
                                                <td>
                                                    <?php $title = Str::of($row->title)->limit(50); ?>
                                                    {{ $title }}
                                                </td>
                                                <td>
                                                    <?php $content = Str::of($row->content)->limit(50); ?>
                                                    {{ $content }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.blog.edit', $row->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="far fa-edit"></i>
                                                    </a>
                                                    <button class="btn btn-sm btn-danger" id="deletButton"
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
        </section>
    </div>
@endsection

@section('script')
    <script src="{{ asset('bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/page/datatables.js') }}"></script>
    <script>
        $("#save-stage").on("click", "button#deletButton", function() {

            var id = $(this).data("id");
            swal({
                    title: 'Are you sure?',
                    text: 'Once deleted, you will not be able to recover this action!',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        var token = $("meta[name='csrf-token']").attr("content");
                        var url = '{{ url('/admin/blog/delete') }}' + '/' + id;
                        $.ajax({
                            url: url,
                            type: 'DELETE',
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
                                var result = jQuery.parseJSON(response);
                                var typeOfResponse = result['type'];
                                var res = result['msg'];
                                console.log(res);
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
                                    }).then((oK) => {
                                        if (oK) {
                                            if (oK) {
                                                location.reload();
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    }
                });
        });
    </script>
@endsection
