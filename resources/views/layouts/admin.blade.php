<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>@yield('title')</title>
    <meta name='csrf-token' content="{{ csrf_token() }}">
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
        href=' {{ SettingHelper::getSettingValueBySLug('site_favicon') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_favicon')) : asset('img/favicon.ico') }}' />

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
                        <li>
                            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn">
                                <i data-feather="align-justify"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">
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
                                <a href="{{ route('admin.notification.list') }}">View All <i
                                        class="fas fa-chevron-right"></i></a>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                src="{{ Auth::guard('admin')->user()->image ? asset('uploads/admin_profile') . '/' . Auth::guard('admin')->user()->image : asset('img/users/user-3.png') }}"
                                class="user-img-radious-style"> <span class="d-sm-none d-lg-inline-block"></span></a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">Hello {{ Auth::guard('admin')->user()->name }}</div>
                            <a href="{{ route('admin.profile') }}" class="dropdown-item has-icon">
                                <span style="color: #191d21;">
                                    <i class="far fa-user"></i>
                                    Profile
                                </span>
                            </a>
                            <a href="{{ route('admin.password') }}" class="dropdown-item has-icon">
                                <span style="color: #191d21;">
                                    <i class="fas fa-cog"></i>
                                    Change Password
                                </span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.logout') }}" class="dropdown-item has-icon text-danger"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper" style="padding:10px 0">
                    <div class="sidebar-brand">
                        <a href="{{ route('admin.dashboard') }}">
                            <img alt="image"
                                src="{{ SettingHelper::getSettingValueBySLug('site_logo') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_logo')) : asset('img/logo.png') }}"
                                class="header-logo" /> <span class="logo-name">{{ env('APP_NAME') }}</span>
                        </a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="menu-header">Main</li>
                        <li class="dropdown">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                <i data-feather="monitor"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <li class="menu-header">Uploads</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="git-branch"></i><span>Category</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.category.add') }}">Add Category</a>
                                    <a class="nav-link" href="{{ route('admin.category.list') }}">Category List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="git-branch"></i><span>Commission</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.commission.add') }}">Add Commission</a>
                                    <a class="nav-link" href="{{ route('admin.commission.list') }}">Commission
                                        List</a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="git-branch"></i><span>Brand</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.brand.add') }}">Add Brand</a>
                                    <a class="nav-link" href="{{ route('admin.brand.list') }}">Brand List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="git-branch"></i><span>Product</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.product.add') }}">Add Product</a>
                                    <a class="nav-link" href="{{ route('admin.product.list') }}">Product List</a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="git-branch"></i><span>Blog</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.blog.add') }}">Add Blog</a>
                                    <a class="nav-link" href="{{ route('admin.blog.list') }}">Blog List</a>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-header">Request</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="dollar-sign"></i><span>All Request</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.request.epin.list') }}">RG Code
                                    </a>
                                    <a class="nav-link" href="{{ route('admin.request.balance.list') }}">Balance
                                    </a>
                                    <a class="nav-link" href="{{ route('admin.request.withdraw.list') }}">Withdraw
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-header">Rewards</li>
                        <li class="dropdown">
                            <a href="{{ route('admin.reward.referred.add') }}" class="nav-link">
                                <i data-feather="gift"></i>
                                <span>Referred Reward</span>
                            </a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="gift"></i><span>Team Reward</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.reward.team.add') }}">Team Reward Add
                                    </a>
                                    <a class="nav-link" href="{{ route('admin.reward.team.list') }}">Team Reward List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="gift"></i><span>PSP Reward</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.reward.psp.add') }}">PSP Reward Add
                                    </a>
                                    <a class="nav-link" href="{{ route('admin.reward.psp.list') }}">PSP Reward List
                                    </a>
                                </li>
                            </ul>
                        </li>
                        </li>

                        <li class="menu-header">Client</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="users"></i><span>All Client</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.client.list') }}">Client
                                    </a>
                                    <a class="nav-link" href="{{ route('admin.client.log') }}">Client Log
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-header">VENDOR</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="users"></i><span>All Vendor</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.vendor.list') }}">Vendors</a>
                                    <a class="nav-link" href="{{ route('admin.vendor.product.list') }}">Products</a>
                                    <a class="nav-link" href="{{ route('admin.vendor.category.load') }}">Category</a>
                                    <a class="nav-link" href="{{ route('admin.vendor.subcategory.load') }}">Vendor
                                        Category</a>
                                    <a class="nav-link"
                                        href="{{ route('admin.vendor.other.terms-condition.load') }}">Terms
                                        & Condition
                                    </a>
                                    <a class="nav-link"
                                        href="{{ route('admin.vendor.other.privacy-policy.load') }}">Privacy Policy
                                    </a>
                                    <a class="nav-link" href="{{ route('admin.vendor.other.notes.load') }}">Notes
                                    </a>
                                    <a class="nav-link" href="{{ route('admin.vendor.request.load') }}">Request
                                        Vendor</a>
                                    <a class="nav-link"
                                        href="{{ route('admin.vendor.request.balance.list') }}">Balance
                                    </a>
                                    <a class="nav-link"
                                        href="{{ route('admin.vendor.request.withdraw.list') }}">Withdraw
                                    </a>

                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="dollar-sign"></i><span>Payment Ledger</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.vendor.paymentledger.add') }}">Add
                                        Payment Ledger</a>
                                    <a class="nav-link" href="{{ route('admin.vendor.paymentledger.list') }}">Payment
                                        Ledger
                                        List</a>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-header">Service Request</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Service Request</span></a>
                            <ul class="dropdown-menu">

                                <li>
                                    <a class="nav-link" href="{{ route('admin.ticket.list') }}">
                                        <span> Service Request List</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-header">Business Account</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="dollar-sign"></i><span>Bank</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.bank.add') }}">Add Bank</a>
                                    <a class="nav-link" href="{{ route('admin.bank.list') }}">Bank List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="dollar-sign"></i><span>Business Account</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.business.account.add') }}">Add Business
                                        Account
                                    </a>
                                    <a class="nav-link" href="{{ route('admin.business.account.list') }}">
                                        Business Account
                                        List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="bold"></i><span>Order</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.order.index') }}">
                                        <span> Order List</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('admin.vendor.order.list') }}">
                                        <span> Vendor Order List</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="menu-header">Success Stories</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="settings"></i><span>Success Stories</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.success.story.add') }}">Add Success
                                        Stories</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('admin.success.story.list') }}">Success
                                        Stories List</a>
                                </li>
                            </ul>
                        </li>
                        <li class="menu-header">Setting</li>
                        <li class="dropdown">
                            <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                    data-feather="settings"></i><span>Setting</span></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link" href="{{ route('admin.setting.site') }}">Site Setting</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('admin.setting.charges') }}">Charges
                                        Setting</a>
                                </li>
                                <li>
                                    <a class="nav-link" href="{{ route('admin.setting.banner') }}">Banner
                                        Setting</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </aside>
            </div>
            <!-- Main Content -->
            @yield('content')


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
    <!-- JS Libraies -->
    <script src="{{ asset('bundles/jquery-ui/jquery-ui.min.js') }}"></script>
    @yield('script')
    <!-- Template JS File -->
    <script src="{{ asset('bundles/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/page/sweetalert.js') }}"></script>
    <script src="{{ asset('bundles/izitoast/js/iziToast.min.js') }}"></script>
    <script src="{{ asset('js/page/toastr.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('js/custom.js') }}"></script>

    <script>
        $(document).ready(function() {

            getUnreadNotification();

            function getUnreadNotification() {
                $.ajax({
                    url: "{{ route('admin.notification.unread') }}",
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
                        $("span#countunreadnotification").html(response.count ? response.count : 0)
                    }
                });
            }

            $('#mark-all').click(function() {
                getreadNotification()
            });

            function getUnreadNotification() {
                $.ajax({
                    url: "{{ route('admin.notification.unread') }}",
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
                            console.log(element.type );
                            output += '<a href="' + url + '" class="dropdown-item ' +
                                unread + '">';
                            const style = (elementdata.isvendor && elementdata.isvendor == "1") ? "bg-warning" : "bg-primary";  
                            output += '<span class="dropdown-item-icon '+style+' text-white">';
                            const icon = '';
                            if (elementdata.type == '1') {
                                output += '<i class = "fab fa-r-project" ></i>';
                            } else if (elementdata.type == '2') {
                                output += '<i class = "far fa-user" ></i>';
                            } else if (elementdata.type == '3') {
                                output += '<i class = "fab fa-servicestack" ></i>';
                            } else if (elementdata.type == '4') {
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
                    url: "{{ route('admin.notification.read') }}",
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
</body>

</html>
