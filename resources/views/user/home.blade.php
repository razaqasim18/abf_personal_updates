@extends('layouts.user')
@section('title')
    User || Dasboard
@endsection
@section('style')
    <style>
        @media (max-width: 600px) {
            img.customsize {
                width: 80px !important;
                height: 80px !important;
                margin-top: 50px;
            }

            .custumh4 {
                /*font-size: 4vh !important;*/
                font-size: 20px;
            }

            .banner {
                height: 218px !important;
            }
        }

        img.customsize {
            width: 150px;
            height: 150px;
        }

        .custumh4 {
            font-size: 18px;
            margin: 0;
        }

        /*.banner {*/
        /*    height: 460px;*/
        /*}*/
    </style>
@endsection
@section('content')
    <div class="main-content" style=" padding-right: 30px; padding-top: 80px;">
        <section class="section">
            <div class="section-body">
                <div class="row">

                    <div class="col-12 col-md-12 col-lg-12 banner">
                        <div style="display:block">

                            @if (!count($dashboardbanner))
                                <!--banner-->
                                <img alt="image" src="{{ asset('images/user-panel-banner-original.jpeg') }}" class=""
                                    width="auto" height="auto" style="max-width:100%;">
                            @else
                                <div id="carouselExampleIndicators2" class="carousel slide" data-ride="carousel">

                                    @php $count = 1 @endphp
                                    <ol class="carousel-indicators">
                                        @for ($i = 0; $i < count($dashboardbanner); $i++)
                                            <li data-target="#carouselExampleIndicators2"
                                                data-slide-to="{{ $count }}"
                                                @if ($count == 1) class="active" @endif></li>
                                            @php $count++; @endphp
                                        @endfor
                                    </ol>


                                    <div class="carousel-inner">
                                        @php $counter = 1 @endphp
                                        @foreach ($dashboardbanner as $image)
                                            <div class="carousel-item @if ($counter == 1) active @endif">
                                                <img class="d-block w-100" src="{{ $image->getFirstMediaUrl('images') }}"
                                                    alt="{{ $counter }}" style="max-width:100%;">
                                            </div>
                                            @php $counter++; @endphp
                                        @endforeach
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators2" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators2" role="button"
                                        data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            @endif
                        </div><br />
                        <div class="author-box-center" style="top: -75px;position: relative; display: flex;">
                            <div>
                                <img alt="image"
                                    src="{{ Auth::guard('web')->user()->image ? asset('uploads/user_profile') . '/' . Auth::guard('web')->user()->image : asset('img/users/user-3.png') }}"
                                    class="rounded-circle rounded-circle customsize">
                            </div>
                            <div style="margin: 45px 0 0 10px;">
                                <div class="author-box-name">
                                    <p class="mt-2 custumh4"><b>{{ Auth::guard('web')->user()->name }}</b>
                                        {{ Auth::guard('web')->user()->userpoint ? (Auth::guard('web')->user()->userpoint->commission ? '(' . Auth::guard('web')->user()->userpoint->commission->title . ')' : '') : '' }}
                                    </p>
                                    {{-- <p class="text-center custumh4">My Team:
                                        <b>{{ $myteam ? $myteam->count : 0 }}</b> - Total Team:
                                        <b> {{ $totalteam ? $totalteam - 1 : 0 }}</b>
                                    </p> --}}
                                    {{-- @if (Auth::guard('web')->user()->userpoint)
                                        @if (Auth::guard('web')->user()->userpoint->commission)
                                            @if (Auth::guard('web')->user()->userpoint->commission->ptp)
                                                <p class="custumh4">
                                                    {{ 'MP ' . Auth::guard('web')->user()->userpoint->commission->ptp }}</p>
                                            @endif
                                        @endif
                                    @endif --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <h4>
                        <a href="{{ route('vendor.request.load') }}">Vendor Request</a>
                    </h4> --}}
                    @php
                        // $monthlyptp = 0;
                        // if (Auth::guard('web')->user()->userpoint){
                        //     if (Auth::guard('web')->user()->userpoint->commission){
                        //         if (Auth::guard('web')->user()->userpoint->commission->ptp){
                        //             $monthlyptp = Auth::guard('web')->user()->userpoint->commission->ptp;
                        //             $monthlyptpdiff = $user['personalpointmonthly'] ? ($user['personalpointmonthly']->count ? $user['personalpointmonthly']->count : 0) - $monthlyptp;
                        //         }
                        //     }
                        // }

                        $monthlyptp = 0;
                        $monthlyptpdiff = 0;

                        $userPoint = Auth::guard('web')->user()->userpoint;
                        $commission = $userPoint ? $userPoint->commission : null;
                        $ptp = $commission ? $commission->ptp : null;
                        if ($ptp !== null) {
                            $monthlyptp = $ptp;

                            if ($user['mppointmonthly'] !== null) {
                                $monthlyptpdiff = $monthlyptp - $user['mppointmonthly']->count ?? 0;
                            }
                        }
                    @endphp
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="row">
                            @if (session('error'))
                                <div class="col-12 col-md-12 col-lg-12 text-center p-1">
                                    <div class="alert alert-danger mb-3" role="alert">
                                        {{ session('error') }}
                                    </div>
                                </div>
                            @endif
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center p-1"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Mp Point</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4 class="{{ $monthlyptpdiff <= 0 ? 'text-success' : 'text-danger' }}">
                                                    @if ($monthlyptpdiff >= 0)
                                                        {{ round($monthlyptpdiff) }}
                                                    @else
                                                        {{ round($user['mppointmonthly']->count) }}
                                                    @endif
                                                    <sup style="color: #212529;">{{ $monthlyptp }}</sup>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Monthly Point</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4>
                                                    {{ $user['monthlypoint'] !== null ? round($user['monthlypoint']->totapoint) : 0 }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>My Team</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4> {{ $myteam ? $myteam->count : 0 }} </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Total Team</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4> {{ $totalteam ? $totalteam - 1 : 0 }} </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                @if (VendorHelper::getVendorExists())
                                    <a href="{{ route('vendor.dashboard') }}">

                                        <div class="card card-primary">
                                            <div class="card-header text-center"
                                                style="display:block; padding:6px; color: #212529;">
                                                <h6>Vendor Dashboard</h6>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h4>
                                                            Dashboard
                                                            {{-- <i data-feather="users"></i> --}}
                                                        </h4>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    <div class="card card-primary">
                                        <div class="card-header text-center"
                                            style="display:block; padding:6px; color: #212529;">
                                            <h6>Become A Vendor</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    @if (Auth::guard('web')->user()->is_vendor_allowed == 1 || (isset($user['point']) && $user['point']->point >= 40000))
                                                        @php
                                                            $vendor = \App\Models\VendorRequest::where(
                                                                'user_id',
                                                                Auth::guard('web')->user()->id,
                                                            )->first();
                                                        @endphp
                                                        @if ((isset($vendor->status) && in_array($vendor->status, ['0', '1'])) || !isset($vendor->status))
                                                            <a href="{{ route('vendor.request.load') }}">
                                                                <h4>
                                                                    Eligible
                                                                </h4>
                                                            </a>
                                                        @endif
                                                        @if (isset($vendor->status) && in_array($vendor->status, ['-1', '2', '3']))
                                                            <a href="{{ route('vendor.request.payment.load') }}">
                                                                <h4>
                                                                    Payment
                                                                </h4>
                                                            </a>
                                                        @endif
                                                    @else
                                                        <h4>
                                                            Not-Eligible
                                                        </h4>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>


                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    @if (VendorHelper::getVendorExists())
                                        <div class="card-header text-center"
                                            style="display:block; padding:6px; color: #212529;">
                                            <h6>Vendor Status</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    {!! Auth::guard('web')->user()->vendor->is_blocked
                                                        ? '<h4 class="text-danger">Blocked </h4>'
                                                        : '<h4 class="text-primary">Active </h4>' !!}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card-header text-center"
                                            style="display:block; padding:6px; color: #212529;">
                                            <h6>Vendor Status</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h4>
                                                        Not-Eligible
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center p-1"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Total Sale Point</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4> {{ $user['point'] ? round($user['point']->point) : 0 }} </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Personal Sale Point</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4>
                                                    {{ $user['personalpoint'] ? round($user['personalpoint']->count ? $user['personalpoint']->count : 0) : 0 }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Wallet</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4> {{ $user['wallet'] ? $user['wallet']->amount : 0 }} </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Reward</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4> {{ $user['wallet'] ? $user['wallet']->gift : 0 }} </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Total Earning</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4> {{ $user['walletcommission']->count ? $user['walletcommission']->count : 0 }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Monthly Earning</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h4>
                                                    {{ $user['personalmonthlyearning']->count ? $user['personalmonthlyearning']->count : 0 }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="row">
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Pending Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['pending'] }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px;  color: #212529;">
                                        <h6>Inprocess Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['inprocess'] }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px;     color: #212529;">
                                        <h6>Approved Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['approved'] }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px;     color: #212529;">
                                        <h6>Delivered Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['delivered'] }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px; color: #212529;">
                                        <h6>Reject Orders</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['cancelled'] }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-2 col-lg-2 text-center p-1">
                                <div class="card card-primary">
                                    <div class="card-header text-center"
                                        style="display:block; padding:6px;     color: #212529; color: #212529;">
                                        <h4>Total Orders</h4>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h4> {{ $order['total'] }} </h4>
                                            </div>
                                            <div class="col-6">
                                                <i class="fas fa-shopping-cart card-icon font-22 pt-1 p-r-30"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--<div class="col-12 col-md-12 col-lg-5">-->
                    <!--    <div class="card card-primary author-box">-->
                    <!--        <div class="card-body">-->
                    <!--            <div class="author-box-center">-->
                    <!--                <img alt="image"-->
                    <!--                    src="{{ Auth::guard('web')->user()->image ? asset('uploads/user_profile') . '/' . Auth::guard('web')->user()->image : asset('img/users/user-3.png') }}"-->
                    <!--                    class="rounded-circle author-box-picture" width="100px" height="100px">-->
                    <!--                <div class="clearfix"></div>-->
                    <!--                <div class="author-box-name">-->
                    <!--                    <h4 class="mt-2">{{ Auth::guard('web')->user()->name }}</h4>-->
                    <!--                </div>-->
                    <!--                <div class="author-box-job">-->
                    <!--                    {{ Auth::guard('web')->user()->userpoint ? (Auth::guard('web')->user()->userpoint->commission ? Auth::guard('web')->user()->userpoint->commission->title : '') : '' }}-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--            <div class="text-center">-->
                    <!--                <div class="author-box-description">-->
                    <!--                    <p class="mb-0">-->
                    <!--                        <strong>ID: </strong>-->
                    <!--                        {{ 'ABF-' . Auth::guard('web')->user()->id }}-->
                    <!--                    </p>-->
                    <!--                </div>-->
                    <!--                <div class="w-100 d-sm-none"></div>-->
                    <!--            </div>-->
                    <!--            <div class="py-2">-->
                    <!--                <p class="clearfix mb-1">-->
                    <!--                    <span class="float-left">-->
                    <!--                        Birthday-->
                    <!--                    </span>-->
                    <!--                    <span class="float-right text-muted">-->
                    <!--                        {{ date('d M Y h:i A', strtotime(Auth::guard('web')->user()->dob)) }}-->
                    <!--                    </span>-->
                    <!--                </p>-->
                    <!--                <p class="clearfix mb-1">-->
                    <!--                    <span class="float-left">-->
                    <!--                        Phone-->
                    <!--                    </span>-->
                    <!--                    <span class="float-right text-muted">-->
                    <!--                        {{ Auth::guard('web')->user()->phone }}-->
                    <!--                    </span>-->
                    <!--                </p>-->
                    <!--                <p class="clearfix mb-1">-->
                    <!--                    <span class="float-left">-->
                    <!--                        Mail-->
                    <!--                    </span>-->
                    <!--                    <span class="float-right text-muted">-->
                    <!--                        {{ Auth::guard('web')->user()->email }}-->
                    <!--                    </span>-->
                    <!--                </p>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->

                    <!--<div class="col-12 col-md-12 col-lg-7">-->
                    <!--    <div class="row">-->
                    <!--        <div class="col-12 col-md-6 col-lg-6 text-center">-->
                    <!--            <div class="card card-primary">-->
                    <!--                <div class="card-header text-center"-->
                    <!--                    style="display:block; padding:6px; color: #212529;">-->
                    <!--                    <h4>Total Sale Point</h4>-->
                    <!--                </div>-->
                    <!--                <div class="card-body p-1">-->
                    <!--                    <div class="row">-->
                    <!--                        <div class="col-6">-->
                    <!--                            <h3> {{ $user['point'] ? $user['point']->point : 0 }} </h3>-->
                    <!--                        </div>-->
                    <!--                        <div class="col-6 text-right">-->
                    <!--                            <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->

                    <!--        <div class="col-12 col-md-6 col-lg-6 text-center">-->
                    <!--            <div class="card card-primary">-->
                    <!--                <div class="card-header text-center"-->
                    <!--                    style="display:block; padding:6px; color: #212529;">-->
                    <!--                    <h4>Personal Sale Point</h4>-->
                    <!--                </div>-->
                    <!--                <div class="card-body p-1">-->
                    <!--                    <div class="row">-->
                    <!--                        <div class="col-6">-->
                    <!--                            <h3> {{ $user['personalpoint'] ? $user['personalpoint']->count : 0 }} </h3>-->
                    <!--                        </div>-->
                    <!--                        <div class="col-6 text-right">-->
                    <!--                            <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->

                    <!--        <div class="col-12 col-md-6 col-lg-6 text-center">-->
                    <!--            <div class="card card-primary">-->
                    <!--                <div class="card-header text-center"-->
                    <!--                    style="display:block; padding:6px; color: #212529;">-->
                    <!--                    <h4>Wallet</h4>-->
                    <!--                </div>-->
                    <!--                <div class="card-body p-1">-->
                    <!--                    <div class="row">-->
                    <!--                        <div class="col-6">-->
                    <!--                            <h3> Rs: {{ $user['wallet'] ? $user['wallet']->amount : 0 }} </h3>-->
                    <!--                        </div>-->
                    <!--                        <div class="col-6 text-right">-->
                    <!--                            <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->

                    <!--        <div class="col-12 col-md-6 col-lg-6 text-center">-->
                    <!--            <div class="card card-primary">-->
                    <!--                <div class="card-header text-center"-->
                    <!--                    style="display:block; padding:6px; color: #212529;">-->
                    <!--                    <h4>Reward</h4>-->
                    <!--                </div>-->
                    <!--                <div class="card-body p-1">-->
                    <!--                    <div class="row">-->
                    <!--                        <div class="col-6">-->
                    <!--                            <h3> Rs: {{ $user['wallet'] ? $user['wallet']->gift : 0 }} </h3>-->
                    <!--                        </div>-->
                    <!--                        <div class="col-6 text-right">-->
                    <!--                            <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->

                    <!--        <div class="col-12 col-md-6 col-lg-6 text-center">-->
                    <!--            <div class="card card-primary">-->
                    <!--                <div class="card-header text-center"-->
                    <!--                    style="display:block; padding:6px; color: #212529;">-->
                    <!--                    <h4>Total Earning</h4>-->
                    <!--                </div>-->
                    <!--                <div class="card-body p-1">-->
                    <!--                    <div class="row">-->
                    <!--                        <div class="col-6">-->
                    <!--                            <h3> Rs: {{ $user['wallet'] ? $user['wallet']->amount : 0 }} </h3>-->
                    <!--                        </div>-->
                    <!--                        <div class="col-6 text-right">-->
                    <!--                            <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--        <div class="col-12 col-md-6 col-lg-6 text-center">-->
                    <!--            <div class="card card-primary">-->
                    <!--                <div class="card-header text-center"-->
                    <!--                    style="display:block; padding:6px; color: #212529;">-->
                    <!--                    <h4>Monthly Earning</h4>-->
                    <!--                </div>-->
                    <!--                <div class="card-body p-1">-->
                    <!--                    <div class="row">-->
                    <!--                        <div class="col-6">-->
                    <!--                            <h3> Rs:-->
                    <!--                                {{ $user['personalmonthlyearning']->count ? $user['personalmonthlyearning']->count : 0 }}-->
                    <!--                            </h3>-->
                    <!--                        </div>-->
                    <!--                        <div class="col-6 text-right">-->
                    <!--                            <i class="fas fa-shopping-cart card-icon font-30 p-r-20"></i>-->
                    <!--                        </div>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <!--            </div>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->
                </div>

            </div>
        </section>
    </div>
@endsection
@section('script')
@endsection
