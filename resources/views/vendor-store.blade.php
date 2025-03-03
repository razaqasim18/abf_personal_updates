@extends('layouts.eshop')
@section('style')
    <!-- Glidejs css files -->
    <link rel="stylesheet" href="{{ asset('eshop/css/glidecss/glide.core.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('eshop/css/glidecss/glide.theme.min.css') }}" />
    <style type="text/css">
        .box {
            width: 600px;
            margin: 0 auto;
        }

        li.page-item {
            float: left;
        }

        .shop .nice-select {
            width: 100% !important;
        }

        .colstyle {
            margin: 1%;
            border-radius: 4px;
            padding: 5%;
            box-shadow: 0px 0px 6px 0px #f6931d;
        }

        .height-37 {
            height: 37px;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('eshop/css/product_comment.css') }}">
@endsection

@section('content')

    @if (count($banner))
        <!-- Slider Area -->
        <section class="hero-slider" style="height: auto;">
            <!-- Single Slider -->
            <div class="single-slider">
                <div class="glide">
                    <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            @foreach ($banner as $image)
                                <li class="glide__slide">
                                    <img src="{{ $image->getFirstMediaUrl('images') }}" alt="{{ $image->name }}" />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ End Single Slider -->
        </section>
        <!--/ End Slider Area -->
    @else
        <!--else Slider Area -->
        <section class="hero-slider" style="height: auto;">
            <!-- Single Slider -->
            <div class="single-slider forcustom" style="height: 350px;">
                <div class="container">
                    <div class="row no-gutters">
                        <div class="col-lg-9 offset-lg-3 col-12">
                            <div class="text-inner">
                                {{--  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ End Single Slider -->
        </section>
        <!--else End Slider Area -->
    @endif

    <!-- Product Style -->
    <section class="product-area shop-sidebar shop section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-4 col-12">
                    <div class="shop-sidebar">
                        <!-- Single Widget -->
                        <form class="searchForm" id="searchForm" method="">
                            <div class="single-widget price">
                                <h3 class="title">Search Product</h3>
                                <div class="input-group">
                                    <input type="text" id="product" name="product" class="form-control">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <button type="button" id="searchBtn" class="btn btn-sm btn-primary p-2"><i
                                                    class="ti-search"></i></button>
                                            <button type="button" id="clearBtn" class="btn btn-sm btn-primary p-2"><i
                                                    class="ti-close"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="single-widget category">
                                <h3 class="title">Categories</h3>
                                <select class="category form-control" name="category" style="width:100%" required>
                                    <option value="">Select Option</option>
                                    @foreach ($category as $row)
                                        <option value="{{ $row->id }}">{{ $row->category }}</option>
                                    @endforeach
                                </select>
                                <br />
                            </div>

                            <div class="single-widget subcategory">
                                <h3 class="title">Sub Categories</h3>
                                <div id="subCategoryDiv">
                                    <select class="subcategory form-control" id="subcategory" required>
                                        <option value="">Select Option</option>
                                    </select>
                                </div>
                                </br>
                            </div>

                            <div class="single-widget price">
                                <h3 class="title">Shop by Price</h3>
                                <select class="price form-control" name="price" style="width:100%" required>
                                    <option value="0" selected>Low to High</option>
                                    <option value="1">High to Low</option>
                                </select>
                                <br />
                            </div>

                            <div class="single-widget sort">
                                <h3 class="title">Sort by</h3>
                                <select class="sort form-control" name="sort" style="width:100%" required>
                                    <option value="0" selected>Name</option>
                                    <option value="1">Price</option>
                                </select>
                                <br />
                            </div>
                        </form>
                        <!--/ End Single Widget -->
                    </div>
                </div>
                <div class="col-lg-9 col-md-8 col-12" id="displayProduct">
                    @include('include.shop')
                </div>
            </div>
        </div>
    </section>
    <!--/ End Product Style 1  -->
@endsection

@section('script')
    <script src="{{ asset('eshop/js/glidejs/glide.min.js') }}"></script>
    <script>
        var glideHero = new Glide('.glide', {
            type: 'carousel',
            animationDuration: 2000,
            autoplay: 3000,
            focusAt: '1',
            startAt: 1,
            perView: 1,
            loop: true,
        });
        glideHero.mount();
    </script>

    <!-- Owl carousol code -->
    <script>
        $(document).ready(function() {
            var silder = $('.owl-carousel');
            silder.owlCarousel({
                autoPlay: false,
                items: 1,
                center: false,
                nav: true,
                margin: 30,
                dots: false,
                loop: true,
                navText: [
                    "<i class='fa fa-arrow-left' aria-hidden='true'></i>",
                    "<i class='fa fa-arrow-right' aria-hidden='true'></i>",
                ],
                responsive: {
                    0: {
                        items: 1,
                    },
                    575: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    991: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    },
                },
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
            });

            $("button#clearBtn").click(function() {
                event.preventDefault();
                $("input#product").val("");
                fetch_data(1);
            });

            $("button#searchBtn").click(function() {
                event.preventDefault();
                fetch_data(1);
            });

            $("select.price").change(function() {
                event.preventDefault();
                fetch_data(1);
            });

            $("select.category").change(function() {
                event.preventDefault();
                let price = $("select.price option:selected").val();
                let category = $("select.category option:selected").val();
                $.ajax({
                    url: "{{ url('') }}" + "/vendor-store/category/" + category,
                    beforeSend: function() {
                        $(".preloader").show();
                    },
                    complete: function() {
                        $(".preloader").hide();
                    },
                    success: function(data) {
                        $('#subCategoryDiv').html(data);
                        $("select#subcategory").niceSelect('update');
                    }
                });

                fetch_data(1);
            });

            $(document).on('change', 'select#subcategory', function(event) {
                event.preventDefault();
                fetch_data(1);
            });

            function fetch_data(page) {
                let product = $("input#product").val().trim();
                product ? product : "";
                let category = $("select.category option:selected").val();
                let subcategory = $("select.subcategory option:selected").val();
                let price = $("select.price option:selected").val();
                let sort = $("select.sort option:selected").val();

                let url = "{{ url('') }}" + "/vendor-store/search?page=" + page + "&product=" +
                    product +
                    "&category=" +
                    category +
                    "&subcategory=" +
                    subcategory +
                    "&price=" + price + "&sort=" + sort;
                $.ajax({
                    url: url,
                    beforeSend: function() {
                        $(".preloader").show();
                    },
                    complete: function() {
                        $(".preloader").hide();
                    },
                    success: function(data) {
                        $('#displayProduct').html(data);
                    }
                });
            }
        });
    </script>
@endsection
