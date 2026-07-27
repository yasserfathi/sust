<!doctype html>
<html class="no-js" lang="ar" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Staff Profile')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('img/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/aos.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    @stack('styles')
</head>

<body class="body__wrapper arabic-profile">
    <div id="back__preloader">
        <div id="back__circle_loader"></div>
        <div class="back__loader_logo">
            <img loading="lazy" src="{{ asset('images/gallery/logo_mini.png') }}" alt="Preload">
        </div>
    </div>
    <header>
        <div class="headerarea headerarea__3 header__sticky header__area">
            <div class="container desktop__menu__wrapper">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-6">
                        <div class="headerarea__left">
                            <div class="headerarea__left__logo">
                                <a href="{{ url('/') }}">
                                    <img loading="lazy" src="{{ asset('images/gallery/sust-logo-en.png') }}"
                                        alt="sust logo" class="img-fluid">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 main_menu_wrap">
                        <div class="headerarea__main__menu">
                            <nav>
                                <ul>
                                    <li><a class="headerarea__has__dropdown" href="{{ url('/') }}">Home</a></li>
                                    <li><a href="#">CV</a></li>
                                    <li><a href="#">Contact</a></li>
@php
    $currentRoute = Route::currentRouteName();
    $switchUrl = route('home_ar');
    if ($currentRoute) {
        $arRoute = $currentRoute . '_ar';
        if (Route::has($arRoute)) {
            try { $switchUrl = route($arRoute, Route::current()->parameters()); } catch (\Exception $e) {}
        } elseif (isset($user) && isset($user->name_en)) {
            $switchUrl = route('staff_home_ar', $user->name_en);
        }
    }
@endphp
                                    <li><a href="{{ $switchUrl }}">عربى</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid mob_menu_wrapper">
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="mobile-logo">
                            <a class="logo__dark" href="{{ url('/') }}">
                                <img loading="lazy" src="{{ asset('images/gallery/sust-logo-en.png') }}" alt="logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="header-right-wrap">
                            <div class="mobile-off-canvas">
                                <a class="mobile-aside-button" href="#"><i class="icofont-navigation-menu"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="mobile-off-canvas-active">
        <a class="mobile-aside-close"><i class="icofont icofont-close-line"></i></a>
        <div class="header-mobile-aside-wrap">
            <div class="mobile-menu-wrap headerarea">
                <div class="mobile-navigation">
                    <nav>
                        <ul class="mobile-menu">
                                    <li><a class="headerarea__has__dropdown" href="{{ url('/') }}">Home</a></li>
                                    <li><a href="#">CV</a></li>
                                    <li><a href="#}">Contact</a></li>
                                    <li><a href="{{ $switchUrl }}">عربى</a></li>
                                </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    @yield('content')

    <div class="footerarea">
        <div class="container">
            <div class="footerarea__copyright__wrapper footerarea__copyright__wrapper__2">
                <div class="row">
                    <div class="col-xl-9 col-lg-9">
                        <div class="footerarea__copyright__content footerarea__copyright__content__2">
                            <p><span>{{ date('Y') }}</span> Copyright © Sudan University of Science and Technology. All rights reserved.</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="footerarea__icon footerarea__icon__2">
                            <ul>
                                <li><a href="//facebook.com"><i class="icofont-facebook"></i></a></li>
                                <li><a href="//twitter.com"><i class="icofont-twitter"></i></a></li>
                                <li><a href="//linkedin.com"><i class="icofont-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/jquery.meanmenu.min.js') }}"></script>
    <script src="{{ asset('js/ajax-form.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/waypoints.min.js') }}"></script>
    <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>