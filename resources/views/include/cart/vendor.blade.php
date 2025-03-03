<!-- Shopping Summery -->
@php
    // Get the content of the cart for the 'vendor' session
    $cartContent = \Cart::session('vendor')->getContent();

    // Group the items by the product_seller_id attribute
    $groupedBySeller = $cartContent->groupBy(function ($item) {
        return $item->attributes->product_seller_id;
    });
@endphp
{{-- vendor --}}

@foreach ($groupedBySeller as $sellerId => $items)
    <div class="row py-4">
        <div class="col-12">
            @php
                $deliveryCharges = SettingHelper::getVendordeliveryCharges($sellerId);
                $vendor = VendorHelper::getVendorByid($sellerId);
            @endphp
            <h6>
                {{ $vendor->business_name . ' Ship Charges Per Kg : ' . $deliveryCharges }}
            </h6>
            <table class="table shopping-summery">
                <thead>
                    <tr class="main-hading">
                        <th class="text-center">PRODUCT</th>
                        <th class="text-center">NAME</th>
                        <th class="text-center">UNIT PRICE</th>
                        <th class="text-center">Sale POINT</th>
                        @if (SettingHelper::getSettingValueBySLug('gst_charges') > 0)
                            <th class="text-center">GST</th>
                        @endif
                        <th class="text-center">QUANTITY</th>
                        <!--<th class="text-center">Ship Charges</th>-->
                        <th class="text-center">TOTAL</th>
                        <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                    </tr>
                </thead>
                <tbody id="cartTable">
                    @php $weight = 0; @endphp
                    @foreach ($items as $item)
                        <tr>
                            <td class="image" data-title="No">
                                <img src="{{ $img = $item->attributes->product_image ? asset('uploads/product') . '/' . $item->attributes->product_image : asset('img/products/product-1.png') }}"
                                    alt="{{ $item->product }}">
                            </td>
                            <td class="product-des text-center" data-title="{{ $item->product }}">
                                <p class="product-name"><a
                                        href="{{ route('vendor.product.detail', \Crypt::encrypt($item->id)) }}">{{ $item->name }}</a>
                                </p>
                                {{-- <p class="product-des">Maboriosam in a tonto nesciung eget distingy magndapibus.</p> --}}
                            </td>
                            <td class="price text-center" data-title="price">
                                <span class="productprice">{{ 'PKR ' . $item->attributes->product_price }}</span>
                            </td>
                            <td class="points text-center" data-title="points">
                                <span class="point">{{ $item->attributes->product_points }}</span>
                            </td>
                            @if (SettingHelper::getSettingValueBySLug('gst_charges') > 0)
                                <td class="text-center">
                                    <span>{{ SettingHelper::getSettingValueBySLug('gst_charges') . ' %' }}</span>
                                </td>
                            @endif
                            <td class="qty text-center" data-title="qty">
                                <!-- Input Order -->
                                <div class="input-group">
                                    <div class="button minus">
                                        <button type="button" class="btn btn-primary btn-number"
                                            @if ($item->quantity <= 1) disabled="disabled" @endif
                                            data-type="minus" data-field="quant[{{ $item->id }}]"
                                            @disabled($item->attributes->product_seller_discounted)>
                                            <i class="ti-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" id="quantity" name="quant[{{ $item->id }}]"
                                        class="input-number quantity" data-min="1" data-max="100"
                                        value="{{ $item->quantity }}" data-productid="{{ $item->id }}"
                                        @disabled($item->attributes->product_seller_discounted)>
                                    <div class="button plus">
                                        <button type="button" class="btn btn-primary btn-number" data-type="plus"
                                            data-field="quant[{{ $item->id }}]" @disabled($item->attributes->product_seller_discounted)>
                                            <i class="ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--/ End Input Order -->
                            </td>


                            <!--<td class="shipcharges text-center" data-title="vendorshipcharges">-->
                            <!--    <span class="charges">{{ ceil($item->attributes->product_weight) * $deliveryCharges }}</span>-->
                                
                            <!--</td>-->

                            <td class="total-amount text-center" data-title="Total">
                                <span class="producttotal">{{ 'PKR ' . \Cart::get($item->id)->getPriceSum() }}</span>
                                <input type="hidden" class="business_name" value="{{ Str::slug($vendor->business_name) }}" />
                                <input type="hidden" class="point" value="{{ $item->attributes->product_points }}" />
                                <input type="hidden" class="{{ Str::slug($vendor->business_name) }}_business_totalweight" value="{{ $item->attributes->product_weight }}" />
                                <input type="hidden" class="total" value="{{ \Cart::get($item->id)->getPriceSum() }}" />
                                @php $weight = $weight + $item->attributes->product_weight; @endphp
                            </td>

                            <td class="action" data-title="Remove">
                                <a href="javascript:void(0)" class="removeProduct" id="removeProduct"
                                    data-productid="{{ $item->id }}" data-isdiscount="0"
                                    @if ($item->attributes->product_from_table == 'vendor') data-isvendor="1" @endif>
                                    <i class="ti-trash remove-icon"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    <input type="hidden" class="{{ Str::slug($vendor->business_name) }}_vendorshippingcharges" value="{{ $deliveryCharges }}" />
                    <input type="hidden" class="{{ Str::slug($vendor->business_name) }}_shippingcharges shippingcharges" value="{{ ceil($weight) * $deliveryCharges }}" />
                    
                </tbody>
            </table>
        </div>
    </div>
@endforeach


<!--/ End Shopping Summery -->

<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ route('checkout') }}">
            <!-- Total Amount -->
            <div class="total-amount">
                <div class="row">
                    <div class="col-lg-8 col-md-5 col-12">
                        {{-- @if (Auth::guard('web')->user() && SettingHelper::getSettingValueBySLug('coupon_discount') > 0)
                         <div class="left">
                             <div class="checkbox">
                                 <label
                                     class="checkbox-inline  @if (Session::get('coupon_discount') > 0) checked @endif"
                                     for="discount_coupon">
                                     <input name="discount_coupon" id="discount_coupon" type="checkbox"
                                         @if (Session::get('coupon_discount') > 0) checked @endif>
                                     Check To Apply Coupon Discount
                                 </label>
                             </div>
                         </div>
                     @endif --}}
                    </div>
                    <div class="col-lg-4 col-md-7 col-12">
                        <div class="right">
                            <ul>
                                <li>
                                    Cart Subtotal
                                    <span class="subtotal"></span>
                                    <input type="hidden" name="subtotal" id="subtotal" />
                                </li>
                                <li>
                                    Total Points
                                    <span class="subpoint"></span>
                                    <input type="hidden" name="subpoint" id="subpoint" />
                                </li>

                                <li>
                                    Shipping Charges total
                                    <span class="shippingcharges"></span>
                                    <input type="hidden" id="totalshippingcharges" value="" />
                                </li>

                                <li class="last">
                                    You Pay
                                    <span class="totalpay"></span>
                                    <input type="hidden" name="totalpay" id="totalpay" />
                                </li>
                            </ul>
                            <div class="button5">
                                @if (Auth::guard('web')->user())
                                    <a href="{{ route('checkout') }}" class="btn">Checkout</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn">Login To Checkout</a>
                                @endif
                                <a href="{{ route('shop') }}" class="btn">Continue shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ End Total Amount -->
        </form>
    </div>
</div>
@push('scripts')
    <script>
        $('document').ready(function() {
            calculateSubtotal();
            calculatePoint();
            calculateShippingCharges();
            calculateTotalPay();
            $('body').on("change", "input.quantity", function() {
                let tthis = $(this);
                let productid = $(this).data('productid');
                let quantity = $(this).val();
                $.ajax({
                    url: '{{ route('cart.update') }}',
                    method: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        productid: productid,
                        quantity: quantity,
                    },
                    beforeSend: function() {
                        $(".preloader").show();
                    },
                    complete: function() {
                        $(".preloader").hide();
                    },
                    success: function(response) {
                        totalweight = 0;
                        if (response.type == 0) {
                            iziToast.error({
                                title: 'Error!',
                                message: response.msg,
                                position: 'topRight'
                            });
                            tthis.closest('tr').find("input.quantity").val(Number(quantity) -
                                Number(1));
                            return false;
                        } else {
                            const element = response.cart;
                            tthis.closest('tr').find("span.producttotal").html("PRK " + element
                                .sumprice.toFixed(2));
                            tthis.closest("tr").find("input.total").val(element
                                .sumprice.toFixed(2));
                           
                            const business = tthis.closest("tr").find("input.business_name").val();
                            tthis.closest("tr").find("input."+business+"_business_totalweight").val(element.totalweight);
                      
                             $("input."+business+"_business_totalweight").each(function() {
                                    console.log($(this).val());
                                totalweight = Number(totalweight) + Number($(this).val()); // This is the jquery object of the input, do what you will
                            });
                            $("input."+business+"_shippingcharges").val(Math.ceil(totalweight) * Number($("input."+business+"_vendorshippingcharges").val()));
                           
                            tthis.closest('tr').find("span.point").html(element
                                .point);
                            tthis.closest('tr').find("input.point").val(element
                                .point);
                            calculateSubtotal();
                            calculatePoint();
                            calculateShippingCharges();
                            calculateTotalPay();
                        }
                    }
                });
            });

            $("input#discount_coupon").change(function() {
                let val = ($('#discount_coupon')[0].checked) ? 1 : 0;
                let url = "{{ url('cart/discount') }}" + "/" + val;
                $.ajax({
                    url: url,
                    method: "GET",
                    // data: {
                    //     _token: '{{ csrf_token() }}',
                    //     productid: productid,
                    //     quantity: quantity,
                    // },
                    beforeSend: function() {
                        $(".preloader").show();
                    },
                    complete: function() {
                        $(".preloader").hide();
                    },
                    success: function(response) {
                        iziToast.success({
                            title: 'Success!',
                            message: response.msg,
                            position: 'topRight'
                        });
                    }
                });

            });
        });

        function calculateSubtotal() {
            var total = 0;
            $("input.total").each(function() {
                total = Number(total) + Number($(this)
                    .val()); // This is the jquery object of the input, do what you will
            });
            $("span.subtotal").html("PKR " + total.toFixed(2));
            $("input#subtotal").val(total.toFixed(2));
        }

        function calculatePoint() {
            var total = 0;
            $("input.point").each(function() {
                total = Number(total) + Number($(this)
                    .val()); // This is the jquery object of the input, do what you will
            });
            $("span.subpoint").html(total);
            $("input#subpoint").val(total);
        }

        function calculateTotalPay() {
            let subtotal = Number($("input#subtotal").val());
            let totalshippingcharges = Number($("input#totalshippingcharges").val());
            let total = subtotal + totalshippingcharges;
            $("span.totalpay").html("PKR " + total.toFixed(2));
            $("#totalpay").val(total.toFixed(2));
        }


        function calculateShippingCharges() {
            var total = 0;
            $("input.shippingcharges").each(function() {
                total = Number(total) + Number($(this)
                    .val()); // This is the jquery object of the input, do what you will
            });
            total = Math.ceil(Number(total));
            $("span.shippingcharges").html("PKR " + total.toFixed(2));
            $("input#totalshippingcharges").val(total.toFixed(2));
        }
    </script>
@endpush
