@extends('layouts.eshop')
@section('style')
    <!-- Glidejs css files -->
    <link rel="stylesheet" href="{{ asset('eshop/css/glidecss/glide.core.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('eshop/css/glidecss/glide.theme.min.css') }}" />

    <style>
        .colstyle {
            margin: 1%;
            border-radius: 4px;
            padding: 5%;
            box-shadow: 0px 0px 6px 0px {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
        }

        a.customa {
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            width: 100%;
            display: block;
            margin: 10px 0;
        }

        a.customa:hover {
            color: #F7941D;
        }
    </style>
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

    <!-- Start Small Banner  -->
    <section class="small-banner section">
        <div class="container-fluid">
            <div class="row">
                <!-- Single Banner  -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-banner">
                        <a href="{{ route('shop') }}">
                            <img src="{{ SettingHelper::getSettingValueBySLug('shop_banner') ? asset('uploads/setting') . '/' . SettingHelper::getSettingValueBySLug('shop_banner') : asset('eshop/images/cream1.jpeg') }}"
                                alt="#">
                            Shop
                        </a>
                        <div class="content"></div>
                    </div>
                </div>
                <!-- /End Single Banner  -->
                <!-- Single Banner  -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="single-banner">
                        <a href="{{ route('other.brand') }}">
                            <img src="{{ SettingHelper::getSettingValueBySLug('other_brand_banner') ? asset('uploads/setting') . '/' . SettingHelper::getSettingValueBySLug('other_brand_banner') : asset('eshop/images/cream2.jpeg') }}"
                                alt="otherbrand">
                            Other Brand
                        </a>
                        <div class="content"></div>
                    </div>

                </div>
                <!-- /End Single Banner  -->
                <!-- Single Banner  -->
                <div class="col-lg-4 col-12">
                    <div class="single-banner tab-height">
                        <a href="{{ route('customize') }}">
                            <img src="{{ SettingHelper::getSettingValueBySLug('customize_banner') ? asset('uploads/setting') . '/' . SettingHelper::getSettingValueBySLug('customize_banner') : asset('eshop/images/cream3.jpeg') }}"
                                alt="customized">
                            Custom Product
                        </a>
                        <div class="content"></div>
                    </div>
                </div>
                <!-- /End Single Banner  -->
            </div>
        </div>
    </section>
    <!-- End Small Banner -->

    <!-- Start Feature Popular -->
    <div class="product-area most-popular section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Feature Product</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="owl-carousel popular-slider">
                        @foreach ($featureproduct as $row)
                            <!-- Start Single Product -->
                            <div class="single-product colstyle">
                                <div class="product-img">
                                    <a href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">
                                        <img class="default-img"
                                            src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                                            alt="{{ $row->product }}">
                                        <img class="hover-img"
                                            src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                                            alt="{{ $row->product }}">
                                        @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                            <span class="out-of-stock">Out of stock</span>
                                        @endif
                                    </a>
                                    <div class="button-head">
                                        <div class="product-action">
                                            <a class="viewProduct" title="Quick View"
                                                href="{{ route('product.detail', Crypt::encrypt($row->id)) }}"><i
                                                    class=" ti-eye"></i><span>Quick Shop</span></a>
                                        </div>
                                        <div class="product-action-2">
                                            @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                                <a href="javascript:void(0)">Out of stock</a>
                                            @else
                                                <a title="Add to cart" href="javascript:void(0)" id="addToCart"
                                                    data-productid="{{ $row->id }}">Add to cart</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="product-content">
                                    <h3><a
                                            href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">{{ $row->product }}</a>
                                    </h3>
                                    <div class="product-price">
                                        {{-- <span class="old">$60.00</span> --}}
                                        <span>{{ 'PKR ' . $row->price }}</span>&nbsp; <span>(SP
                                            {{ $row->points }})</span>
                                    </div>
                                </div>
                            </div>
                            <!-- End Single Product -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Feature Popular -->

    <!-- Start Product Area -->
    <div class="product-area section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>New Arrival</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="product-info">
                        <div class="row">
                            @foreach ($newproduct as $row)
                                <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                                    <div class="single-product colstyle">
                                        <div class="product-img">
                                            <a href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">
                                                <img class="default-img"
                                                    src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                                                    alt="{{ $row->product }}">
                                                <img class="hover-img"
                                                    src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                                                    alt="{{ $row->product }}">
                                                @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                                    <span class="out-of-stock">Out of stock</span>
                                                @endif
                                            </a>
                                            <div class="button-head">
                                                <div class="product-action">
                                                    <a class="viewProduct" title="Quick View"
                                                        href="{{ route('product.detail', Crypt::encrypt($row->id)) }}"><i
                                                            class=" ti-eye"></i><span>Quick Shop</span></a>
                                                </div>
                                                <div class="product-action-2">
                                                    @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                                        <a href="javascript:void(0)">Out of stock</a>
                                                    @else
                                                        <a title="Add to cart" href="javascript:void(0)" id="addToCart"
                                                            data-productid="{{ $row->id }}">Add to cart</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-content">
                                            <h3><a
                                                    href="{{ route('product.detail', Crypt::encrypt($row->id)) }}">{{ $row->product }}</a>
                                            </h3>
                                            <div class="product-price">
                                                <span>{{ 'PKR ' . $row->price }}</span>&nbsp; <span>(SP
                                                    {{ $row->points }})</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Product Area -->

    <!-- our Testimonials start -->
    <section class="section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h2>Our Testimonials</h2>
                    </div>
                </div>
            </div>
            <div>
                <div class="containerOwl rounded">
                    <div class="owl-carousel owl-theme">
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Good Service
                                </div>
                                <div class="testimonial">
                                    I Want to Share Some Review About ABF Cosmetics
                                    (Attire Beauty Fragrance) Pakistan. ABF Cosmetics Is One of The Best
                                    Online Direct Selling Companies & Home Base Business Opportunity. Through
                                    This Platform We Can Earn Easily & We Can Secure Our Future & Also of Many People Future
                                    by
                                    Generating Network. This Is Very Reliable & Beneficial Platform Especially for Female,
                                    Students
                                    & Beautician Too. So, I Suggest All Females for Joining This Fabulous Business
                                    Opportunity for Bright
                                    Future & Make Hers Dreams True for Independent Life. Thanks
                                </div>
                                <div class="d-flex flex-row profile">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Farzana Hussain</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Great Service
                                </div>
                                <div class="testimonial">
                                    I’m Working with ABF Cosmetics (Attire Beauty Fragrance) Of Pakistan.
                                    ABF Cosmetics Company Is No 1 Direct Selling Cosmetic Company of Pakistan. This Platform
                                    Is Providing a Very Beneficial Business Opportunity for Ladies. Through This Platform I
                                    Can
                                    Easily Earn for Myself & I Can Support My Family. Through This Platform You Can Make
                                    Your Future Secure.
                                    This Platform Have Many Benefit & Successful Business Plan. I’m Proud of Being a Part Of
                                    This Platform. Thanks
                                </div>
                                <div class="d-flex flex-row profile">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Arooj Zaheer</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Great Quality
                                </div>
                                <div class="testimonial">
                                    I’m Businesses Lady, I’m Working Through ABF Cosmetics (Attire Beauty Fragrance)
                                    Pakistan.
                                    This Platform Provides Me A Home Base & Earning Opportunity. This Platform Plans a Very
                                    Different
                                    & Beneficial Business Opportunity for Woman & Students and Those Needy People Which Are
                                    Jobless.
                                    Through This Platform We Can Easily Achieve Our Targets & Can Easily Earn Through
                                    Generating Network. Thanks
                                </div>
                                <div class="d-flex flex-row profile">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Humaira Adnan</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Good Service
                                </div>
                                <div class="testimonial">
                                    This Is Saniya. I Am Working in ABF Company. Abf Is World's Best Company.
                                    This Company Had Become Popular in Pakistan and Getting Higher Level. Because
                                    This Company Is Giving Too Much Opportunities It's Members and Users. Ohhh!
                                    Its Products Are Very Amazing. All Products Are Herbal and Organic. I Love Its Products.
                                    It's Policy and Products Are Very Beneficial for All Users. U Can Easily Earn from Home
                                    and Fulfill Your Dreams.
                                </div>
                                <div class="d-flex flex-row profile">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Saniya</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="owl-item">
                            <div class="card d-flex flex-column">
                                <div class="mt-2">
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star active-star"></span>
                                    <span class="fas fa-star-half-alt active-star"></span>
                                </div>
                                <div class="main font-weight-bold pb-2 pt-1">
                                    Amazing Products
                                </div>
                                <div class="testimonial">
                                    I am Anaya, I am business consultant in ABF Cosmetics company of Pakistan.
                                    ABF Cosmetics is very beneficial business opportunity for ladies. This business platform
                                    provides many options of earning. I am so satisfied with this platform. I am supporting
                                    my
                                    family through this platform. I will suggest this platform for all ladies who want to
                                    earn for
                                    herself. Thanks
                                </div>
                                <div class="d-flex flex-row profile">
                                    <div class="d-flex flex-column pl-2">
                                        <div class="name">Anaya</div>
                                        <p class="text-muted designation">
                                            Customer
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- our Testimonials ends -->

    <section class="shop-services section home">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service d-flex gap-3 flex-column justify-content-center ">
                        <img src="eshop/images/SECPcopy.png" alt="cashimage" />
                        <h4 class="text-center">SECP</h4>
                        <p class="text-center">ABF Cosmetics is Certified With Securities and Exchange Commission of
                            Pakistan.</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service d-flex gap-3 flex-column justify-content-center">
                        <img src="eshop/images/FBR.png" alt="cashimage" />
                        <h4 class="text-center">FBR</h4>
                        <p class="text-center">ABF Cosmetics is Certified With Federal Board of Revenue.</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service d-flex gap-3 flex-column justify-content-center">
                        <img src="eshop/images/codcopy.png" alt="codimage" />
                        <h4 class="text-center">COD Delivery</h4>
                        <p class="text-center">Cash On Delivery and Advance Order are Delivered All Over in Pakistan</p>
                    </div>
                    <!-- End Single Service -->
                </div>
                <div class="col-lg-3 col-md-6 col-12">
                    <!-- Start Single Service -->
                    <div class="single-service d-flex gap-3 flex-column justify-content-center">
                        <img src="eshop/images/abfservicecentercopy.png" alt="cashimage" />
                        <h4 class="text-center">ABF Service Center</h4>
                        <p class="text-center">Our Skin Specialists And Consultant are Available Seven Days a Week to
                            Answer any Question.</p>
                    </div>
                    <!-- End Single Service -->
                </div>
            </div>
        </div>
    </section>



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
@endsection
