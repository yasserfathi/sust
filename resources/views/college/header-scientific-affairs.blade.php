<!-- topbar__section__stert -->
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
                            <li><a href="https://web.facebook.com/sustech.edu" target="_blank"><i
                                        class="icofont-facebook desktop-social-icon facebook"></i></a></li>
                            <li><a href="https://x.com/sudanuniv_SUST" target="_blank"><i
                                        class="icofont-twitter desktop-social-icon twitter"></i></a></li>
                            <li><a href="https://www.instagram.com/sustuniv/" target="_blank"><i
                                        class="icofont-instagram desktop-social-icon instagram"></i></a>
                            </li>
                            <li><a href="https://www.youtube.com/channel/UCfZt0JbAzY69o4PUbacTD8A" target="_blank"><i
                                        class="icofont-youtube desktop-social-icon youtube"></i></a></li>
                            <li><a href="https://www.linkedin.com/company/sudan-university-of-science-and-technology-sust-/posts/?feedView=all"
                                    target="_blank"><i class="icofont-linkedin desktop-social-icon linkedin"></i></a>
                            </li>
                            <li style="margin-right: 10px;">|</li>
                            <li>
                                @php
    $currentRoute = Route::currentRouteName();
    $switchUrl = route('home_ar');
    if ($currentRoute) {
        $arRoute = $currentRoute . '_ar';
        $excludedRoutes = ['news_detail', 'news_archive', 'ads_detail', 'ads_archive', 'college_news_archive', 'college_news_detail', 'college_ads_archive', 'college_ads_detail'];
        $routeMap = [
            'news_archive' => 'news_ar_archive',
            'news_detail' => 'news_ar_detail',
            'ads_archive' => 'ads_ar_archive',
            'ads_detail' => 'ads_ar_detail',
            'college_news_archive' => 'college_news_archive_ar',
            'college_news_detail' => 'college_news_detail_ar',
            'college_ads_archive' => 'college_ads_archive_ar',
            'college_ads_detail' => 'college_ads_detail_ar',
        ];
        if (array_key_exists($currentRoute, $routeMap)) {
            try { $switchUrl = route($routeMap[$currentRoute], Route::current()->parameters()); } catch (\Exception $e) { $switchUrl = '#error-' . $e->getMessage(); }
        } elseif (!in_array($currentRoute, $excludedRoutes) && Route::has($arRoute)) {
            try { $switchUrl = route($arRoute, Route::current()->parameters()); } catch (\Exception $e) {}
        } elseif (isset($data['college_type']) && isset($data['name'])) {
            try { $switchUrl = route($data['college_type'] . '_home_ar', ['name' => $data['name']]); } catch (\Exception $e) {}
        }
    }
@endphp
                                <a href="{{ $switchUrl }}">عربي</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- topbar__section__end -->

<!-- headar section start -->
<header>
    <div class="headerarea headerarea__3 header__sticky header__area">
        <div class="container desktop__menu__wrapper">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="headerarea__left">
                        <div class="headerarea__left__logo">
                            <a href="{{ route($data['college_type'] . '_home', ['name' => $data['name']]) }}">
                                @php
                                    $logoPath = !empty($data['logo_en']) && file_exists(public_path($data['logo_en'])) ? $data['logo_en'] : (!empty($data['logo']) && file_exists(public_path($data['logo'])) ? $data['logo'] : 'images/gallery/sust-logo-en.png');
                                @endphp
                                <img loading="lazy" src="{{ URL::to($logoPath) }}" alt="sust logo" class="img-fluid">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9">
                    <nav>
                        <ul class="sci-affairs-menu">
                            <!-- <li class="sci-affairs-item-ar">
                                <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'about']) }}" class="sci-affairs-link-ar">
                                    <i class="icofont-institution"></i>About </a>
                            </li> -->
                            <li class="sci-affairs-item">
                                <a href="#" class="sci-affairs-link">
                                    <i class="icofont-institution"></i> Vision & Identity <i
                                        class="icofont-rounded-down dropdown-icon"></i>
                                </a>
                                <div class="sci-affairs-dropdown">
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'vision-mission-goals-values']) }}"
                                        class="sci-dropdown-link">Vision, Mission & Objectives</a>
                                </div>
                            </li>

                            <li class="sci-affairs-item">
                                <a href="#" class="sci-affairs-link">
                                    <i class="icofont-network"></i> Structures <i
                                        class="icofont-rounded-down dropdown-icon"></i>
                                </a>
                                <div class="sci-affairs-dropdown">
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'organizational_structure']) }}"
                                        class="sci-dropdown-link">Organizational Structure</a>
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'functional_structure']) }}"
                                        class="sci-dropdown-link">Functional Structure</a>
                                </div>
                            </li>

                            <li class="sci-affairs-item">
                                <a href="#" class="sci-affairs-link">
                                    <i class="icofont-businessman"></i> Leadership <i
                                        class="icofont-rounded-down dropdown-icon"></i>
                                </a>
                                <div class="sci-affairs-dropdown">
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'tasks_of_secretary']) }}"
                                        class="sci-dropdown-link">Tasks of
                                        the Secretary</a>
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'tasks_of_deputy_secretary']) }}"
                                        class="sci-dropdown-link">Tasks of the Deputy Secretary</a>
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'administrative_supervisor']) }}"
                                        class="sci-dropdown-link">Administrative Supervisor</a>
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'accounting_unit']) }}"
                                        class="sci-dropdown-link">Accounting
                                        Unit</a>
                                </div>
                            </li>

                            <li class="sci-affairs-item">
                                <a href="#" class="sci-affairs-link">
                                    <i class="icofont-hat-alt"></i> Departments <i
                                        class="icofont-rounded-down dropdown-icon"></i>
                                </a>
                                <div class="sci-affairs-dropdown">
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'admission_registration_department']) }}"
                                        class="sci-dropdown-link">Admission & Registration</a>
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'special_technological_admission_center']) }}"
                                        class="sci-dropdown-link">Special & Tech Admission Center</a>
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'academic_affairs_information_department']) }}"
                                        class="sci-dropdown-link">Academic Affairs & Info</a>
                                    <a href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'faculty_members_affairs_department']) }}"
                                        class="sci-dropdown-link">Faculty Members Affairs</a>
                                </div>
                            </li>
                            <li class="sci-affairs-item lang-switcher-sticky">
                                <a href="{{ $switchUrl }}" class="sci-affairs-link" style="font-family: 'Cairo', sans-serif;">عربي</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Section (Standard) -->
        <div class="container-fluid mob_menu_wrapper">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="mobile-logo">
                        <a class="logo__dark"
                            href="{{ route($data['college_type'] . '_home', ['name' => $data['name']]) }}">
                            @php
                                $logoPath = !empty($data['logo_en']) && file_exists(public_path($data['logo_en'])) ? $data['logo_en'] : (!empty($data['logo']) && file_exists(public_path($data['logo'])) ? $data['logo'] : 'images/gallery/sust-logo-en.png');
                            @endphp
                            <img loading="lazy" src="{{ URL::to($logoPath) }}" alt="logo"></a>
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
<!-- header section end -->

<!-- Mobile Menu Start Here -->
<div class="mobile-off-canvas-active">
    <a class="mobile-aside-close"><i class="icofont icofont-close-line"></i></a>
    <div class="header-mobile-aside-wrap">
        <div class="mobile-menu-wrap headerarea">
            <div class="mobile-navigation">
                <nav>
                    <ul class="mobile-menu">
                        <li class="menu-item-has-children">
                            <a href="#">Vision & Identity</a>
                            <ul class="dropdown">
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'vision-mission-goals-values']) }}">Vision,
                                        Mission & Objectives</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Structures</a>
                            <ul class="dropdown">
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'organizational_structure']) }}">Organizational
                                        Structure</a></li>
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'functional_structure']) }}">Functional
                                        Structure</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Leadership</a>
                            <ul class="dropdown">
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'tasks_of_secretary']) }}">Tasks
                                        of the Secretary</a></li>
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'tasks_of_deputy_secretary']) }}">Tasks
                                        of the Deputy Secretary</a></li>
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'administrative_supervisor']) }}">Administrative
                                        Supervisor</a></li>
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'accounting_unit']) }}">Accounting
                                        Unit</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">Departments</a>
                            <ul class="dropdown">
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'admission_registration_department']) }}">Admission
                                        & Registration</a></li>
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'special_technological_admission_center']) }}">Special
                                        & Tech Admission Center</a></li>
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'academic_affairs_information_department']) }}">Academic
                                        Affairs & Info</a></li>
                                <li><a
                                        href="{{ route('secretariat_dynamic_page', ['name' => $data['name'], 'slug' => 'faculty_members_affairs_department']) }}">Faculty
                                        Members Affairs</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ $switchUrl }}">عربي</a></li>
                    </ul>
                </nav>
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
</div>
<!-- Mobile Menu end Here -->