<!doctype html>
<html class="no-js" lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>جامعة السودان للعلوم والتكنولوجيا</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="images/gallery/favicon.ico">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    @php
        if (!function_exists('versioned_asset')) {
            function versioned_asset($path)
            {
                return asset($path) . '?v=' . filemtime(public_path($path));
            }
        }
    @endphp

    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ versioned_asset('css/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/aos.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/style-ar.css') }}">

    <style>
        :root {
            --college-primary: #ce6148;
            /* User Main Color */
            --college-secondary: #a84f3a;
            --college-accent: #C5A059;
            /* Elegant Gold */
            --college-bg: #f5f7fa;
            --text-dark: #2c3e50;
            --text-muted: #6c757d;
        }

        body {
            background-color: var(--college-bg);
            font-family: 'Noto Naskh Arabic', 'Figtree', sans-serif;
        }

        /* Global Header Overrides */
        .topbararea {
            background: var(--college-secondary);
            color: white;
        }

        .headerarea__main__menu nav ul li a:hover {
            color: var(--college-primary);
        }
    </style>

</head>


<body class="body__wrapper">
    <!-- pre loader area start -->
    <div id="back__preloader">
        <div id="back__circle_loader"></div>
        <div class="back__loader_logo">
            <img loading="lazy" src="{{ URL::asset('images/gallery/logo_mini.png') }}" alt="Preload">
        </div>
    </div>
    </div>
    <!-- pre loader area end -->

    <main class="main_wrapper overflow-hidden containe">


        @if(isset($data['name']) && ($data['name'] == 'scientific-affairs'))
            @include('ar/college/header-scientific-affairs')
        @else
            @include('ar/college/header')
        @endif
        @yield('content')
        @include('ar/footer')









    </main>






    <!-- JS here -->
    <script src="{{ URL::asset('js/vendor/modernizr-3.5.0.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/vendor/jquery-3.6.0.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/popper.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/bootstrap.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/isotope.pkgd.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/slick.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/jquery.meanmenu.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/ajax-form.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/wow.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/jquery.scrollUp.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/imagesloaded.pkgd.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/jquery.magnific-popup.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/waypoints.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/jquery.counterup.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/plugins.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/swiper-bundle.min.js?' . strtotime('now')) }}"></script>
    <script src="{{ URL::asset('js/main.js?' . strtotime('now')) }}"></script>




</body>

</html>