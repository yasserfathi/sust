<div class="topbararea">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-6">
                <div class="topbar__left">
                    <ul>
                        <li>
                            <i class="icofont-email"></i> admin@sustech.edu
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="topbar__right">
                    <div class="topbar__list">
                        <ul>
                            <li>
                                <a href="https://web.facebook.com/sustech.edu" target="_blank"><i
                                        class="icofont-facebook desktop-social-icon facebook"></i></a>
                            </li>
                            <li>
                                <a href="https://x.com/sudanuniv_SUST" target="_blank"><i class="icofont-twitter desktop-social-icon twitter"></i></a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/sustuniv/" target="_blank"><i
                                        class="icofont-instagram desktop-social-icon instagram"></i></a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/channel/UCfZt0JbAzY69o4PUbacTD8A" target="_blank"><i
                                        class="icofont-youtube desktop-social-icon youtube"></i></a>
                            </li>
                            <li>
                                <a href="https://www.linkedin.com/company/sudan-university-of-science-and-technology-sust-/posts/?feedView=all"
                                    target="_blank"><i class="icofont-linkedin desktop-social-icon linkedin"></i></a>
                            </li>
                            <li>
                            <li>
                                |
                            </li>
@php
    $currentRoute = Route::currentRouteName();
    $switchUrl = route('home');
    if ($currentRoute) {
        $enRoute = str_ends_with($currentRoute, '_ar') ? substr($currentRoute, 0, -3) : $currentRoute;
        $excludedRoutes = ['news_ar_detail', 'news_ar_archive', 'ads_ar_detail', 'ads_ar_archive', 'college_news_archive_ar', 'college_news_detail_ar', 'college_ads_archive_ar', 'college_ads_detail_ar'];
        if (!in_array($currentRoute, $excludedRoutes) && Route::has($enRoute)) {
            try { $switchUrl = route($enRoute, Route::current()->parameters()); } catch (\Exception $e) {}
        } elseif (isset($data['college_type']) && isset($data['name'])) {
            try { $switchUrl = route($data['college_type'] . '_home', ['name' => $data['name']]); } catch (\Exception $e) {}
        }
    }
@endphp
                            <li>
                                <a href="{{ $switchUrl }}">English</a>
                            </li>
                        </ul>
                    </div>
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

                            <a href="{{ URL::to('/ar')}}"><img loading="lazy"
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
                                            <li><a href="{{ URL::to('/ar/leadership')}}">قيادات الجامعة</a></li>
                                            <li><a href="{{ route('home_dynamic_page_ar', ['slug' => 'vice_chancellor_message']) }}">كلمة مدير
                                                    الجامعة</a></li>
                                        </ul>
                                    </li>

                                    <li class="headerarea__mega__menu">
                                        <a class="headerarea__has__dropdown" href="#">الكليات <i
                                                class="icofont-rounded-down"></i></a>
                                        <div class="headerarea__submenu mega__menu__wrapper">
                                            <div class="row">
                                                @foreach ($data['colleges']->chunk(7) as $chunk)
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

                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">المراكز والمعاهد <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            @foreach ($data['centers'] as $center)
                                                <li><a
                                                        href="{{ URL::to('/ar/center/' . $center->slug)}}" target="_blank">{{$center->name}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
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

                                    <li><a href="{{ route('home_dynamic_page_ar', ['slug' => 'administration']) }}">ادارة الجامعة</a></li>
                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">الاخباروالاحداث <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            <li><a href="{{ URL::to('/ar/news')}}">الاخبار</a></li>
                                            <li><a href="{{ URL::to('/ar/ads')}}">الإعلانات والأحداث</a></li>
                                        </ul>
                                    </li>

                                    <li><a href="{{ URL::to('/ar/secretariat/scientific-affairs') }}">الشؤون العلمية</a></li>

                                    <li><a href="#">الطلاب</a></li>

                                    <li><a href="#">المجلات العلمية</a></li>

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
                        <a class="logo__dark" href="#"><img loading="lazy"
                                src="{{ URL::to('images/gallery/sust-logo.png') }}" alt="logo"></a>
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
    <a class="mobile-aside-close"><i class="icofont  icofont-close-line"></i></a>
    <div class="header-mobile-aside-wrap">
        <div class="mobile-menu-wrap headerarea">

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
                                <li><a href="{{ route('home_dynamic_page_ar', ['slug' => 'vice_chancellor_message']) }}">قيادات الجامعة </a></li>
                                <li><a href="{{ URL::to('/ar/leadership')}}">كلمة مدير الجامعة</a></li>
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
                                        <li><a href="{{ URL::to('/ar/center/' . $center->slug)}}" target="_blank">{{$center->name}}</a></li>
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
                            <a href="{{ route('home_dynamic_page_ar', ['slug' => 'administration']) }}">ادارة الجامعة</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">الاخباروالاحداث</a>
                            <ul class="dropdown">
                                <li><a href="{{ URL::to('/ar/news')}}">الاخبار</a></li>
                                <li><a href="{{ URL::to('/ar/ads')}}">الإعلانات والأحداث</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ URL::to('/ar/secretariat/scientific-affairs') }}">الشؤون العلمية</a>
                        </li>
                        <li>
                            <a href="#">الطلاب</a>
                        </li>
                        <li>
                            <a href="#">المجلات العلمية</a>
                        </li>
                        <li>
                            <a href="{{ $switchUrl }}">English</a>
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