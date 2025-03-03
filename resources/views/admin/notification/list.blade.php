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
                <h2 class="section-title">Notifications</h2>
                <div class="row">
                    <div class="col-12">
                        <div class="activities">

                            @foreach ($notifications as $row)
                                @php 
                                    $resdata = json_decode($row->data);
                                    $style = (isset($resdata->isvendor) && $resdata->isvendor == '1') ? 'bg-warning' : 'bg-primary';
                                @endphp
                                <div class="activity">
                                   <div class="activity-icon {{ $style }} text-white">
                                        @if ($resdata->type == '1')
                                            <i class="fab fa-r-project"></i>
                                        @elseif ($resdata->type == '2')
                                            <i class="far fa-user"></i>
                                        @elseif ($resdata->type == '3')
                                            <i class="fab fa-servicestack"></i>
                                        @elseif ($resdata->type == '4')
                                            <i class="fas fa-shopping-cart"></i>
                                        @else
                                            <i class="fas fa-code"></i>
                                        @endif
                                    </div>
                                    <div class="activity-detail w-100">
                                        <div class="mb-2">
                                            <span
                                                class="text-job">{{ date('d-M-Y h:i A', strtotime($row->created_at)) }}</span>
                                            <span class="bullet"></span>
                                            <a class="text-job" href="{{ url($resdata->link) }}">View</a>
                                            @if (!$row->read_at)
                                                <div class="float-right ">
                                                    <a href="" id="mark-read" data-id="{{ $row->id }}">Mark as
                                                        Read</a>
                                                </div>
                                            @endif

                                        </div>
                                        <p>{{ $resdata->message }} {{ "'" . $resdata->detail . "'" }}</p>
                                    </div>
                                </div>
                            @endforeach
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
            $('#mark-read').click(function() {
                var id = $(this).data('id');
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: "{{ route('vendor.notification.read') }}",
                    type: 'POST',
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
                        // iziToast.success({
                        //     title: 'Success!',
                        //     message: 'Marked As Read',
                        //     position: 'topRight'
                        // });
                        $(this).parent().remove();
                        getUnreadNotification();
                    }
                });
            });
        });
    </script>
@endsection
