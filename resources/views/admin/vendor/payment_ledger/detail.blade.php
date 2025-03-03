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
                                <h4>User Detail</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.client.detail', $paymentledger->user->id) }}" target="_blank"
                                        class="btn btn-sm btn-primary">
                                        User Detail
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                        <tbody>
                                            <tr>
                                                <th>
                                                    User Name
                                                </th>
                                                <td>
                                                    {{ $paymentledger->user->name }}
                                                </td>
                                                <th>
                                                    Email
                                                </th>
                                                <td>
                                                    {{ $paymentledger->user->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    Cnic
                                                </th>
                                                <td>
                                                    {{ $paymentledger->user->cnic }}
                                                </td>
                                                <th>
                                                    Phone
                                                </th>
                                                <td>
                                                    {{ $paymentledger->user->phone }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Ledger Detail</h4>
                                <div class="card-header-action">
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Amount
                                                </td>
                                                <td>
                                                    {{ 'PKR ' . $paymentledger->amount }}
                                                </td>
                                                <td>
                                                    Proof
                                                </td>
                                                <td>
                                                    @if ($paymentledger->proof)
                                                        <a href="{{ asset('uploads/payment_ledger') . '/' . $paymentledger->proof }}"
                                                            target="_blank" class="btn btn-md btn-primary">
                                                            <i class="far fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    Date
                                                </td>
                                                <td>
                                                    {{ date('d M Y h:i A', strtotime($paymentledger->created_at)) }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h4>User Account Detail</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" style="border: 2px solid #F5F5F5">
                                        <tbody>
                                            <tr>
                                                <th>Bank</th>
                                                <td class="text-right">
                                                    {{ $paymentledger->user->accountdetail->bank->name }}</td>
                                                <th>Account Holder Name</th>
                                                <td class="text-right">
                                                    {{ $paymentledger->user->accountdetail->account_holder_name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Account Number</th>
                                                <td class="text-right">
                                                    {{ $paymentledger->user->accountdetail->account_number }}</td>
                                                <th>Account IBAN</th>
                                                <td class="text-right">
                                                    {{ $paymentledger->user->accountdetail->account_iban }}</td>
                                            </tr>

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
@endsection
