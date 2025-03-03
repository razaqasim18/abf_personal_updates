@extends('layouts.eshop')
@section('style')
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
            box-shadow: 0px 0px 6px 0px {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
        }

        .height-37 {
            height: 37px;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('eshop/css/product_comment.css') }}">
@endsection
@section('content')
    <!-- Product Style -->
    {{-- <section class="product-area shop-sidebar shop section"> --}}
    <section class="small-banner section">
        <div class="mt-2">
            <x-vendor-detail-component :vendor="$vendor"></x-vendor-detail-component>
        </div>
        <div class="container">
            <div class="row">
                <input type="hidden" id="vendorid" value="{{ $vendor->id }}" />
                <div class="col-lg-12 col-md-12 col-12">
                    <!-- Single Widget -->
                    <form class="searchForm row" id="searchForm" method="" style="background: #f6f7fb;padding: 30px;">
                        <div class="col-lg-1 col-md-1 col-12 mt-2 text-center">
                            <h3>Filter</h3>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12 mt-2">
                            <div class="input-group">
                                <input type="text" id="product" name="product" class="form-control height-37">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <button type="button" id="searchBtn"
                                            class="btn btn-sm btn-primary p-2 height-37"><i class="ti-search"></i></button>
                                        <button type="button" id="clearBtn"
                                            class="btn btn-sm btn-primary p-2 height-37"><i class="ti-close"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12 mt-2">
                            <select class="category form-control" name="category" style="width:100%" required>
                                <option value="">Select Category</option>
                                @foreach ($category as $row)
                                    <option value="{{ $row->id }}">{{ $row->category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-3 col-12 mt-2">
                            <div id="subCategoryDiv">
                                <select class="subcategory form-control" id="subcategory" required>
                                    <option value="">Select Option</option>
                                </select>
                            </div>
                            </br>
                        </div>

                        <div class="col-lg-2 col-md-2 col-12 mt-2">
                            <select class="price form-control" name="price" style="width:100%" required>
                                <option value="0" selected>Low to High</option>
                                <option value="1">High to Low</option>
                            </select>
                        </div>
                    </form>
                    <!--/ End Single Widget -->
                </div>
            </div>
            <div id="displayProduct">
                @include('include.shop')
            </div>
        </div>
    </section>
    <!--/ End Product Style 1  -->
@endsection
@section('script')
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
                let vendorid = $("input#vendorid").val();

                let product = $("input#product").val().trim();
                product ? product : "";
                let category = $("select.category option:selected").val();
                let price = $("select.price option:selected").val();
                let sort = $("select.sort option:selected").val();
                let url = "{{ url('') }}" + "/vendor-store/search?page=" + page + "&product=" + product +
                    "&category=" +
                    category +
                    "&price=" + price + "&sort=" + sort + "&vendorid=" + vendorid;
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
