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
                                <h4>Add PSP Reward</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('admin.reward.psp.list') }}" class="btn btn-primary">PSP Reward List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                    action="{{ route('admin.reward.psp.update', $psp->id) }}">
                                    @method('put')
                                    @csrf

                                    @if (session('success'))
                                        <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="title">Title</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="title" id="title"
                                                value="{{ $psp->title ? $psp->title : old('title') }}"
                                                required>
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="points">Points</label>
                                            <span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="points" id="points"
                                                value="{{ $psp->points ? $psp->points : old('members') }}"
                                                required>
                                            @error('points')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="reward">Reward</label>
                                            <span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="reward" id="reward"
                                                value="{{ $psp->reward ? $psp->reward : old('reward') }}" required>
                                            @error('reward')
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
