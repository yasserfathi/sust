
<div class="topbararea">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <div class="topbar__right">
                    <div class="topbar__list">
                        <ul>
@php
    $currentRoute = Route::currentRouteName();
    $switchUrl = route('home');
    if ($currentRoute) {
        $enRoute = str_ends_with($currentRoute, '_ar') ? substr($currentRoute, 0, -3) : $currentRoute;
        $excludedRoutes = ['news_ar_detail', 'news_ar_archive', 'ads_ar_detail', 'ads_ar_archive', 'college_news_archive_ar', 'college_news_detail_ar', 'college_ads_archive_ar', 'college_ads_detail_ar'];
        $routeMap = [
            'news_ar_archive' => 'news_archive',
            'news_ar_detail' => 'news_archive',
            'ads_ar_archive' => 'ads_archive',
            'ads_ar_detail' => 'ads_archive',
            'events_ar_archive' => 'events_archive',
            'events_ar_detail' => 'events_detail',
            'college_news_archive_ar' => 'college_news_archive',
            'college_news_detail_ar' => 'college_news_archive',
            'college_ads_archive_ar' => 'college_ads_archive',
            'college_ads_detail_ar' => 'college_ads_archive',
        ];
        if (array_key_exists($currentRoute, $routeMap)) {
            $params = Route::current()->parameters();
            if (in_array($currentRoute, ['college_news_detail_ar', 'college_ads_detail_ar', 'college_news_archive_ar', 'college_ads_archive_ar'])) {
                $switchUrl = route($routeMap[$currentRoute], ['name' => $params['name'] ?? ($data['name'] ?? '')]);
            } else {
                try { $switchUrl = route($routeMap[$currentRoute], $params); } catch (\Exception $e) { $switchUrl = route('home'); }
            }
        } elseif ($currentRoute === 'home_dynamic_page_ar') {
            $slug = Route::current()->parameter('slug');
            $hasEnPage = \App\Models\Page::where('lang', 2)->where('slug', $slug)->exists();
            $switchUrl = $hasEnPage ? route('home_dynamic_page', ['slug' => $slug]) : route('home');
        } elseif (!in_array($currentRoute, $excludedRoutes) && Route::has($enRoute)) {
            try { $switchUrl = route($enRoute, Route::current()->parameters()); } catch (\Exception $e) {}
        } elseif (isset($data['college_type']) && isset($data['name'])) {
            try { $switchUrl = route($data['college_type'] . '_home', ['name' => $data['name']]); } catch (\Exception $e) {}
        }
    }
@endphp
                            <li>
                                <a href="{{ $switchUrl }}" class="custom-lang-btn"><i class="icofont-globe"></i> English</a>
                            </li>
                            <li style="opacity: 0.5; margin: 0 10px;">|</li>
                            <li class="topbar-search">
                                <form action="{{ route('search.index') }}" method="GET">
                                    <input type="hidden" name="lang" value="ar">
                                    <input type="text" name="q" placeholder="بحث..." class="topbar-search-input" value="{{ request('q') }}">
                                    <button type="submit" class="topbar-search-btn"><i class="icofont-search-1"></i></button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="topbar__left" style="display: flex; justify-content: flex-end;">
                    <ul>
                        <li>
                            <i class="icofont-email"></i> admin@sustech.edu
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<header>
    <div class="headerarea headerarea__3 header__sticky header__area">
        <div class="container desktop__menu__wrapper">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="headerarea__left">
                        <div class="headerarea__left__logo">

                            <a href="{{ route('home_ar') }}"><img loading="lazy"
                                    src="{{ URL::to('images/gallery/sust-logo.png') }}" alt="sust logo"
                                    class="img-fluid"></a>
                        </div>

                    </div>
                </div>

                @isset($data['colleges'])
                    <div class="col-xl-9 col-lg-9 main_menu_wrap">
                        <div class="headerarea__main__menu">
                            <nav>
                                <ul>

                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">عن الجامعة <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            <li><a href="{{ route('home_dynamic_page_ar', ['slug' => 'about_sust']) }}">عن الجامعة </a></li>
                                            <li><a href="{{ route('sust_leaders_ar')}}">قيادات الجامعة</a></li>
                                            <li><a href="{{ route('home_dynamic_page_ar', ['slug' => 'vice_chancellor_message']) }}">كلمة مدير
                                                    الجامعة</a></li>
                                        </ul>
                                    </li>

                                    <li class="headerarea__mega__menu">
                                        <a class="headerarea__has__dropdown" href="#">الكليات <i
                                                class="icofont-rounded-down"></i></a>
                                        <div class="headerarea__submenu mega__menu__wrapper">
                                            <div class="row">
                                                @foreach ($data['colleges']?->chunk(7) as $chunk)
                                                    <div class="col-3 mega__menu__single__wrap">
                                                        <ul class="mega__menu__item">
                                                            @foreach ($chunk as $college)
                                                                <li><a
                                                                        href="{{ URL::to('/ar/college/' . $college->slug)}}" target="_blank">{{$college->name}}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>

                                    <li class="headerarea__mega__menu">
                                        <a class="headerarea__has__dropdown" href="#">المراكز والمعاهد <i
                                                class="icofont-rounded-down"></i></a>
                                        <div class="headerarea__submenu mega__menu__wrapper">
                                            <div class="row">
                                                @foreach ($data['centers']?->chunk(7) as $chunk)
                                                    <div class="col-3 mega__menu__single__wrap">
                                                        <ul class="mega__menu__item">
                                                            @foreach ($chunk as $center)
                                                                <li><a
                                                                        href="{{ URL::to('/ar/' . $center->getRawOriginal('college_type') . '/' . $center->slug) }}" target="_blank">{{ $center->name }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">العمادات <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            @foreach ($data['deanships'] as $deanship)
                                                <li><a
                                                        href="{{ URL::to('/ar/deanship/' . $deanship->slug)}}" target="_blank">{{$deanship->name}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>

                                    <li><a href="{{ route('home_dynamic_page_ar', ['slug' => 'administration']) }}">الإدارة</a></li>
                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">الاخبار و الاحداث <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            <li><a href="{{ URL::to('/ar/news')}}">الاخبار</a></li>
                                            <li><a href="{{ URL::to('/ar/ads')}}">الاعلانات</a></li>
                                        </ul>
                                    </li>

                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">المؤتمرات والسمنارات  <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            <li><a href="{{ route('events_ar_archive', ['type' => 'conferences']) }}">المؤتمرات</a></li>
                                            <li><a href="{{ route('events_ar_archive', ['type' => 'seminars']) }}">السمنارات</a></li>
                                            <li><a href="{{ route('events_ar_archive', ['type' => 'workshops']) }}">ورش العمل</a></li>
                                        </ul>
                                    </li>

                                    <li><a href="{{ URL::to('/ar/secretariat/scientific-affairs') }}">الشؤون العلمية</a></li>

                                    <li><a href="#">الطلاب</a></li>

                                    <li><a href="#">المجلات العلمية</a></li>

                                    <li class="lang-switcher-sticky">
                                        <a href="{{ $switchUrl }}" title="English"><i class="icofont-globe"></i> English</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                @endisset
            </div>
        </div>


        <div class="container-fluid mob_menu_wrapper">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="mobile-logo">
                        <a class="logo__dark" href="{{ URL::to('/ar') }}"><img loading="lazy"
                                src="{{ URL::to('images/gallery/sust-logo.png') }}" alt="logo"></a>
                    </div>
                </div>
                <div class="col-6">
                    <div class="header-right-wrap" style="display: flex; justify-content: flex-end; align-items: center;">

                        <div class="mobile-lang" style="margin-left: 15px;">
                            <a href="{{ $switchUrl }}" class="custom-lang-btn" style="font-weight: bold;"><i class="icofont-globe"></i> English</a>
                        </div>
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
    <a class="mobile-aside-close"><i class="icofont  icofont-close-line"></i></a>
    <div class="header-mobile-aside-wrap">
        <div class="mobile-menu-wrap headerarea">

            <div class="mobile-search">
                <form class="search-form" action="{{ route('search.index') }}" method="GET">
                    <input type="hidden" name="lang" value="ar">
                    <input type="text" name="q" placeholder="بحث..." value="{{ request('q') }}">
                    <button type="submit" class="button-search"><i class="icofont icofont-search-2"></i></button>
                </form>
            </div>

            <div class="mobile-navigation">

                <nav>
                    <ul class="mobile-menu">
                        <li>
                            <a href="{{ URL::to('/ar')}}">الرئيسية</a>
                        </li>

                        <li class="menu-item-has-children">
                            <a href="#">عن الجامعة</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('home_dynamic_page_ar', ['slug' => 'about_sust']) }}">عن الجامعة </a></li>
                                <li><a href="{{ route('sust_leaders_ar')}}">قيادات الجامعة </a></li>
                                <li><a href="{{ route('home_dynamic_page_ar', ['slug' => 'vice_chancellor_message']) }}">كلمة مدير الجامعة</a></li>
                            </ul>
                        </li>

                        @isset($data['colleges'])
                            <li class="menu-item-has-children">
                                <a href="#">الكليات</a>
                                <ul class="dropdown">
                                    @foreach ($data['colleges'] as $college)
                                        <li><a href="{{ URL::to('/ar/college/' . $college->slug)}}" target="_blank">{{$college->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>


                            <li class="menu-item-has-children">
                                <a href="#">المراكز والمعاهد</a>
                                <ul class="dropdown">
                                    @foreach ($data['centers'] as $center)
                                        <li><a href="{{ URL::to('/ar/' . $center->getRawOriginal('college_type') . '/' . $center->slug)}}" target="_blank">{{$center->name}}</a></li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="menu-item-has-children">
                                <a href="#">العمادات</a>
                                <ul class="dropdown">
                                    @foreach ($data['deanships'] as $deanship)
                                        <li><a href="{{ URL::to('/ar/deanship/' . $deanship->slug)}}" target="_blank">{{$deanship->name}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endisset

                        <li>
                            <a href="{{ route('home_dynamic_page_ar', ['slug' => 'administration']) }}">الإدارة</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">الاخبار و الاحداث </a>
                            <ul class="dropdown">
                                <li><a href="{{ URL::to('/ar/news')}}">الاخبار</a></li>
                                <li><a href="{{ URL::to('/ar/ads')}}">الاعلانات</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">المؤتمرات والسمنارات</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('events_ar_archive', ['type' => 'conferences']) }}">المؤتمرات</a></li>
                                <li><a href="{{ route('events_ar_archive', ['type' => 'seminars']) }}">السمنارات</a></li>
                                <li><a href="{{ route('events_ar_archive', ['type' => 'workshops']) }}">ورش العمل</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ URL::to('/ar/secretariat/scientific-affairs') }}">الشؤون العلمية</a>
                        </li>
                        <li>
                            <a href="{{ url('/student/login') }}" target="_blank">الطلاب</a>
                        </li>
                        <li>
                            <a href="#">المجلات العلمية</a>
                        </li>

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