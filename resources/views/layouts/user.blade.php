<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name='csrf-token' content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bundles/izitoast/css/iziToast.min.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('bundles/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    @yield('style')
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon'
        href='{{ SettingHelper::getSettingValueBySLug('site_favicon') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_favicon')) : asset('img/favicon.ico') }}' />

    {{-- for dynamic styling --}}
    @includeIf('include.style')
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn">
                                <i data-feather="align-justify"></i></a></li>
                        <li><a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize"></i>
                            </a></li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">

                    @if (VendorHelper::getVendorExists())
                        <li class="dropdown">
                            <a href="{{ route('vendor.dashboard') }}" class="nav-link nav-link-lg"><i
                                    data-feather="refresh-ccw"></i>
                            </a>
                        </li>
                    @endif

                    <li class="dropdown">
                        <a href="{{ route('cart.index') }}" class="nav-link nav-link-lg"><i
                                data-feather="shopping-cart"></i>
                            <span class="badge customheaderBadge1 bg-info"
                                style=" position: absolute;top: 4px;right: 0px;font-weight: 300;padding: 3px 6px;border-radius: 10px;">
                        </a>
                    </li>

                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                src="{{ Auth::guard('web')->user()->image ? asset('uploads/user_profile') . '/' . Auth::guard('web')->user()->image : asset('img/users/user-3.png') }}"
                                class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">Hello {{ Auth::guard('web')->user()->name }}</div>
                            <a href="{{ route('profile.load') }}" class="dropdown-item has-icon">
                                <span style="color: #191d21;">
                                    <i class="far fa-user"></i>
                                    Profile
                                </span>
                            </a>
                            <a href="{{ route('payment.information.load') }}" class="dropdown-item has-icon">
                                <span style="color: #191d21;">
                                    <i class="fab fa-paypal"></i>
                                    Payment Information
                                </span>
                            </a>
                            <a href="{{ route('password.load') }}" class="dropdown-item has-icon">
                                <span style="color: #191d21;">
                                    <i class="fas fa-cog"></i>
                                    Change Password
                                </span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper" style="padding:10px 0">
                    <div class="sidebar-brand">
                        <a href="{{ route('dashboard') }}"> <img alt="image"
                                src="{{ SettingHelper::getSettingValueBySLug('site_logo') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_logo')) : asset('img/logo.png') }}"
                                class="header-logo" /> <span class="logo-name">{{ env('APP_NAME') }}</span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Main</li>
                        <li class="dropdown">
                            <a href="{{ route('dashboard') }}" class="nav-link">
                                <i data-feather="monitor"></i>
                                <span> Dashboard</span>
                            </a>
                        </li>
                        <li class="menu-header">Credit</li>
                        <li class="dropdown">
                            <a href="{{ route('report.monthly.commission') }}" class="nav-link">
                                <i data-feather="bold"></i>
                                <span> Monthly Commission</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Balance</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('balance.add') }}">Add Balance</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('balance.history') }}">Balance
                                        History
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>WithDraw</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('withdraw.add') }}">Withdrawal Request</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('withdraw.history') }}">Withdrawal
                                        History
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Fund Transfer</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('transfer.add') }}">Fund Transfer</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('transfer.list.send') }}">Send Fund Transfer
                                        List
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('transfer.list.receive') }}">Received Fund
                                        Transfer
                                        List
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Wallet To Reward</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('transfer.reward.add') }}">Wallet To
                                        Reward</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('transfer.reward.list') }}">Wallet To Reward
                                        List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-header">Buy</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Buy RG-code</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('buy.rgcode.add') }}">
                                        <span>RG-code</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('buy.rgcode.list') }}">
                                        <span>RG-code List</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-header">Team</li>
                        <li class="dropdown">
                            <a href="{{ route('team.list', ['id' => \Crypt::encryptString(Auth::guard('web')->user()->id)]) }}"
                                class="nav-link">
                                <i data-feather="users"></i>
                                <span> My Team</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Order</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('order.index') }}">
                                        <span> Order List</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-header">Service Request</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Service Request</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('ticket.add') }}">
                                        <span> Add Service Request</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('ticket.list') }}">
                                        <span> Service Request List</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </aside>
            </div>

            <!-- Main Content -->
            @yield('content')

        </div>
        <footer class="main-footer">
            <div class="footer-left">
                <a href="https://trylotech.com">Trylotech</a></a>
            </div>
            <div class="footer-right">
            </div>
        </footer>
    </div>
    </div>
    <!-- General JS Scripts -->
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('bundles/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/page/sweetalert.js') }}"></script>
    <script src="{{ asset('bundles/izitoast/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/page/toastr.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('js/custom.js') }}"></script>
    {{-- custom pages js --}}
    @yield('script')
    <script>
        getItemList();

        function getItemList() {
            $.ajax({
                url: '{{ route('cart.list') }}',
                method: "GET",
                // data: {
                //     _token: '{{ csrf_token() }}',
                //     productid: productid,
                // },
                beforeSend: function() {
                    $(".preloader").show();
                },
                complete: function() {
                    $(".preloader").hide();
                },
                success: function(response) {
                    var item = '';
                    $("span.customheaderBadge1").text(response.count ? response.count : 0);

                }
            });
        }
    </script>
</body>


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->

</html>
