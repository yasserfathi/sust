<!-- topbar__section__stert -->
<div class="topbararea" dir="rtl">
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
                                        class="icofont-facebook"
                                        style="color: white; background: #3b5998; border-radius: 4px;"></i></a></li>
                            <li><a href="https://x.com/sudanuniv_SUST" target="_blank"><i class="icofont-twitter"
                                        style="color: white; background: #1da1f2; border-radius: 4px;"></i></a></li>
                            <li><a href="https://www.instagram.com/sustuniv/" target="_blank"><i
                                        class="icofont-instagram"
                                        style="color: white; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); border-radius: 4px;"></i></a>
                            </li>
                            <li><a href="https://www.youtube.com/channel/UCfZt0JbAzY69o4PUbacTD8A" target="_blank"><i
                                        class="icofont-youtube"
                                        style="color: white; background: #ff0000; border-radius: 4px;"></i></a></li>
                            <li><a href="https://www.linkedin.com/company/sudan-university-of-science-and-technology-sust-/posts/?feedView=all"
                                    target="_blank"><i class="icofont-linkedin"
                                        style="color: white; background: #0077b5; padding: 2px; border-radius: 4px;"></i></a>
                            </li>
                            <li style="margin-left: 10px;">|</li>
                            <li>
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
                                <a href="{{ $switchUrl }}"
                                    style="font-family: 'Figtree', sans-serif">English</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- topbar__section__end -->

<style>
    /* Custom Premium Menu Styling - ARABIC */
    .sci-affairs-menu-ar {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin: 0;
        padding: 0;
        list-style: none;
        direction: rtl;
        font-family: 'Noto Naskh Arabic', serif;
    }

    .sci-affairs-item-ar {
        position: relative;
        margin: 0 5px;
        padding: 25px 0;
    }

    .sci-affairs-link-ar {
        color: var(--text-dark);
        font-size: 15px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }

    .sci-affairs-link-ar:hover {
        color: var(--college-primary);
    }

    .sci-affairs-link-ar i.dropdown-icon {
        font-size: 12px;
        transition: transform 0.3s;
    }

    .sci-affairs-item-ar:hover .sci-affairs-link-ar i.dropdown-icon {
        transform: rotate(-180deg);
    }

    .sci-affairs-dropdown-ar {
        position: absolute;
        top: 100%;
        right: 50%;
        transform: translateX(50%) translateY(20px);
        width: 290px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 5px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        z-index: 999;
        text-align: right;
    }

    .sci-affairs-item-ar:hover .sci-affairs-dropdown-ar {
        opacity: 1;
        visibility: visible;
        transform: translateX(50%) translateY(0);
    }

    .sci-dropdown-link-ar {
        display: block;
        padding: 12px 15px;
        color: var(--text-dark);
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s;
        border-right: 3px solid transparent;
    }

    .sci-dropdown-link-ar:hover {
        background: rgba(206, 97, 72, 0.05);
        color: var(--college-primary);
        border-right-color: var(--college-primary);
        padding-right: 20px;
    }
</style>

<!-- headar section start -->
<header dir="rtl">
    <div class="headerarea headerarea__3 header__sticky header__area">
        <div class="container desktop__menu__wrapper">
            <div class="row align-items-center">
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="headerarea__left text-end">
                        <div class="headerarea__left__logo">
                            <a href="{{ route('secretariat_home_ar', ['name' => $data['name']]) }}">
								@php
									$logoPath = !empty($data['logo']) && file_exists(public_path($data['logo'])) ? $data['logo'] : 'images/gallery/sust-logo.png';
								@endphp
                                <img loading="lazy" src="{{ URL::to($logoPath) }}" alt="sust logo"
                                    class="img-fluid">
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-lg-9 main_menu_wrap">
                    <nav>
                        <ul class="sci-affairs-menu-ar">

                            <li class="sci-affairs-item-ar">
                                <a href="#" class="sci-affairs-link-ar">
                                    <i class="icofont-institution"></i> التعريف المؤسسي والرؤية <i
                                        class="icofont-rounded-down dropdown-icon"></i>
                                </a>
                                <div class="sci-affairs-dropdown-ar">
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'vision-mission-goals-values']) }}"
                                        class="sci-dropdown-link-ar">الرؤية والرسالة والأهداف و القيم</a>
                                </div>
                            </li>

                            <li class="sci-affairs-item-ar">
                                <a href="#" class="sci-affairs-link-ar">
                                    <i class="icofont-network"></i> الهياكل التنظيمية والوظيفية <i
                                        class="icofont-rounded-down dropdown-icon"></i>
                                </a>
                                <div class="sci-affairs-dropdown-ar">
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'organizational_structure']) }}"
                                        class="sci-dropdown-link-ar">الهيكل التنظيمي لامانة الشؤون العلمية</a>
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'functional_structure']) }}" class="sci-dropdown-link-ar">الهيكل
                                        الوظيفي لامانة الشؤون العلمية</a>
                                </div>
                            </li>

                            <li class="sci-affairs-item-ar">
                                <a href="#" class="sci-affairs-link-ar">
                                    <i class="icofont-businessman"></i> القيادة والإشراف الإداري <i
                                        class="icofont-rounded-down dropdown-icon"></i>
                                </a>
                                <div class="sci-affairs-dropdown-ar">
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'tasks_of_secretary']) }}" class="sci-dropdown-link-ar">مهام
                                        أمين أمانة الشؤون العلمية</a>
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'tasks_of_deputy_secretary']) }}"
                                        class="sci-dropdown-link-ar">مهام نائب امين أمانة الشؤون العلمية</a>
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'administrative_supervisor']) }}"
                                        class="sci-dropdown-link-ar">المشرف الإداري بالأمانة</a>
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'accounting_unit']) }}" class="sci-dropdown-link-ar">الوحدة
                                        الحسابية بالأمانة</a>
                                </div>
                            </li>

                            <li class="sci-affairs-item-ar">
                                <a href="#" class="sci-affairs-link-ar">
                                    <i class="icofont-hat-alt"></i> الإدارات المتخصصة والخدمات <i
                                        class="icofont-rounded-down dropdown-icon"></i>
                                </a>
                                <div class="sci-affairs-dropdown-ar">
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'admission_registration_department']) }}"
                                        class="sci-dropdown-link-ar">إدارة القبول والتسجيل</a>
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'special_technological_admission_center']) }}"
                                        class="sci-dropdown-link-ar">مركز القبول الخاص والتكنولوجي</a>
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'academic_affairs_information_department']) }}"
                                        class="sci-dropdown-link-ar">إدارة الشؤون الأكاديمية والمعلومات</a>
                                    <a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'faculty_members_affairs_department']) }}"
                                        class="sci-dropdown-link-ar">ادارة شؤون اعضاء هيئة التدريس ومساعديهم</a>
                                </div>
                            </li>

                        </ul>
                    </nav>
                </div>

            </div>
        </div>

        <!-- Mobile Menu Section -->
        <div class="container-fluid mob_menu_wrapper">
            <div class="row align-items-center">
                <div class="col-6">
                    <div class="mobile-logo">
                        <a class="logo__dark" href="{{ route('secretariat_home_ar', ['name' => $data['name']]) }}">
							@php
								$logoPath = !empty($data['logo']) && file_exists(public_path($data['logo'])) ? $data['logo'] : 'images/gallery/sust-logo.png';
							@endphp
							<img loading="lazy"
                                src="{{ URL::to($logoPath) }}" alt="logo"></a>
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
<div class="mobile-off-canvas-active" dir="rtl">
    <a class="mobile-aside-close"><i class="icofont icofont-close-line"></i></a>
    <div class="header-mobile-aside-wrap">
        <div class="mobile-menu-wrap headerarea">
            <div class="mobile-navigation" style="text-align: right;">
                <nav>
                    <ul class="mobile-menu">
                        <li class="menu-item-has-children">
                            <a href="#">التعريف المؤسسي والرؤية</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'vision-mission-goals-values']) }}">الرؤية والرسالة والأهداف و القيم</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">الهياكل التنظيمية والوظيفية</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'organizational_structure']) }}">الهيكل التنظيمي لامانة الشؤون العلمية</a></li>
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'functional_structure']) }}">الهيكل الوظيفي لامانة الشؤون العلمية</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">القيادة والإشراف الإداري</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'tasks_of_secretary']) }}">مهام أمين أمانة الشؤون العلمية</a></li>
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'tasks_of_deputy_secretary']) }}">مهام نائب امين أمانة الشؤون العلمية</a></li>
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'administrative_supervisor']) }}">المشرف الإداري بالأمانة</a></li>
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'accounting_unit']) }}">الوحدة الحسابية بالأمانة</a></li>
                            </ul>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">الإدارات المتخصصة والخدمات الأكاديمية</a>
                            <ul class="dropdown">
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'admission_registration_department']) }}">إدارة القبول والتسجيل</a></li>
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'special_technological_admission_center']) }}">مركز القبول الخاص والتكنولوجي</a></li>
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'academic_affairs_information_department']) }}">إدارة الشؤون الأكاديمية والمعلومات</a></li>
                                <li><a href="{{ route('secretariat_dynamic_page_ar', ['name' => $data['name'], 'slug' => 'faculty_members_affairs_department']) }}">ادارة شؤون اعضاء هيئة التدريس ومساعديهم</a></li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Mobile Menu end Here -->