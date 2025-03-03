@extends('layouts.eshop')
@section('style')
@endsection

@section('content')
    <!-- Shopping Cart -->
    <div class="shopping-cart section">
        <div class="container">
            @if (Count(\Cart::session('normal')->getContent()))
                @include('include.cart.normal')
            @endif
            @if (Count(\Cart::session('vendor')->getContent()))
                @include('include.cart.vendor')
            @endif

            @if (!(\Cart::session('normal')->getContent()->count() || \Cart::session('vendor')->getContent()->count()))
                @include('include.cart.empty')
            @endif

        </div>
    </div>
    <!--/ End Shopping Cart -->
@endsection
@section('script')
@endsection
