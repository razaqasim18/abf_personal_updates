@extends('layouts.user')
@section('title')
    User || Dasboard
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
                                <h4>Team View of : {{ 'ABF-' . $user->id }} ({{ $user->name }})</h4>
                                <div class="card-header-action"> </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @forelse ($team as $row)
                                        <div class="col mb-4 mb-lg-0 text-center m-3">
                                            <div class="">
                                                <img alt="image"
                                                    src="{{ $row->image ? asset('uploads/user_profile') . '/' . $row->image : asset('img/users/user-3.png') }}"
                                                    class="rounded-circle" width="100px" height="100px" title=""
                                                    data-original-title="{{ $row->name }}">
                                            </div>
                                            <div class="mt-2 font-weight-bold">
                                                {{ 'ABF-' . $row->id }}<br />
                                                <a
                                                    href="{{ route('team.list', ['id' => \Crypt::encryptString($row->id)]) }}">
                                                    {{ $row->name }}
                                                </a>
                                            </div>
                                            <div class="text-small text-muted">
                                                <span class="text-primary"></span>
                                                Points {{ $row->userpoint ? $row->userpoint->point : 0 }}
                                            </div>
                                        </div>

                                    @empty
                                        <div class="col mb-4 mb-lg-0 text-center m-3">
                                            <div class="">
                                                <img alt="image" src="{{ asset('img/users/user-3.png') }}"
                                                    class="rounded-circle" width="100px" height="100px">
                                            </div>
                                            <div class="mt-2 font-weight-bold">
                                                No User Found<br />

                                            </div>
                                        </div>
                                    @endforelse

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
    <script></script>
@endsection
