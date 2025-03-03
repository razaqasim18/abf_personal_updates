<div>
    <div class="container p-0 mb-2">
        <h3>Profile Detail</h3>
    </div>
    <div class="container p-0">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 m-0 p-0" style="display: grid;  align-items: end;">
                <div class="row m-0 p-2">
                    <div class="col-md-2 text-center">
                        <img style="height: 150px; width: 160px; border-radius:50%; padding: 10px 0px;"
                            src="{{ $vendor->business_logo ? $vendor->business_logo : asset('img/users/user-3.png') }}"
                            alt="business logo">
                    </div>
                    <div class="col-md-10" style="display: grid; align-items: end;">
                        <h4>{{ $vendor->user->name }} @if ($vendor->user->sponserid)
                                <small>/ {{ 'ABF-' . $vendor->user->id }}</small>
                            @endif
                        </h4>
                        <div class="rating">
                            @for ($i = 5; $i > 0; $i--)
                                @if ($vendor->rating >= $i)
                                    <i class="fas fa-star"></i> <!-- Full star -->
                                @elseif ($vendor->rating >= $i - 0.5)
                                    <i class="fas fa-star-half-alt"></i> <!-- Half star -->
                                @else
                                    <i class="far fa-star"></i> <!-- Empty star -->
                                @endif
                            @endfor
                        </div>
                        <p><strong><i class="fa fa-shopping-cart" aria-hidden="true"></i></strong>
                            {{ $vendor->business_name }}</p>
                        @if ($vendor->business_address)
                            <p><strong><i class="fa fa-map-marker" aria-hidden="true"></i></strong>
                                {{ $vendor->business_address }}</p>
                        @endif
                        <p>{{ Str::limit($vendor->description, 250) }}</p>
                        <p><a style="text-decoration:underline"
                                href="{{ route('vendor.profile.detail', Crypt::encrypt($vendor->id)) }}">More
                                detail</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
