<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@hasSection('title')@yield('title') | @endif{{ 'Sudan University of Science & Technology' }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('images/gallery/favicon.ico') }}">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    @php
        function versioned_asset($path) {
            return asset($path) . '?v=' . filemtime(public_path($path));
        }
    @endphp

    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ versioned_asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/aos.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/style.css') }}">

</head>

<body class="body__wrapper">
    <!-- pre loader area start -->
    <div id="back__preloader">
        <div id="back__circle_loader"></div>
        <div class="back__loader_logo">
            <img loading="lazy" src="{{ asset('images/gallery/logo_mini.png') }}" alt="Preload">
        </div>
    </div>
    <!-- pre loader area end -->

    <main class="main_wrapper overflow-hidden">

        @include('header')
        @yield('content')
        @include('footer')

    </main>

    <!-- JS here -->
    <script src="{{ versioned_asset('js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ versioned_asset('js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ versioned_asset('js/popper.min.js') }}"></script>
    <script src="{{ versioned_asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ versioned_asset('js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ versioned_asset('js/slick.min.js') }}"></script>
    <script src="{{ versioned_asset('js/jquery.meanmenu.min.js') }}"></script>
    <script src="{{ versioned_asset('js/ajax-form.js') }}"></script>
    <script src="{{ versioned_asset('js/wow.min.js') }}"></script>
    <script src="{{ versioned_asset('js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ versioned_asset('js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ versioned_asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ versioned_asset('js/waypoints.min.js') }}"></script>
    <script src="{{ versioned_asset('js/jquery.counterup.min.js') }}"></script>
    <script src="{{ versioned_asset('js/plugins.js') }}"></script>
    <script src="{{ versioned_asset('js/swiper-bundle.min.js') }}"></script>
    <script src="{{ versioned_asset('js/main.js') }}"></script>

</body>

</html>
