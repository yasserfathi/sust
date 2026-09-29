<!doctype html>
<html class="no-js" lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('partials.seo-head', ['locale' => 'ar'])

    <link rel="shortcut icon" type="image/x-icon" href="{{ versioned_asset('images/gallery/favicon.ico') }}">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->


    <link rel="stylesheet" href="{{ versioned_asset('css/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/aos.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/style-ar.min.css') }}">

</head>


<body class="body__wrapper">
    <!-- pre loader area start -->
    <div id="back__preloader">
        <div id="back__circle_loader"></div>
        <div class="back__loader_logo">
            <img loading="lazy" src="{{ versioned_asset('images/gallery/logo_mini.png') }}" alt="Preload">
        </div>
    </div>
    <!-- pre loader area end -->

    <main class="main_wrapper overflow-hidden">

        @if(isset($data['name']) && ($data['name'] == 'scientific-affairs'))
            @include('ar/college/header-scientific-affairs')
        @else
            @include('ar/college/header')
        @endif

        <div data-aos="fade-in" data-aos-duration="1200" data-aos-delay="300" data-aos-once="true">
            @yield('content')
        </div>

        <div data-aos="fade-up" data-aos-duration="1000" data-aos-once="true" data-aos-anchor-placement="top-bottom">
            @include('ar/footer')
        </div>

    </main>


    <!-- JS here -->
    <script src="{{ versioned_asset('js/popper.min.js') }}"></script>
    <script src="{{ versioned_asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ versioned_asset('js/plugins.js') }}"></script>
    <script src="{{ versioned_asset('js/swiper-bundle.min.js') }}"></script>
    <script src="{{ versioned_asset('js/main.js') }}"></script>


</body>

</html>