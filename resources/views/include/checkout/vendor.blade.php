<div class="col-lg-4 col-12">
    <div class="order-details">
        <!-- Order Widget -->
        <div class="single-widget">
            <h2>CART TOTALS</h2>
            <div class="content">
            @php
                // Initialize variables
                $attribute = $subtotal = $discount = $shippingcharges = 0;
            
                // Get the content of the cart for the 'vendor' session
                $cartContent = \Cart::session('vendor')->getContent();
            
                // Group the items by the product_seller_id attribute
                $groupedBySeller = $cartContent->groupBy(function ($item) {
                    return $item->attributes->product_seller_id;
                });
            
                // Iterate through each seller's items
                foreach ($groupedBySeller as $sellerId => $items) {
                    // Get delivery charges for the seller
                    $deliveryCharges = SettingHelper::getVendordeliveryCharges($sellerId);
                    
                    // Initialize weight and shipping charges for the current seller
                    $weight = 0;
                    $sellerShippingCharges = 0;
            
                    // Calculate attributes, weight, discount, and subtotal for each item
                    foreach ($items as $item) {
                        $attribute += $item->attributes->product_points;
                        $weight += $item->attributes->product_weight;
                        $discount += $item->attributes->product_discount;
                        $subtotal += $item->getPriceSum();
                    }
            
                    // Calculate shipping charges for the current seller
                    $sellerShippingCharges = ceil($weight) * $deliveryCharges;
                    $shippingcharges += $sellerShippingCharges;
            
                    // Output shipping charges for debugging (optional)
                   //  echo "Shipping charges for seller $sellerId: $sellerShippingCharges<br>";
                }
            
                // Calculate total payable amount
                $totalpay = $subtotal + $shippingcharges;
            
                // Output the total payable amount (optional)
                // echo "Total payable amount: $totalpay";
            @endphp
                <ul>
                    <li>
                        Sale Point<span>{{ $attribute }}</span>
                        <input type="hidden" name="subpoint" id="subpoint" value="{{ $attribute }}" />
                    </li>
                    <li>
                        Weight <span>{{ ceil($weight) }} KG</span>
                        <input type="hidden" name="totalweight" id="totalweight" value="{{ $weight }}" />
                    </li>
                    <li>
                        Sub Total<span>PKR {{ $subtotal }}</span>
                        <input type="hidden" name="subtotal" id="subtotal" value="{{ $subtotal }}" />
                    </li>
                    <li>
                        (-) Discount
                        <span>PKR {{ $discount }}</span>
                        <input type="hidden" name="discount" id="discount" value="{{ $discount }}" />
                    </li>
                    <li>
                        (+) Shipping
                        <span>PKR {{ $shippingcharges }}</span>
                        <input type="hidden" name="shippingcharges" id="totalshippingcharges"
                            value="{{ $shippingcharges }}" />
                    </li>
                    <li class="last">
                        Total<span>PKR {{ $totalpay }}</span>
                        <input type="hidden" name="totalpay" id="totalpay" value="{{ $totalpay }}" />
                    </li>
                </ul>
            </div>

        </div>
        <!--/ End Order Widget -->
        <!-- Order Widget -->
        <div class="single-widget">
            <h2>Payments</h2>
            <div class="content">
                <div style="display:block;padding: 10px 27px;">
                    <input name="payment_by" id="payment_cash" type="radio" value="0" checked>
                    <label for="payment_cash">
                        By Cash
                    </label><br />
                    <div class="row">
                        <div class="col-md-6 text-left">
                            <input name="payment_by" id="payment_wallet" type="radio" value="1">
                            <label for="payment_wallet">
                                By Wallet
                            </label>
                        </div>
                        @if (Auth::guard('web')->user() && Auth::guard('web')->user()->userdetail)
                            <div class="col-md-6 text-right">
                                <label><b>Balance:</b> {!! CustomHelper::getUserWalletAmountByid(Auth::user()->id) !!} </label>
                            </div>
                            <input type="hidden" name="balance" value="{!! CustomHelper::getUserWalletAmountByid(Auth::user()->id) !!}" />
                            @error('balance')
                                <span class="col-md-12 text-danger">{{ $message }}</span>
                            @enderror
                        @endif

                    </div>

                    <div class="row">
                        <div class="col-md-6 text-left">
                            <input name="payment_by" id="payment_wallet" type="radio" value="2">
                            <label for="payment_wallet">
                                By Reward
                            </label>
                        </div>
                        @if (Auth::guard('web')->user() && Auth::guard('web')->user()->userdetail)
                            <div class="col-md-6 text-right">
                                <label><b>Balance:</b> {!! CustomHelper::getUserWalletGiftByid(Auth::user()->id) !!} </label>
                            </div>
                            <input type="hidden" name="giftbalance" value="{!! CustomHelper::getUserWalletGiftByid(Auth::user()->id) !!}" />
                            @error('giftbalance')
                                <span class="col-md-12 text-danger">{{ $message }}</span>
                            @enderror
                        @endif

                    </div>

                </div>
            </div>
        </div>
        <!--/ End Order Widget -->

        <!-- Button Widget -->
        <div class="single-widget get-button">
            <div class="content">
                <div class="button">
                    <button type="submit" class="btn" @if (
                        !(Auth::guard('web')->user() &&
                            Auth::guard('web')->user()->userdetail &&
                            (\Cart::session('normal')->getContent()->count() ||
                                \Cart::session('discount')->getContent()->count() ||
                                \Cart::session('vendor')->getContent()->count())
                        )) disabled @endif>proceed to
                        checkout</button>
                </div>
            </div>
        </div>
        <!--/ End Button Widget -->
    </div>
</div>
