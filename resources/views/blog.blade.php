@extends('layouts.eshop')
@section('style')
    <style>
        li.page-item {
            float: left;
        }
    </style>
@endsection
@section('content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs blogsBg d-flex justify-content-center align-items-center" style="">
        <div class="container">
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center align-items-center">
                    <a href="#">News</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <!-- Start Blog Single -->
    <section class="blog-single section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-sm-12 col-12">
                    <div class="blog-single-main">
                        <div class="row">
                            <div class="col-12">
                                @if ($blog->image)
                                    <div class="image">
                                        <img src="{{ asset('uploads/blog') . '/' . $blog->image }}" alt="#" />
                                    </div>
                                @endif
                                <div class="blog-detail">
                                    <h2 class="blog-title">
                                        {{ $blog->title }}
                                    </h2>
                                    <div class="blog-meta">
                                        <span class="author"><a href="#"><i
                                                    class="fa fa-calendar"></i>{{ date('d M Y h:i A', strtotime($blog->created_at)) }}</a></span>
                                    </div>
                                    <div class="content">
                                        {!! $blog->content !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12 col-12">
                    <div class="main-sidebar">
                        <!-- Single Widget -->
                        <div class="single-widget recent-post">
                            <h3 class="title">Recent post</h3>
                            @foreach ($blogs as $row)
                                <!-- Single Post -->
                                <div class="single-post">
                                    @if ($row->image)
                                        <div class="image">
                                            <img src="{{ asset('uploads/blog') . '/' . $row->image }}" alt="#" />
                                        </div>
                                    @endif
                                    <div class="content">
                                        <h5>
                                            <a href="{{ route('blog.single', $row->id) }}">{{ $row->title }}</a>
                                        </h5>
                                        <ul class="comment">
                                            <li>
                                                <i class="fa fa-calendar"
                                                    aria-hidden="true"></i>{{ date('d M Y h:i A', strtotime($row->created_at)) }}
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <!-- End Single Post -->
                            @endforeach
                        </div>
                        <!--/ End Single Widget -->
                        <!-- Single Widget -->
                        <!--/ End Single Widget -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/ End Blog Single -->
@endsection

@section('script')
@endsection
