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
								<a href="https://web.facebook.com/sustech.edu" target="_blank">
									<i class="icofont-facebook"
										style="color: white; background: #3b5998; border-radius: 4px;"></i>
								</a>

							</li>
							<li>
								<a href="https://x.com/sudanuniv_SUST" target="_blank">
									<i class="icofont-twitter"
										style="color: white; background: #1da1f2; border-radius: 4px;"></i>
								</a>

							</li>
							<li>
								<a href="https://www.instagram.com/sustuniv/" target="_blank">
									<i class="icofont-instagram"
										style="color: white; background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); border-radius: 4px;"></i>
								</a>
							</li>
							<li>
								<a href="https://www.youtube.com/channel/UCfZt0JbAzY69o4PUbacTD8A" target="_blank">
									<i class="icofont-youtube"
										style="color: white; background: #ff0000; border-radius: 4px;"></i>
								</a>

							</li>
							<li>
								<a href="https://www.linkedin.com/company/sudan-university-of-science-and-technology-sust-/posts/?feedView=all"
									target="_blank">
									<i class="icofont-linkedin"
										style="color: white; background: #0077b5; padding: 2px; border-radius: 4px;"></i>
								</a>

							</li>
							<li style="margin-right: 10px;">
								|
							</li>
							<li>
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
								<a href="{{ $switchUrl }}"
									style="font-family: 'Noto Naskh Arabic', serif">عربي</a>
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

							<a href="{{ route($data['college_type'] . '_home', ['name' => $data['name']]) }}">
								@php
									$logoPath = !empty($data['logo']) && file_exists(public_path($data['logo'])) ? $data['logo'] : 'images/gallery/sust-logo-en.png';
								@endphp
								<img loading="lazy" src="{{ URL::to($logoPath) }}" alt="sust logo"
									class="img-fluid"></a>
						</div>

					</div>
				</div>
				<div class="col-xl-9 col-lg-9 main_menu_wrap">
					<div class="headerarea__main__menu">
						<nav>
							<ul>
								<li><a class="headerarea__has__dropdown" href="#">About College
										<i class="icofont-rounded-down"></i>
									</a>
									<ul class="headerarea__submenu headerarea__submenu--third--wrap">
										<li><a
												href="{{ url('/' . $data['college_type'] . '/' . $data['name'] . '/about')}}">About
												College</a></li>
										<li><a
												href="{{ url('/' . $data['college_type'] . '/' . $data['name'] . '/vision-mission-objectives')}}">Vision
												- Mission - Objectives</a></li>
										<li><a
												href="{{ url('/' . $data['college_type'] . '/' . $data['name'] . '/dean_message')}}">Dean
												Message</a></li>
									</ul>
								</li>

								<li class="mega__menu position-static">
									<a class="headerarea__has__dropdown" href="#">Departments <i
											class="icofont-rounded-down"></i> </a>
									<div class="headerarea__submenu mega__menu__wrapper">

										<div class="row">
											@foreach ($data['departments']->chunk(7) as $departments)
												<div class="col-3 mega__menu__single__wrap">
													<ul class="mega__menu__item">
														@foreach ($departments as $department)
															<li><a
																	href="{{ route($data['college_type'] . '_about_department', ['name' => $data['name'], 'dept_name' => str_replace(' ', '-', $department->name_en)]) }}">
																	{{$department->name_en}}</a></li>
														@endforeach
													</ul>
												</div>
											@endforeach



										</div>
									</div>

								</li>

								<li><a class="headerarea__has__dropdown" href="#">Activities</a>
								</li>
								<li><a class="headerarea__has__dropdown"
										href="{{ url('/' . $data['college_type'] . '/' . $data['name'] . '/news')}}">News</a>
								</li>
								<li><a class="headerarea__has__dropdown"
										href="{{ url('/' . $data['college_type'] . '/' . $data['name'] . '/ads')}}">Ads</a>
								</li>
								<li><a class="headerarea__has__dropdown"
										href="{{ url('/' . $data['college_type'] . '/' . $data['name'] . '/staff')}}">Staff</a>
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
						<a class="logo__dark"
							href="{{ route($data['college_type'] . '_home', ['name' => $data['name']]) }}">
							@php
								$logoPath = !empty($data['logo']) && file_exists(public_path($data['logo'])) ? $data['logo'] : 'images/gallery/sust-logo.png';
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