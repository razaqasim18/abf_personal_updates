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

    <!-- Start Blogs Grid -->
    <section id="contact-us" class="contact-us sectionOne">
        <div class="container">
            <div class="blogsGrid row">
                @foreach ($blog as $row)
                    <div class="oneBlog col-sm-12 col-md-5 col-lg-5"
                        @if ($row->image) style="background: url({{ asset('uploads/blog') . '/' . $row->image }}) no-repeat center;" @endif>
                        <h4>{{ $row->title }}</h4>
                        <p>
                            {!! CustomHelper::strWordCut($row->content, 200) !!}
                        </p><br/>
                        <a href="{{ route('blog.single', $row->id) }}" class="readMBtn">Read More</a>
                    </div>
                @endforeach
            </div>
            <div>
                <div id="paginationLinks" style="display: flex;">
                    {{ $blog->links() }}
                </div>
            </div>

        </div>
    </section>
    <!--/ End Blogs Grid -->
@endsection

@section('script')
@endsection
