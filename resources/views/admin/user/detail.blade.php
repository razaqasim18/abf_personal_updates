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
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h4>Client Detail</h4>
                                <div class="card-header-action">
                                    <button class="btn btn-sm btn-success" id="addPoints" data-id="{{ $client->id }}"
                                        data-status="1"><i class="fas fa-check"></i> Add Point
                                    </button>
                                    <a href="{{ route('admin.client.list') }}" target="_blank"
                                        class="btn btn-sm btn-primary">
                                        User List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                        <tbody>
                                            <tr>
                                                <th>
                                                    Client Name
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->name }}
                                                </td>
                                                <th>
                                                    Email
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Cnic
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->cnic }}
                                                </td>
                                                <th>
                                                    Phone
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Sponser ID
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->sponserid ? $client->sponserid : '' }}
                                                </td>
                                                <th>
                                                    DOB
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->dob }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    CNIC Front
                                                </th>
                                                <td class="text-center">
                                                    @if ($client->cnic_image_front)
                                                        <a href="{{ asset('uploads/cnic') . '/' . $client->cnic_image_front }}"
                                                            target="_blank">
                                                            <img class="rounded-circle"
                                                                src="{{ asset('uploads/cnic') . '/' . $client->cnic_image_front }}"
                                                                width="70px" height="70px">
                                                        </a>
                                                    @else
                                                        <img class="rounded-circle"
                                                            src="{{ asset('img/products/product-5.png') }}" width="70px"
                                                            height="70px">
                                                    @endif
                                                </td>
                                                <th>
                                                    CNIC Back
                                                </th>

                                                <td class="text-center mr-5">
                                                    @if ($client->cnic_image_back)
                                                        <a href="{{ asset('uploads/cnic') . '/' . $client->cnic_image_back }}"
                                                            target="_blank">
                                                            <img class="rounded-circle"
                                                                src="{{ asset('uploads/cnic') . '/' . $client->cnic_image_back }}"
                                                                width="70px" height="70px">
                                                        </a>
                                                    @else
                                                        <img class="rounded-circle"
                                                            src="{{ asset('img/products/product-5.png') }}" width="70px"
                                                            height="70px">
                                                    @endif
                                                </td>

                                            </tr>
                                            <tr>
                                                <th>
                                                    Client Point
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->userpoint != null ? $client->userpoint->point : '' }}
                                                </td>
                                                <th>
                                                    Client Personal Point
                                                </th>
                                                <td class="text-center">
                                                    @php
                                                        $result = CustomHelper::calculateUserPersonalPoint($client->id);
                                                    @endphp
                                                    {{ $result->count ? $result->count : 0 }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th colspan="2">
                                                    Client Rank
                                                </th>
                                                <td colspan="2">
                                                    {{ $client->userpoint != null ? ($client->userpoint->commission ? $client->userpoint->commission->title : '') : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Client Wallet
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->userwallet != null ? 'PKR ' . $client->userwallet->amount : '' }}
                                                </td>
                                                <th>
                                                    Client Cash Back
                                                </th>
                                                <td class="text-center">
                                                    {{ $client->userwallet != null ? 'PKR ' . $client->userwallet->gift : '' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card">
                            <div class="card-header">
                                <h4>Balance Detail</h4>
                                <div class="card-header-action">
                                    @if ($balance->status == 0)
                                        <button class="btn btn-sm btn-success" id="statusChangeEPIN"
                                            data-id="{{ $balance->id }}" data-status="1" ><i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" id="statusChangeEPIN"
                                            data-id="{{ $balance->id }}" data-status="2">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    @endif

                                    <button class="btn btn-sm btn-danger" id="deleteEPIN" data-id="{{ $balance->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                        <tbody>
                                            <tr>
                                                <th>
                                                    Transection ID
                                                </th>
                                                <td class="text-center">
                                                    {{ $balance->transectionid }}
                                                </td>
                                                <th>
                                                    Transection Data
                                                </th>
                                                <td class="text-center">
                                                    {{date('d M Y h:i A', strtotime($balance->transectiondate)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Amount
                                                </th>
                                                <td class="text-center">
                                                    {{ 'PKR ' . $balance->amount }}
                                                </td>
                                                <th>
                                                    Status
                                                </th>
                                                <td class="text-center">
                                                    @if ($balance->status == 1)
                                                        <div class="badge badge-success">Approved</div>
                                                    @elseif ($balance->status == 2)
                                                        <div class="badge badge-danger">Denied</div>
                                                    @else
                                                        <div class="badge badge-primary">Pending</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Approved At
                                                </th>
                                                <td class="text-center">
                                                    {{date('d M Y h:i A', strtotime($balance->approved_at)) }}
                                                </td>

                                                <th>
                                                    Proof
                                                </th>
                                                <td class="text-left mr-5">
                                                    <a href="{{ asset('uploads/balance_proof') . '/' . $balance->proof }}"
                                                        target="_blank" class="btn btn-md btn-primary">
                                                        <i class="far fa-file-pdf"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> --}}


                    </div>
                </div>
            </div>
        </section>
    </div>

    <div id="pointModel" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">Add Points</h5>
                    <button type="button" class="close" onclick="approvalModelClose()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="pointForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control" id="clientid" name="clientid" required>
                        <div id="customerrror"></div>
                        <div class="form-group row">
                            <label for="points" class="col-sm-3">Add Points</label>
                            <input id="points" type="text"
                                class="col-sm-8 form-control @error('points') is-invalid @enderror" name="points"
                                value="{{ old('points') }}" required>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary mr-1" type="button" onclick="submitApproval()">Submit</button>
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

            $("body").on("click", "button#addPoints", function() {
                $("input#clientid").val($(this).data("id"));
                $("#pointModel").modal("show");
            });

        });

        function approvalModelClose() {
            $("#pointModel").modal("hide");
            $("#pointForm")[0].reset()
        }

        function submitApproval() {
            var myForm = $('form#pointForm')
            if (!myForm[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                // $myForm.find(':submit').click();
                myForm[0].reportValidity();
                return false;
            }
            let token = "{{ csrf_token() }}";
            let url = "{{ url('/admin/client/add/point') }}";
            var form = $('#pointForm')[0];
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
                                    showConfirmButton: true // There won't be any confirm button
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
