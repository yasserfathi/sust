<!--sidebar wrapper -->
@php 
	$data = DB::table('categories')
            ->join('category_pages', 'categories.id', '=', 'category_pages.category_id')
            ->join('category_page_user', 'category_pages.id', '=', 'category_page_user.category_page_id')
            ->select('categories.title as category_title', 'category_pages.title as page', 'url')
			->where('category_page_user.user_id', Auth::user()->id)
            ->get();
	
	$counts = array_count_values($data->pluck('category_title')->toArray());
	$arr2 = array();
	foreach($data as $item)
	{
		$arr[$item->url] = $item->page;
		if($counts[$item->category_title] > 1)
		{
			foreach($data as $record)
			{
				if($item->category_title == $record->category_title && in_array($record->page,$arr) == false){
					$arr[$record->url] = $record->page;
				}
			}
		}

		$arr2[] = array($item->category_title,$arr);
		$arr = array();
	}
	$data = array();
	foreach($arr2 as $key) 
	{
		if(array_key_exists($key[0], $data)) continue;
		$data[$key[0]] = $key[1];
	}
@endphp
{{-- <div style="text-align: left;direction:ltr">{{ print_r($data2) }}</div>; }}</div> --}}
<div class="sidebar-wrapper" data-simplebar="true">
	<div class="sidebar-header">
		<div>
			<img src="{{ URL::asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
		</div>
		<div>
			<h6 class="logo-text">جامعة السودان للعلوم والتكنولوجيا</h6>
		</div>
		<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i></div>
	</div>
	<!--navigation-->
	<ul class="metismenu" id="menu">
		@if(Auth::user()->role == 1)
		<li>
			<a href="javascript:;" class="has-arrow">
				<div class="parent-icon"><i class='bx bx-home-circle'></i>
				</div>
				<div class="menu-title">صفحات لوحة التحكم</div>
			</a>
			<ul>
				<li> <a href="{{ URL :: to('admin/category') }}"><i class="bx bx-right-arrow-alt"></i>اضافة فئة</a></li>
				<li> <a href="{{ URL :: to('admin/category_page') }}"><i class="bx bx-right-arrow-alt"></i>اضافة صفحة</a></li>
				<li> <a href="{{ URL :: to('admin/category_page_user') }}"><i class="bx bx-right-arrow-alt"></i>تنسيب الصفحات للمستخدمين</a></li>
			</ul>
		</li>
		@endif
		@if(isset($data))
			@foreach( $data as $key =>$value)
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-home-circle'></i>
						</div>
						<div class="menu-title">{{ $key }}</div>
					</a>
					<ul>
						@foreach( $value as $url => $page )
						<li> <a href="{{ URL :: to('admin/'.$url)}}"><i class="bx bx-right-arrow-alt"></i>{{ $page }}</a></li>
						@endforeach
					</ul>
				</li>
			@endforeach
		@endif
	</ul>
	<!--end navigation-->
</div>
<!--end sidebar wrapper -->
<!--start header -->
<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand">
			<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
			</div>
			<div class="top-menu ms-auto">
				<ul class="navbar-nav align-items-center">
				</ul>
			</div>
			<div class="user-box dropdown">
				<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
						<img src="{{ URL::asset(Auth::user()->img) }}" class="rounded-circle border" style="width:40px;height:40px" />
					<div class="user-info ps-3">
						<p class="user-name mb-0">{{Auth::user()->name}}</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					<li><a class="dropdown-item" href="javascript:;"><i class="bx bx-user"></i><span>Profile</span></a>
					</li>
					<li>
						<div class="dropdown-divider mb-0"></div>
					</li>
					<li><a class="dropdown-item" href="#" data-href="{{ URL :: to('admin/logout')}}" data-bs-toggle="modal" data-bs-target="#LogoutModal"><i class='bx bx-log-out-circle'></i><span>تسجيل الخروج</span></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>
<!--end header -->