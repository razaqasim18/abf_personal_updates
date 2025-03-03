<!DOCTYPE html>
<html lang="en">
<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->

<head>
    <meta charset="UTF-8">
    <!--<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') </title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bundles/bootstrap-social/bootstrap-social.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('bundles/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon'
        href=' {{ SettingHelper::getSettingValueBySLug('site_favicon') ? asset('uploads/setting/' . SettingHelper::getSettingValueBySLug('site_favicon')) : asset('img/favicon.ico') }}' />

    {{-- for dynamic styling --}}
    @includeIf('include.style')
    @yield('style')
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <section class="section">
            @yield('content')
        </section>
    </div>
    <!-- General JS Scripts -->
    <script src="{{ asset('js/app.min.js') }}"></script>
    <!-- JS Libraies -->
    <!-- Page Specific JS File -->

    <script src="{{ asset('bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('js/custom.js') }}"></script>
    @yield('script')
</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->

</html>
