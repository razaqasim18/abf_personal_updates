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
                                <h4>Balance History</h4>
                                <div class="card-header-action">
                                    <div class="card-header-action">
                                        <a href="{{ route('balance.add') }}" class="btn btn-primary">Add Balance</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-1" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Requested Amount</th>
                                                <th>Cashout Amount</th>
                                                <th>Status</th>
                                                <th>Created At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; @endphp
                                            @foreach ($withdraw as $row)
                                                <tr>
                                                    <td>
                                                        {{ $i++ }}
                                                    </td>
                                                    <td>
                                                        {{ 'PKR ' . $row->requested_amount }}
                                                    </td>
                                                    <td>
                                                        {{ $row->cashout_amount ? 'PKR ' . $row->cashout_amount : 'NULL' }}
                                                    </td>
                                                    <td>
                                                        @if ($row->status == 1)
                                                            <div class="badge badge-success">Approved</div>
                                                        @elseif ($row->status == 2)
                                                            <div class="badge badge-danger">Denied</div>
                                                        @else
                                                            <div class="badge badge-primary">Pending</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $row->created_at ?date('d M Y h:i A', strtotime($row->created_at)) : '' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('withdraw.detail', $row->id) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="far fa-eye"></i>
                                                        </a>
                                                        @if ($row->proof != null)
                                                            <a href="{{ asset('uploads/withdraw_proof' . '/' . $row->proof) }}"
                                                                class="btn btn-sm btn-primary">
                                                                <i class="far fa-file-pdf"></i>
                                                            </a>
                                                        @endif
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
@endsection
