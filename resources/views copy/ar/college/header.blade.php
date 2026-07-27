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
							<li>
								<a href="https://web.facebook.com/sustech.edu" target="_blank"><i
										class="icofont-facebook" style="background-color: #3b5998"></i></a>
							</li>
							<li>
								<a href="https://x.com/sudanuniv_SUST" target="_blank"><i class="icofont-twitter"
										style="background-color: #1da1f2"></i></a>
							</li>
							<li>
								<a href="https://www.instagram.com/sustuniv/" target="_blank"><i
										class="icofont-instagram"
										style="background:  radial-gradient(circle farthest-corner at 35% 90%, #fec564, transparent 50%), radial-gradient(circle farthest-corner at 0 140%, #fec564, transparent 50%), radial-gradient(ellipse farthest-corner at 0 -25%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 20% -50%, #5258cf, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 0, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 60% -20%, #893dc2, transparent 50%), radial-gradient(ellipse farthest-corner at 100% 100%, #d9317a, transparent), linear-gradient(#6559ca, #bc318f 30%, #e33f5f 50%, #f77638 70%, #fec66d 100%);"></i></a>
							</li>
							<li>
								<a href="https://www.youtube.com/channel/UCfZt0JbAzY69o4PUbacTD8A" target="_blank"><i
										class="icofont-youtube" style="background-color: #ff0000"></i></a>
							</li>
							<li>
								<a href="https://www.linkedin.com/company/sudan-university-of-science-and-technology-sust-/posts/?feedView=all"
									target="_blank"><i class="icofont-linkedin"
										style="background-color: #0077b5"></i></a>
							</li>
							<li>
								|
							</li>
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
								<a
									href="{{ $switchUrl }}">English</a>
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
			<div class="row">
				<div class="col-xl-3 col-lg-3 col-md-6">
					<div class="headerarea__left">
						<div class="headerarea__left__logo">

							<a href="{{ route($data['college_type'] . '_home_ar', ['name' => $data['name'] ?? request('name')]) }}">
								@php
									$logoPath = !empty($data['logo']) && file_exists(public_path($data['logo'])) ? $data['logo'] : 'images/gallery/sust-logo.png';
								@endphp
								<img loading="lazy" src="{{ URL::to($logoPath) }}"
									alt="sust logo" class="img-fluid"></a>
						</div>

					</div>
				</div>
				<div class="col-xl-9 col-lg-9 main_menu_wrap">
					<div class="headerarea__main__menu">
						<nav>
							<ul>
								<li><a class="headerarea__has__dropdown" href="#">عن الكلية
										<i class="icofont-rounded-down"></i>
									</a>
									<ul class="headerarea__submenu headerarea__submenu--third--wrap">
										<li><a href="{{ URL::to('/ar/college/' . Request::segment(3) . '/about')}}">عن
												الكلية</a></li>
										<li><a
												href="{{ URL::to('/ar/college/' . Request::segment(3) . '/vision-mission-objectives')}}">الرؤية
												- الرسالة - الأهداف</a></li>
										<li><a
												href="{{ URL::to('/ar/college/' . Request::segment(3) . '/dean_message')}}">رسالة
												العميد</a></li>
									</ul>
								</li>

								<li class="mega__menu position-static">
									<a class="headerarea__has__dropdown" href="#">الأقسام <i
											class="icofont-rounded-down"></i> </a>
									<div class="headerarea__submenu mega__menu__wrapper">

										<div class="row">
											@if(isset($data['departments']))
												@foreach ($data['departments']->chunk(7) as $departments)
													<div class="col-3 mega__menu__single__wrap">
														<ul class="mega__menu__item">
															@foreach ($departments as $department)
																<li><a
																		href="{{ route('college_about_department_ar', ['name' => $data['name'], 'dept_name' => str_replace(' ', '-', $department->name_en)]) }}">
																		{{$department->name}}</a></li>
															@endforeach
														</ul>
													</div>
												@endforeach
											@endif



										</div>
									</div>

								</li>

								<li><a class="headerarea__has__dropdown" href="#">الأنشطة</a>
								</li>
								<li><a class="headerarea__has__dropdown"
										href="{{ URL::to('/ar/college/' . Request::segment(3) . '/news')}}">الاخبار</a>
								</li>
								<li><a class="headerarea__has__dropdown"
										href="{{ URL::to('/ar/college/' . Request::segment(3) . '/ads')}}">الإعلانات
										والأحداث</a>
								</li>
								<li><a class="headerarea__has__dropdown"
										href="{{ URL::to('/ar/college/' . Request::segment(3) . '/staff')}}">هيئة
										التدريس</a>
								</li>

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
						<a class="logo__dark" href="{{ route($data['college_type'] . '_home_ar', ['name' => $data['name'] ?? request('name')]) }}">
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
<div class="mobile-off-canvas-active">
	<a class="mobile-aside-close"><i class="icofont  icofont-close-line"></i></a>
	<div class="header-mobile-aside-wrap">
		<div class="mobile-search">
			<form class="search-form" action="#">
				<input type="text" placeholder="Search entire store…">
				<button class="button-search"><i class="icofont icofont-search-2"></i></button>
			</form>
		</div>
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
								<li><a href="#">About SUST </a></li>
								<li><a href="#">SUST Leaders </a></li>
							</ul>
						</li>

						<li>
							<a href="#">Administration</a>
						</li>
						<li>
							<a href="#">Scientific Affairs</a>
						</li>
						<li>
							<a href="#">Students</a>
						</li>
						<li>
							<a href="#">journals</a>
						</li>

					</ul>
				</nav>

			</div>

		</div>
		<div class="mobile-social-wrap">
			<a class="facebook" href="#"><i class="icofont icofont-facebook"></i></a>
			<a class="twitter" href="#"><i class="icofont icofont-twitter"></i></a>
			<a class="pinterest" href="#"><i class="icofont icofont-pinterest"></i></a>
			<a class="instagram" href="#"><i class="icofont icofont-instagram"></i></a>
			<a class="google" href="#"><i class="icofont icofont-youtube-play"></i></a>
		</div>
	</div>
</div>
<!-- Mobile Menu end Here -->

<!-- theme fixed shadow -->
<div>
	<div class="theme__shadow__circle"></div>
	<div class="theme__shadow__circle shadow__right"></div>
</div>
<!-- theme fixed shadow -->