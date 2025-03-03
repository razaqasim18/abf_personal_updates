@if (isset($product->vendor_id) && $product->vendor_id != 0)
    <div class="container p-0 mb-2">
        <h3>Comments</h3>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="product__details__tab">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#reviews" role="tab"
                                aria-selected="true">Reviews
                                <span>({{ count($product->comments) }})</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#leavereview" role="tab"
                                aria-selected="false">Leave A
                                Review</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane mt-3 active" id="reviews" role="tabpanel">
                            <div class="product__details__tab__desc" id="displayProduct">
                                <!-- Main Blade Template -->
                                @include('include.partials.comments', [
                                    'product' => $product,
                                ])
                            </div>
                        </div>

                        <div class="tab-pane mt-3" id="leavereview" role="tabpanel">
                            @if (Auth::check())
                                <form action="{{ route('vendor.product.comment') }}" method="post">
                                    @csrf
                                    <div>
                                        <div class="rating">
                                            <input type="radio" name="rating" value="5" id="5"><label
                                                for="5">☆</label>
                                            <input type="radio" name="rating" value="4" id="4"><label
                                                for="4">☆</label>
                                            <input type="radio" name="rating" value="3" id="3"><label
                                                for="3">☆</label>
                                            <input type="radio" name="rating" value="2" id="2"><label
                                                for="2">☆</label>
                                            <input type="radio" name="rating" value="1" id="1"><label
                                                for="1">☆</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Leave a Comment</label>
                                        <input type="hidden" name="productid" value="{{ $product->id }}" />
                                        <textarea name="content" class="form-control" rows="3" required></textarea>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn">Submit</button>
                                    </div>
                                </form>
                            @else
                                <div>
                                    <h3 class="text-center">Please Login To Leave A Review</h3>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
