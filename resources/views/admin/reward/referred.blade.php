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
                            <h4>Add Referred Reward</h4>
                        </div>
                        <div class="card-body">
                            <form class="form-horizontal form-bordered" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.reward.referred.insert') }}">
                                @csrf
                                @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                    <label class="form-label">Referred Points <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" min="0" class="form-control" name="referred_points" id="referred_points" step=".01"
                                                value="{!! SettingHelper::getSettingValueBySLug('referred_points') !!}" required>
                                        </div>
                                        @error('referred_points')
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
