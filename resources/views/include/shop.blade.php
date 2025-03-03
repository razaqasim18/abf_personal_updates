<div class="row">

    @forelse ($product as $row)
        @php
            if (isset($row->vendor_id)) {
                $link = 'vendor.product.detail';
            } else {
                $link = 'product.detail';
            }
        @endphp
        <div class="col-lg-4 col-md-6 col-12">
            <div class="single-product colstyle">
                <div class="product-img">
                    <a href="{{ route($link, Crypt::encrypt($row->id)) }}">
                        <img class="default-img"
                            src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                            alt="{{ $row->product }}">
                        <img class="hover-img"
                            src="{{ $row->image ? asset('uploads/product') . '/' . $row->image : asset('img/products/product-1.png') }}"
                            alt="{{ $row->product }}">
                        @if ($row->in_stock == 0 || $row->in_stock <= 0)
                            <span class="out-of-stock">Out of stock</span>
                        @else
                            @if (isset($row->vendor_id) && $row->discount > 0)
                                <span class="price-dec">Discount</span>
                            @endif
                        @endif

                    </a>
                    <div class="button-head">
                        <div class="product-action">
                            <a class="viewProduct" title="Quick View" style=" height: 29px;"
                                href="{{ route($link, Crypt::encrypt($row->id)) }}"><i class=" ti-eye"></i><span>Quick
                                    Shop</span></a>
                        </div>
                        <div class="product-action-2">
                            @if ($row->in_stock == 0 || $row->in_stock <= 0)
                                <a href="javascript:void(0)">Out of stock</a>
                            @else
                                <a title="Add to cart" href="javascript:void(0)" id="addToCart"
                                    @if (isset($row->vendor_id)) data-isvendor="1" @else data-isvendor="0" @endif
                                    data-productid="{{ $row->id }}">Add to cart</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="product-content">
                    <h3>
                        <a href="{{ route($link, Crypt::encrypt($row->id)) }}">{{ $row->product }}</a>
                    </h3>
                    @if ($row->vendor_id)
                        <div class="align-rative-div">
                            <div class="rating">
                                @for ($i = 5; $i > 0; $i--)
                                    @if ($row->rating >= $i)
                                        <i class="fas fa-star"></i> <!-- Full star -->
                                    @elseif ($row->rating >= $i - 0.5)
                                        <i class="fas fa-star-half-alt"></i> <!-- Half star -->
                                    @else
                                        <i class="far fa-star"></i> <!-- Empty star -->
                                    @endif
                                @endfor
                            </div>
                            ({{ $row->rating }})
                        </div>
                    @endif
                    @if (isset($row->vendor_id) && $row->discount > 0)
                        <div class="product-price">

                            <span>
                                PKR
                                {{ (int) $row->price - ((int) $row->price * $row->discount) / 100 }}
                            </span>&nbsp;<span>(SP {{ $row->points }})</span>
                            <br />
                            <sub>
                                PKR
                                <del>{{ $row->price }}</del>
                            </sub>
                        </div>
                    @else
                        <div class="product-price">
                            <span>PKR {{ $row->price }}</span>&nbsp;<span>(SP {{ $row->points }})</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-lg-12 col-md-12 col-12 colstyle">
            <div class="text-center">
                <h4>No Record Found!</h4>
            </div>
        </div>
    @endforelse
    <div class="col-lg-12 col-md-12 col-12">
        <div id="paginationLinks" style="display: flex;">
            {{ $product->links() }}
        </div>
    </div>

</div>
