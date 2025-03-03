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
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h4>Withdraw Detail</h4>
                                <div class="card-header-action">
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
                                                <td>
                                                    {{ $withdraw->transectionid }}
                                                </td>
                                                <th>
                                                    Transection Data
                                                </th>
                                                <td>
                                                    {{ date('d M Y h:i A', strtotime($withdraw->transectiondate)) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Amount
                                                </th>
                                                <td>
                                                    {{ 'PKR ' . $withdraw->amount }}
                                                </td>
                                                <th>
                                                    Status
                                                </th>
                                                <td>
                                                    @if ($withdraw->status == 1)
                                                        <div class="badge badge-success">Approved</div>
                                                    @elseif ($withdraw->status == 2)
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
                                                <td>
                                                    {{ date('d M Y h:i A', strtotime($withdraw->approved_at)) }}
                                                </td>

                                                <th>
                                                    Proof
                                                </th>
                                                <td class="text-left mr-5">
                                                    @if ($withdraw->proof != null)
                                                        <a href="{{ asset('uploads/withdraw_proof') . '/' . $withdraw->proof }}"
                                                            target="_blank" class="btn btn-md btn-primary">
                                                            <i class="far fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>Remarks</h4>
                                <div class="card-header-action">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <textarea name="remark" id="remark" class="form-control" readonly>{{ $withdraw->remarks }}</textarea>
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
@endsection
