@extends('layouts.admin')
@section('title')
    Admin || Dashboard
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('/bundles/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('/bundles/jquery-selectric/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
@endsection
@section('content')
    <div class="main-content">
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ $ticket->title }}</h4>
                            <div class="card-header-action">
                                <a href="{{ route('admin.ticket.reply', $ticket->id) }}" class="btn btn-primary">Add
                                    Reply</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled list-unstyled-border list-unstyled-noborder">
                                @foreach ($ticket->ticketdetail as $row)
                                    <li class="media">


                                        @if ($row->user_type == 0)
                                            <img alt="{{ $row->user ? $row->user->name : null }}"
                                                class="mr-3 rounded-circle" width="70"
                                                src="{{ $row->user->image ? asset('uploads/user_profile') . '/' . $row->user->image : asset('img/users/users/user-3.png') }}">
                                        @else
                                            <img alt="{{ $row->admin ? $row->admin->name : null }}"
                                                class="mr-3 rounded-circle" width="70"
                                                src="{{ $row->admin->image ? asset('uploads/user_profile') . '/' . $row->admin->image : asset('img/users/users/user-3.png') }}">
                                        @endif

                                        <div class="media-body">
                                            <div class="media-title mb-1">
                                                @if ($row->user_type == 0)
                                                    {{ $row->user ? $row->user->name : null }}
                                                @else
                                                    {{ $row->admin ? $row->admin->name : null }}
                                                @endif
                                            </div>
                                            <div class="text-time">{{ date('d M Y h:i A', strtotime($row->created_at)) }}</div>
                                            <div class="media-description text-muted">
                                                {!! $row->message !!}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/bundles/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <script src="{{ asset('/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
    <script src="{{ asset('/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('/js/page/create-post.js') }}"></script>
@endsection
