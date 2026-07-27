<div class="topbararea">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="topbar__left">
                    <ul>
                        <li>
                            <i class="icofont-email"></i> admin@sustech.edu
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="topbar__right">
                    <div class="topbar__list">
                        <ul>
                            <li>
                                <a href="https://web.facebook.com/sustech.edu" target="_blank">
                                    <i class="icofont-facebook" style="background: #3b5998; color: white;"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://x.com/sudanuniv_SUST" target="_blank">
                                    <i class="icofont-twitter" style="background: #1da1f2; color: white;"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://www.instagram.com/sustuniv/" target="_blank">
                                    <i class="icofont-instagram"
                                        style="background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); color: white;"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://www.youtube.com/channel/UCfZt0JbAzY69o4PUbacTD8A" target="_blank">
                                    <i class="icofont-youtube" style="background: #ff0000; color: white;"></i>
                                </a>
                            </li>

                            <li>
                                <a href="https://www.linkedin.com/company/sudan-university-of-science-and-technology-sust-/posts/?feedView=all"
                                    target="_blank">
                                    <i class="icofont-linkedin" style="background: #0077b5; color: white;"></i>
                                </a>
                            </li>

                            <li style="opacity: 0.5; margin: 0 10px;">|</li>
@php
    $currentRoute = Route::currentRouteName();
    $switchUrl = route('home_ar');
    if ($currentRoute) {
        $arRoute = $currentRoute . '_ar';
        $excludedRoutes = ['news_detail', 'news_archive', 'ads_detail', 'ads_archive', 'college_news_archive', 'college_news_detail', 'college_ads_archive', 'college_ads_detail'];
        if (!in_array($currentRoute, $excludedRoutes) && Route::has($arRoute)) {
            try { $switchUrl = route($arRoute, Route::current()->parameters()); } catch (\Exception $e) {}
        } elseif (isset($data['college_type']) && isset($data['name'])) {
            try { $switchUrl = route($data['college_type'] . '_home_ar', ['name' => $data['name']]); } catch (\Exception $e) {}
        }
    }
@endphp
                            <li>
                                <a href="{{ $switchUrl }}"
                                    style="font-family: 'Noto Naskh Arabic', serif;">عربي</a>
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

                            <a href="{{ route('home') }}"><img loading="lazy"
                                    src="{{ URL::to('images/gallery/sust-logo-en.png') }}" alt="sust logo"
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
                                        <a class="headerarea__has__dropdown" href="#">About SUST <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            <li><a href="{{ route('home_dynamic_page', ['slug' => 'about_sust']) }}">About SUST</a></li>
                                            <li><a href="{{ URL::to('/leadership')}}">SUST Leaders</a></li>
                                            <li><a href="{{ route('home_dynamic_page', ['slug' => 'vice_chancellor_message']) }}">VICE CHANCELLOR</a></li>
                                        </ul>
                                    </li>

                                    <li class="headerarea__mega__menu">
                                        <a class="headerarea__has__dropdown" href="#">Colleges <i
                                                class="icofont-rounded-down"></i></a>
                                        <div class="headerarea__submenu mega__menu__wrapper">
                                            <div class="row">
                                                @foreach ($data['colleges']->chunk(7) as $chunk)
                                                    <div class="col-3 mega__menu__single__wrap">
                                                        <ul class="mega__menu__item">
                                                            @foreach ($chunk as $college)
                                                                <li><a
                                                                        href="{{ URL::to('/college/' . $college->slug) }}" target="_blank">{{ $college->name_en }}</a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">Centers & institutes <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            @foreach ($data['centers'] as $center)
                                                <li><a
                                                        href="{{ URL::to('/center/' . $center->slug)}}" target="_blank">{{$center->name_en}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>

                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">Deanships <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            @foreach ($data['deanships'] as $deanship)
                                                <li><a
                                                        href="{{ URL::to('/deanship/' . $deanship->slug)}}" target="_blank">{{$deanship->name_en}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>

                                    <li><a href="{{ route('home_dynamic_page', ['slug' => 'administration']) }}">Administration</a></li>

                                    <li>
                                        <a class="headerarea__has__dropdown" href="#">News and Ads <i
                                                class="icofont-rounded-down"></i></a>
                                        <ul class="headerarea__submenu">
                                            <li><a href="{{ URL::to('/news')}}">News</a></li>
                                            <li><a href="{{ URL::to('/ads')}}">Announcements and Events</a></li>
                                        </ul>
                                    </li>

                                    <li><a href="{{ URL::to('/secretariat/scientific-affairs') }}">Scientific Affairs</a></li>
                                    <li><a href="#">Students</a></li>
                                    <li><a href="#">Journals</a></li>
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
                            <a href="{{ URL::to('/')}}">Home</a>
                        </li>

                        <li class="menu-item-has-children">
                            <a href="#">About SUST</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('home_dynamic_page', ['slug' => 'about_sust']) }}">About SUST </a></li>
                                <li><a href="{{ URL::to('/leadership')}}">SUST Leaders </a></li>
                                <li><a href="{{ route('home_dynamic_page', ['slug' => 'vice_chancellor_message']) }}">VICE CHANCELLOR</a></li>
                            </ul>
                        </li>

                        @isset($data['colleges'])
                            <li class="menu-item-has-children">
                                <a href="#">Colleges</a>
                                <ul class="dropdown">
                                    @foreach ($data['colleges'] as $college)
                                        <li><a href="{{ URL::to('/college/' . $college->slug)}}" target="_blank">{{$college->name_en}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>


                            <li class="menu-item-has-children">
                                <a href="#">Centers & institutes</a>
                                <ul class="dropdown">
                                    @foreach ($data['centers'] as $center)
                                        <li><a href="{{ URL::to('/center/' . $center->slug)}}" target="_blank">{{$center->name_en}}</a></li>
                                    @endforeach
                                </ul>
                            </li>

                            <li class="menu-item-has-children">
                                <a href="#">Deanships</a>
                                <ul class="dropdown">
                                    @foreach ($data['deanships'] as $deanship)
                                        <li><a href="{{ URL::to('/deanship/' . $deanship->slug)}}" target="_blank">{{$deanship->name_en}}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endisset

                        <li>
                            <a href="{{ route('home_dynamic_page', ['slug' => 'administration']) }}">Administration</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">News and Ads</a>
                            <ul class="dropdown">
                                <li><a href="{{ URL::to('/news')}}">News</a></li>
                                <li><a href="{{ URL::to('/ads')}}">Announcements and Events</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ URL::to('/secretariat/scientific-affairs') }}">Scientific Affairs</a>
                        </li>
                        <li>
                            <a href="#">Students</a>
                        </li>
                        <li>
                            <a href="#">journals</a>
                        </li>
                        <li>
                            <a href="{{ $switchUrl }}" style="font-family: 'Noto Naskh Arabic', serif">عربي</a>
                        </li>

                    </ul>
                </nav>

            </div>

        </div>
        <div style="display: flex; gap: 6px;">
            <a href="https://web.facebook.com/sustech.edu" target="_blank">
                <i class="icofont-facebook"
                    style="color: white; background: #3b5998; padding: 8px; border-radius: 4px;"></i>
            </a>
            <a href="https://x.com/sudanuniv_SUST" target="_blank">
                <i class="icofont-twitter"
                    style="color: white; background: #1da1f2; padding: 8px; border-radius: 4px;"></i>
            </a>
            <a href="https://www.instagram.com/sustuniv/" target="_blank">
                <i class="icofont-instagram"
                    style="color: white; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); padding: 8px; border-radius: 4px;"></i>
            </a>
            <a href="https://www.youtube.com/channel/UCfZt0JbAzY69o4PUbacTD8A" target="_blank">
                <i class="icofont-youtube"
                    style="color: white; background: #ff0000; padding: 8px; border-radius: 4px;"></i>
            </a>
            <a href="https://www.linkedin.com/company/sudan-university-of-science-and-technology-sust-/posts/?feedView=all"
                target="_blank">
                <i class="icofont-linkedin"
                    style="color: white; background: #0077b5; padding: 8px; border-radius: 4px;"></i>
            </a>
        </div>
    </div>
</div>
<div>
    <div class="theme__shadow__circle"></div>
    <div class="theme__shadow__circle shadow__right"></div>
</div>