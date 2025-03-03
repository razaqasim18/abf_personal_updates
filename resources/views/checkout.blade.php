@extends('layouts.eshop')
@section('style')
@endsection

@section('content')
    <!-- Start Checkout -->
    <section class="shop checkout section">
        <div class="container">
            <form class="form" method="post" action="{{ route('checkout.process') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                    </div>

                    <div class="col-lg-8 col-12">
                        @if (Auth::guard('web')->user() && Auth::guard('web')->user()->userdetail)
                            <div class="checkout-form">
                                <h2>Make Your Checkout Here</h2>
                                <p>Where You want us to deliver </p>
                                <!-- Form -->
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <label>First Name<span>*</span></label>
                                            <input type="text" name="name" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->name }}">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Email Address<span>*</span></label>
                                            <input type="email" name="email" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->email }}">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Phone Number<span>*</span></label>
                                            <input type="number" name="phone" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->phone }}">
                                            @error('phone')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>City<span>*</span></label>
                                            <select class="city" name="city" required>
                                                <option value="">Select Option</option>
                                                @foreach ($city as $row)
                                                    <option value="{{ $row->id }}"
                                                        @if (Auth::guard('web')->user()->userdetail->city_id == $row->id) selected @endif>
                                                        {{ $row->city }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('city')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Street<span>*</span></label>
                                            <input type="text" name="street" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->userdetail->street }}">
                                            @error('street')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Address<span>*</span></label>
                                            <input type="text" name="address" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->userdetail->address }}">
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Shipping Address</label>
                                            <input type="text" name="shipping_address" placeholder="" required="required"
                                                value="{{ Auth::guard('web')->user()->userdetail->shipping_address }}">
                                            @error('shipping_address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-12">
                                        <div class="form-group">
                                            <label>Other Information</label>
                                            <input type="text" name="other" placeholder="">
                                            @error('other')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>

                                <!--/ End Form -->
                            </div>
                        @else
                            <div class="checkout-form">
                                <p>Please add your shipping details for checkout
                                </p>
                                <a class="text-primary" href="{{ route('profile.load') }}">Profile Information</a>
                            </div>
                        @endif
                    </div>

                    @if (Count(\Cart::session('normal')->getContent()))
                        @include('include.checkout.normal')
                    @endif
                    @if (Count(\Cart::session('vendor')->getContent()))
                        @include('include.checkout.vendor')
                    @endif
                </div>
            </form>
        </div>
    </section>
    <!--/ End Checkout -->
@endsection
@section('script')
    <script></script>
@endsection
