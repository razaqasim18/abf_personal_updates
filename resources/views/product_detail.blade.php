@extends('layouts.eshop')
@section('style')
    <link rel="stylesheet" href="{{ asset('eshop/css/product_comment.css') }}">
@endsection

@section('content')
    <section class="small-banner section">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <!-- Product Slider -->
                    <div class="product-gallery">
                        <div class="quickview-slider-active">
                            @if ($product->image)
                                <div class="single-slider">
                                    <img src="{{ asset('uploads/product') . '/' . $product->image }}"
                                        alt="{{ $product->product }}">
                                </div>
                            @endif
                            @if ($product->getMedia('images'))
                                @foreach ($product->getMedia('images') as $image)
                                    <div class="single-slider">
                                        <img src="{{ $image->getUrl() }}" alt="{{ $image->name }}">
                                    </div>
                                @endforeach
                            @endif
                            @if (!($product->image && $product->getMedia('images')))
                                <div class="single-slider">
                                    <img src="{{ asset('img/products/product-1.png') }}" alt="{{ $product->image }}">
                                </div>
                                <div class="single-slider">
                                    <img src="{{ asset('img/products/product-1.png') }}" alt="{{ $product->image }}">
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- End Product slider -->
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <div class="quickview-content">
                        <h2>{{ $product->product }}</h2>
                        <h3 id="normal-price">
                            <a class="midium-banner single-banner a">PRICE</a>:
                            @if (isset($product->vendor_id) && $product->discount > 0)
                                <del>
                                    {{ 'PKR ' . $product->price }}
                                </del>
                            @else
                                {{ 'PKR ' . $product->price }}
                            @endif
                        </h3>
                        @if (isset($product->vendor_id) && $product->discount > 0)
                            <h3>
                                <a class="midium-banner single-banner a">Discount PRICE</a>:
                                {{ 'PKR ' . (int) $product->price - ((int) $product->price * $product->discount) / 100 }}
                            </h3>
                        @else
                            <h3 id="discount-price" style="display:none">
                                <a class="midium-banner single-banner a">Discount PRICE</a>:
                                {{ 'PKR ' . ((int) $product->price - ((int) $product->price * SettingHelper::getSettingValueBySLug('coupon_discount')) / 100) }}
                            </h3>
                        @endif

                        @if ($product->vendor_id)
                            <div class="productRating">
                                <h5>
                                    @for ($i = 5; $i > 0; $i--)
                                        @if ($product->rating >= $i)
                                            <i class="fas fa-star"></i> <!-- Full star -->
                                        @elseif ($product->rating >= $i - 0.5)
                                            <i class="fas fa-star-half-alt"></i> <!-- Half star -->
                                        @else
                                            <i class="far fa-star"></i> <!-- Empty star -->
                                        @endif
                                    @endfor
                                    ({{ $product->rating }})
                                </h5>
                            </div>
                        @endif

                        <h3>
                            <a class="midium-banner single-banner a">IN Stock</a>:
                            @if ($product->in_stock == 0 || $product->in_stock <= 0)
                                Not Available
                            @else
                                Available
                            @endif
                        </h3>

                        @if ($product->is_other == 0 && !isset($product->vendor_id))
                            <div class="quickview-peragraph">
                                <div class="checkbox">
                                    <label class="checkbox-inline  @if (Session::get('coupon_discount') > 0) checked @endif"
                                        for="discount_coupon">
                                        <input name="discount_coupon" id="discount_coupon" type="checkbox">
                                        Check To Apply Coupon Discount
                                    </label>
                                </div>
                            </div>
                        @endif
                        <div class="quickview-peragraph">
                            <p>{{ $product->description }}</p>
                        </div>
                        <br />
                        @if ($product->in_stock == 0 || $product->in_stock <= 0)
                            <div class="add-to-cart">
                                <a href="javascript:void(0)" class="btn btn-danger" style="cursor: not-allowed;">Out of
                                    stock</a>
                            </div>
                        @else
                            @if (!isset($product->vendor_id))
                                @if (!\Cart::session('normal')->get($product->id . '-discount'))
                                    @if ($product->is_discount || $product->discount > 0)
                                        <div class="add-to-cart-discount text-center">
                                            <a href="javascript:void(0)" id="addToCartDiscount"
                                                data-productid="{{ $product->id }}"
                                                @if (isset($product->vendor_id)) data-isvendor="1" @else data-isvendor="0" @endif
                                                class="btn btn-block">Add to cart with
                                                {{ $product->discount }} %</a>
                                        </div>
                                    @endif
                                @endif
                            @endif
                            <br /> <br />

                            <div class="quantity">
                                <!-- Input Order -->
                                <div class="input-group">
                                    <div class="button minus">
                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled"
                                            data-type="minus" data-field="quant[1]">
                                            <i class="ti-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" id="quantity" data-productid="{{ $product->id }}"
                                        name="quant[1]" class="input-number" data-min="1" data-max="1000" value="1">
                                    <div class="button plus">
                                        <button type="button" class="btn btn-primary btn-number" data-type="plus"
                                            data-field="quant[1]">
                                            <i class="ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--/ End Input Order -->
                            </div>
                            <div class="add-to-cart">
                                <a href="javascript:void(0)" id="addToCartProductPage" data-productid="{{ $product->id }}"
                                    @if (isset($product->vendor_id)) data-isvendor="1" @else data-isvendor="0" @endif
                                    class="btn">Add to cart</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- vendor detail --}}
            @if (isset($product->vendor_id) && $product->vendor_id != null)
                <div class="mt-5">
                    <x-vendor-detail-component :vendor="$product->vendor"></x-vendor-detail-component>
                </div>
            @endif

            {{-- comment system --}}
            <div class="mt-5">
                @includeIf('include.product_detail_comment')
            </div>
        </div>
    </section>
@endsection

@section('script')
    <!-- Bootstrap 5 JS -->
    <script src="{{ asset('eshop/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        function toggleReplyBox(parentid, productid) {
            var output = '';
            output += '<div id="reply-box-1" class="reply-input mt-3"><div class="d-flex">';
            output +=
                '<div class="w-100 ms-2"><form id="commentForm" method="post"><textarea class="form-control" rows="2" name="content" placeholder="Write a reply..." required></textarea>';
            output +=
                ' <input type="hidden" name="_token" value="{{ csrf_token() }}"/> <input type="hidden" name="parentid" value="' +
                parentid + '"/>';
            output += '<input type="hidden" name="productid" value="' + productid + '"/>';
            output +=
                '<button type="button"  data-parentid="' + parentid +
                '" class="btn btn-primary btn-sm mt-2 mr-2" id="cancelReply">Cancel</button><button type="button" id="submitComment" class="btn btn-primary btn-sm mt-2">Reply</button></form></div></div></div>';

            // Hide the reply span
            $("span#reply-" + parentid).addClass("d-none");

            // Append the reply form HTML after the corresponding div
            $("div#replybox-" + parentid).html(output);

            return false;
        }

        $('body').on("click", "button#submitComment", function(e) {
            e.preventDefault(); // Prevent the default form submission
            var form = $("#commentForm")[0]; // Correct form selector
            var formData = new FormData(form); // Serialize the form data
            // CSRF token setup (if needed)
            refreshCSRFToken();
            $.ajax({
                url: "{{ route('vendor.product.comment.reply') }}", // Correct route syntax
                method: "POST",
                data: formData, // FormData object
                processData: false, // Prevent jQuery from converting the data into a string
                contentType: false, // Prevent jQuery from setting a default content-type
                beforeSend: function() {
                    $(".preloader").show(); // Show preloader
                },
                complete: function() {
                    $(".preloader").hide(); // Hide preloader
                },
                success: function(response) {
                    $('#displayProduct').html(response);
                },
                error: function(xhr) {
                    iziToast.error({
                        title: 'Error!',
                        message: 'Something went wrong. Please try again.',
                        position: 'topRight'
                    });
                }
            });
        });

        // Make sure this function is defined at the global level
        $('body').on("click", "#cancelReply", function() {
            let parentid = $(this).attr('data-parentid');
            // Show the reply span
            $("span#reply-" + parentid).removeClass("d-none");

            // Clear the reply form HTML
            $("div#replybox-" + parentid).html('');
        });

        $("#discount_coupon").click(function() {
            if ($('#discount_coupon').is(':checked')) {
                $("#normal-price").css("display", 'none')
                $("#discount-price").css("display", 'block')
            } else {
                $("#normal-price").css("display", 'block')
                $("#discount-price").css("display", 'none')
            }
        });

        $('body').on("click", "a#addToCartProductPage", function() {
            let productid = $(this).data('productid');
            let productquantity = 1;
            if ($("input#quantity").val()) {
                productquantity = $("input#quantity").val();
            }
            let discount_coupon = 0;
            if ($('#discount_coupon').is(':checked')) {
                discount_coupon = 1;
            } else {
                discount_coupon = 0;
            }
            let isvendor = $(this).data('isvendor');

            refreshCSRFToken();
            $.ajax({
                url: '{{ route('cart.insert') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    productid: productid,
                    quantity: productquantity,
                    discount_coupon: discount_coupon,
                    isvendor: isvendor,
                    discounted: 0,
                },
                beforeSend: function() {
                    $(".preloader").show();
                },
                complete: function() {
                    $(".preloader").hide();
                },
                success: function(response) {
                    if (response.type) {
                        iziToast.success({
                            title: 'Success',
                            message: response.msg,
                            position: 'topRight'
                        });
                        getItemList();
                    } else {
                        iziToast.error({
                            title: 'Error!',
                            message: response.msg,
                            position: 'topRight'
                        });
                    }
                }
            });
        });

        $('a#addToCartDiscount').on('click', function() {
            let productid = $(this).data('productid');
            let productquantity = 1;
            if ($("input#quantity").val()) {
                productquantity = $("input#quantity").val();
            }
            let isvendor = $(this).data('isvendor');
            refreshCSRFToken();
            $.ajax({
                url: "{{ route('cart.insert') }}",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    productid: productid,
                    quantity: productquantity,
                    isvendor: isvendor,
                    discounted: 1,
                },
                beforeSend: function() {
                    $(".preloader").show();
                },
                complete: function() {
                    $(".preloader").hide();
                },
                success: function(response) {
                    if (response.type) {
                        $('a#addToCartDiscount').remove();
                        iziToast.success({
                            title: 'Success',
                            message: response.msg,
                            position: 'topRight'
                        });
                        getItemList();
                    } else {
                        iziToast.error({
                            title: 'Error!',
                            message: response.msg,
                            position: 'topRight'
                        });
                    }
                }
            });
        });

        $('body').on("click", "span#deleteReply", function() {
            var id = $(this).attr('data-commentid');
            var parent = $(this).parent().parent();
            $.ajax({
                url: "{{ route('vendor.product.comment.delete') }}",
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                beforeSend: function() {
                    $(".preloader").show();
                },
                complete: function() {
                    $(".preloader").hide();
                },
                success: function(response) {
                    if (response.type) {
                        parent.remove();
                        iziToast.success({
                            title: 'Success!',
                            message: response.msg,
                            position: 'topRight'
                        });
                    } else {
                        iziToast.error({
                            title: 'Error!',
                            message: response.msg,
                            position: 'topRight'
                        });
                    }
                }
            });
        });

        $('body').on("click", "span#editRply", function() {
            var id = $(this).attr('data-commentid');
            var content = $(this).attr('data-content');
            var productid = $(this).attr('data-productid');
            var output = '';
            output += '<div id="reply-box-1" class="reply-input mt-3"><div class="d-flex">';
            output +=
                '<div class="w-100 ms-2"><form id="editComment"  method="post"><textarea class="form-control" rows="2" name="content" placeholder="Write a reply..." required>' +
                content + '</textarea>';
            output += '<input type="hidden" name="_token" value="{{ csrf_token() }}"/>';
            output += '<input type="hidden" name="productid" value="' + productid + '"/>';
            output += '<input type="hidden" name="commentid" value="' + id + '"/>';
            output +=
                '<button type="button"  data-parentid="' + id +
                '" class="btn btn-primary btn-sm mt-2 mr-2" id="cancelEdit">Cancel</button><button id="editSubmit" type="button" class="btn btn-primary btn-sm mt-2">Reply</button></form></div></div></div>';
            // Hide the reply span
            $("a#edit-" + id).addClass("d-none");
            // Append the reply form HTML after the corresponding div
            $("div#replybox-" + id).html(output);
        });

        $('body').on("click", "button#editSubmit", function(e) {
            e.preventDefault(); // Prevent the default form submission
            var form = $("#editComment")[0]; // Correct form selector
            var formData = new FormData(form); // Serialize the form data
            // CSRF token setup (if needed)
            refreshCSRFToken();
            $.ajax({
                url: "{{ route('vendor.product.comment.reply') }}", // Correct route syntax
                method: "POST",
                data: formData, // FormData object
                processData: false, // Prevent jQuery from converting the data into a string
                contentType: false, // Prevent jQuery from setting a default content-type
                beforeSend: function() {
                    $(".preloader").show(); // Show preloader
                },
                complete: function() {
                    $(".preloader").hide(); // Hide preloader
                },
                success: function(response) {
                    $('#displayProduct').html(response);
                },
                error: function(xhr) {
                    iziToast.error({
                        title: 'Error!',
                        message: 'Something went wrong. Please try again.',
                        position: 'topRight'
                    });
                }
            });
        });

        // Make sure this function is defined at the global level
        $('body').on("click", "button#cancelEdit", function() {
            let parentid = $(this).attr('data-parentid');
            // Show the reply span
            $("span#edit-" + parentid).removeClass("d-none");

            // Clear the reply form HTML
            $("div#replybox-" + parentid).html('');
        });
    </script>
@endsection
