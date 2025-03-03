@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Add Commission</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.commission.list') }}" class="btn btn-primary">Commission List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.commission.update', $commission->id) }}">
                                    @method('put')
                                    @csrf

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="title">Title</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="title" id="title"
                                                value="{{ $commission->title ? $commission->title : old('title') }}"
                                                required>
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="points">Points</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="points" id="points"
                                                value="{{ $commission->points ? $commission->points : old('points') }}"
                                                required>
                                            @error('points')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="profit">Profit
                                                <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" min="0" class="form-control" name="profit"
                                                    id="profit"
                                                    value="{{ $commission->profit ? $commission->profit : old('profit') }}"
                                                    required>
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        %
                                                    </div>
                                                </div>
                                            </div>
                                            @error('profit')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="gift">Gift</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="gift" id="gift"
                                                value="{{ $commission->gift ? $commission->gift : old('gift') }}" required>
                                            @error('gift')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="gift">P2P</label>
                                            <input type="number" class="form-control" name="ptp" id="ptp"
                                                value="{{ $commission->ptp ? $commission->ptp : old('ptp') }}">
                                            @error('ptp')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button class="btn btn-secondary" type="reset">Reset</button>
                                        <button class="btn btn-primary mr-1" type="submit">Submit</button>
                                    </div>
                                </form>
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
