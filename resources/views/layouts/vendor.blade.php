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
        href="{{ VendorHelper::getVendorLogo() != '' ? VendorHelper::getVendorLogo() : asset('img/logo.png') }}" />

    {{-- for dynamic styling --}}
    @includeIf('include.vendor_style')
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
                <ul class="navbar-nav navbar-right">.
                    <li class="dropdown">
                        <a href="{{ route('dashboard') }}" class="nav-link nav-link-lg"><i
                                data-feather="refresh-ccw"></i>
                        </a>
                    </li>
                    <li class="dropdown dropdown-list-toggle">
                        <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg">
                            <i data-feather="bell" class="bell"></i>
                            <span class="badge headerBadge1 bg-info" id="countunreadnotification" style="">
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                            <div class="dropdown-header">
                                Notifications
                                <div class="float-right">
                                    <a href="#" id="mark-all">Mark All As Read</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons" id="notificationList">
                                {{-- <a href="#" class="dropdown-item"> --}}
                                {{-- dropdown-item-unread --}}
                                {{-- <span class="dropdown-item-icon bg-primary text-white">
                                        <i class="fas fa-code"></i>
                                    </span>
                                    <span class="dropdown-item-desc"> Template update is
                                        available now!
                                        <span class="time">2 Min Ago</span>
                                    </span>
                                </a> --}}
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="{{ route('vendor.notification.list') }}">View All <i
                                        class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li>

                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                src="{{ Auth::guard('web')->user()->image ? asset('uploads/user_profile') . '/' . Auth::guard('web')->user()->image : asset('img/users/user-3.png') }}"
                                class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">{{ VendorHelper::getVendorBusinessname() }}</div>
                            <a href="{{ route('vendor.setting.business.load') }}" class="dropdown-item has-icon">
                                <span style="color: #191d21;">
                                    <i class="far fa-user"></i>
                                    Buisness Setting
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
                        <a href="{{ route('vendor.dashboard') }}"> <img alt="image"
                                src="{{ VendorHelper::getVendorLogo() ? VendorHelper::getVendorLogo() : asset('img/logo.png') }}"
                                class="header-logo" /> <span
                                class="logo-name">{{ VendorHelper::getVendorBusinessname() ? VendorHelper::getVendorBusinessname() : 'Business Name' }}</span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Main</li>
                        <li class="dropdown">
                            <a href="{{ route('vendor.dashboard') }}" class="nav-link">
                                <i data-feather="monitor"></i>
                                <span> Dashboard</span>
                            </a>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="git-branch"></i><span>Product</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('vendor.product.add') }}">Add Product</a>
                                    <a class="nav-link" href="{{ route('vendor.product.list') }}">Product List</a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="{{ route('vendor.wallet.list') }}" class="nav-link">
                                <i data-feather="bold"></i>
                                <span> Wallet</span>
                            </a>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Order</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('vendor.order.list') }}">
                                        <span> Order List</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Balance</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('vendor.balance.add') }}">Add Balance</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('vendor.balance.history') }}">Balance
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
                                    <a class="nav-link" href="{{ route('vendor.withdraw.add') }}">Withdrawal
                                        Request</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('vendor.withdraw.history') }}">Withdrawal
                                        History
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
                                    <a class="nav-link" href="{{ route('vendor.ticket.add') }}">
                                        <span> Add Service Request</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('vendor.ticket.list') }}">
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
    <script>
        $(document).ready(function() {



            getUnreadNotification();

            function getUnreadNotification() {
                $.ajax({
                    url: "{{ route('vendor.notification.unread') }}",
                    type: 'GET',
                    dataType: 'json',
                    // data: {
                    //     "id": id,
                    //     "_token": token,
                    // },
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    complete: function() {
                        $(".loader").hide();
                    },
                    success: function(response) {
                        console.log(response.notifications);
                        let notifications = response.notifications;
                        $("span#countunreadnotification").html(response.count ? response.count : 0)
                    }
                });
            }

            $('#mark-all').click(function() {
                getreadNotification()
            });

            function getUnreadNotification() {
                $.ajax({
                    url: "{{ route('vendor.notification.unread') }}",
                    type: 'GET',
                    dataType: 'json',
                    // data: {
                    //     "id": id,
                    //     "_token": token,
                    // },
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    complete: function() {
                        $(".loader").hide();
                    },
                    success: function(response) {
                        let notifications = response.notifications;
                        var output = '';
                        for (let index = 0; index < notifications.length; index++) {
                            const element = notifications[index];
                            const elementdata = notifications[index].data;
                            const D = new Date(element.created_at);

                            const hours = D.getHours();
                            const minutes = D.getMinutes();
                            const seconds = D.getSeconds();
                            const ampm = hours >= 12 ? 'PM' : 'AM'

                            // Convert hours to 12-hour format
                            const formattedHours = hours % 12 || 12;

                            const formattedDate = D.getDate() + "-" + (D.getMonth() + 1) + "-" + D
                                .getFullYear() + " " + formattedHours + ":" + minutes + ":" + seconds +
                                " " + ampm;

                            const unread = (element.read_at) ? 'dropdown-item-unread' : '';
                            const url = "{{ url('') }}" + "/" + elementdata.link;
                            output += '<a href="' + url + '" class="dropdown-item ' +
                                unread + '">';
                            output += '<span class="dropdown-item-icon bg-primary text-white">';
                            const icon = '';
                            if (element.type) {
                                output += '<i class = "fab fa-r-project" ></i>';
                            } else if (element.type == '2') {
                                output += '<i class = "far fa-user" ></i>';
                            } else if (element.type == '3') {
                                output += '<i class = "fab fa-servicestack" ></i>';
                            } else if (element.type == '4') {
                                output += '<i class = "fas fa-shopping-cart" ></i>';
                            } else {
                                output += '<i class = "fas fa-code" ></i>';
                            }
                            output += '</span> <span class="dropdown-item-desc">' + elementdata.message;
                            output += '<span class="time">' + formattedDate +
                                '</span></span></a>';
                        }

                        $("div#notificationList").html(output)
                        $("span#countunreadnotification").html(response.count ? response.count : 0)
                    }
                });
            }

            function getreadNotification(id = null) {
                var token = $("meta[name='csrf-token']").attr("content");
                $.ajax({
                    url: "{{ route('vendor.notification.read') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "id": id,
                        "_token": token,
                    },
                    beforeSend: function() {
                        $(".loader").show();
                    },
                    complete: function() {
                        $(".loader").hide();
                    },
                    success: function(response) {
                        getUnreadNotification();
                    }
                });
            }
        });
    </script>

    @yield('script')

</body>


<!-- blank.html  21 Nov 2019 03:54:41 GMT -->

</html>
