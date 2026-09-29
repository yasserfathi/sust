@php
    $hasCv = false;
    if (isset($user)) {
        $hasCv = \App\Models\Staff_resume::where('user_id', $user->id)
            ->where(function($q) {
                $q->whereNotNull('file')->where('file', '!=', '')
                  ->orWhereNotNull('file_en')->where('file_en', '!=', '');
            })->exists();
    }
@endphp
<!doctype html>
<html class="no-js" lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('partials.seo-head', ['locale' => 'ar'])

    <link rel="shortcut icon" type="image/x-icon" href="{{ versioned_asset('img/favicon.ico') }}">

    <link rel="stylesheet" href="{{ versioned_asset('css/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/aos.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/style-ar.min.css') }}">
    <link rel="stylesheet" href="{{ versioned_asset('css/profile-ar.css') }}?v=1.3">
    @stack('styles')
</head>

<body class="body__wrapper arabic-profile">
    <div id="back__preloader">
        <div id="back__circle_loader"></div>
        <div class="back__loader_logo">
            <img loading="lazy" src="{{ versioned_asset('images/gallery/logo_mini.png') }}" alt="Preload">
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
                                    <img loading="lazy" src="{{ versioned_asset('images/gallery/sust-logo-en.png') }}"
                                        alt="sust logo" class="img-fluid">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 main_menu_wrap">
                        <div class="headerarea__main__menu">
                            <nav>
                                <ul>
                                    <li><a class="headerarea__has__dropdown"
                                            href="{{ route('staff_home_ar', $user?->slug ?? '') }}">الرئيسية</a></li>
                                    <li><a href="{{ $hasCv ? route('staff.cv_ar', $user?->slug ?? '') : '#' }}">السيرة الذاتية</a></li>
                                    <li><a href="mailto:{{ $user?->email ?? '' }}">تواصل</a></li>
                                    @php
                                        $currentRoute = Route::currentRouteName();
                                        $switchUrl = route('home');
                                        if ($currentRoute) {
                                            $enRoute = str_ends_with($currentRoute, '_ar') ? substr($currentRoute, 0, -3) : $currentRoute;
                                            if (Route::has($enRoute)) {
                                                try {
                                                    $switchUrl = route($enRoute, Route::current()->parameters());
                                                } catch (\Exception $e) {
                                                }
                                            } elseif (isset($user) && isset($user->name_en)) {
                                                $switchUrl = route('staff_home', $user->slug);
                                            }
                                        }
                                    @endphp
                                    <li><a href="{{ $switchUrl }}">English</a></li>
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
                            <a class="logo__dark" href="{{ url('/ar') }}">
                                <img loading="lazy" src="{{ versioned_asset('images/gallery/sust-logo.png') }}"
                                    alt="logo">
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
                            <li><a class="headerarea__has__dropdown"
                                    href="{{ route('staff_home_ar', $user?->slug ?? '') }}">الرئيسية</a></li>
                            <li><a href="{{ $hasCv ? route('staff.cv_ar', $user?->slug ?? '') : '#' }}">السيرة الذاتية</a></li>
                            <li><a href="mailto:{{ $user?->email ?? '' }}">تواصل</a></li>
                            <li><a href="{{ $switchUrl }}">English</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="mobile-social-wrapper">
                <a href="https://web.facebook.com/sustech.edu" target="_blank">
                    <i class="icofont-facebook mobile-social-icon facebook"></i>
                </a>
                <a href="https://x.com/sudanuniv_SUST" target="_blank">
                    <i class="icofont-twitter mobile-social-icon twitter"></i>
                </a>
                <a href="https://www.instagram.com/sustuniv/" target="_blank">
                    <i class="icofont-instagram mobile-social-icon instagram"></i>
                </a>
                <a href="https://www.youtube.com/channel/UCfZt0JbAzY69o4PUbacTD8A" target="_blank">
                    <i class="icofont-youtube mobile-social-icon youtube"></i>
                </a>
                <a href="https://www.linkedin.com/company/sudan-university-of-science-and-technology-sust-/posts/?feedView=all"
                    target="_blank">
                    <i class="icofont-linkedin mobile-social-icon linkedin"></i>
                </a>
            </div>
        </div>
    </div>
    @if (session('error'))
        <div class="container mt-3" dir="rtl">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
    <div data-aos="fade-in" data-aos-duration="1200" data-aos-delay="300" data-aos-once="true">
        @yield('content')
    </div>

    <div class="footerarea" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true" data-aos-anchor-placement="top-bottom">
        <div class="container">
            <div class="footerarea__copyright__wrapper footerarea__copyright__wrapper__2">
                <div class="row">
                    <div class="col-xl-9 col-lg-9">
                        <div class="footerarea__copyright__content footerarea__copyright__content__2">
                            <p><span>{{ date('Y') }}</span> محفوظة لجامعة السودان للعلوم والتكنولوجيا. جميع الحقوق
                                محفوظة.</p>
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
    <script src="{{ versioned_asset('js/popper.min.js') }}"></script>
    <script src="{{ versioned_asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ versioned_asset('js/plugins.js') }}"></script>
    <script src="{{ versioned_asset('js/main.js') }}"></script>
    @stack('scripts')
</body>

</html>