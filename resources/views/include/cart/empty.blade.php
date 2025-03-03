<div class="row">
    <div class="col-12">
        <!-- Shopping Summery -->
        <table class="table shopping-summery">
            <thead>
                <tr class="main-hading">
                    <th class="text-center">PRODUCT</th>
                    <th class="text-center">NAME</th>
                    <th class="text-center">UNIT PRICE</th>
                    <th class="text-center">Sale POINT</th>
                    <th class="text-center">QUANTITY</th>
                    <th class="text-center">TOTAL</th>
                    <th class="text-center"><i class="ti-trash remove-icon"></i></th>
                </tr>
            </thead>
            <tbody id="cartTable">
            </tbody>
        </table>
        <!--/ End Shopping Summery -->
    </div>
</div>

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
                                    Shipping Charges
                                    <span class="shippingcharges"></span>
                                    <input type="hidden" id="shippingcharges"
                                        value="{{ SettingHelper::getSettingValueBySLug('shipping_charges') }}" />
                                    <input type="hidden" name="shippingcharges" id="totalshippingcharges" />
                                    total
                                </li>

                                {{-- <li>You Save<span>$20.00</span></li> --}} <li class="last">
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
