<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">

        <h4 class="sidebar__title">Main Menu</h4>
        <ul class="recent__list">
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'about_sust']) }}" style="{{ request()->route('slug') == 'about_sust' ? 'color: var(--primaryColor) !important;' : '' }}"> Historical Background </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'vice_chancellor_message']) }}" style="{{ request()->route('slug') == 'vice_chancellor_message' ? 'color: var(--primaryColor) !important;' : '' }}"> Vice Chancellor Message </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('sust_leaders') }}" style="{{ request()->routeIs('sust_leaders') ? 'color: var(--primaryColor) !important;' : '' }}"> Sust Leaders </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'administration']) }}" style="{{ request()->route('slug') == 'administration' ? 'color: var(--primaryColor) !important;' : '' }}"> SUST Administration </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'khartoum_state']) }}" style="{{ request()->route('slug') == 'khartoum_state' ? 'color: var(--primaryColor) !important;' : '' }}"> About Khartoum State </a></h6>
                </div>
            </li>
            <li style="flex-direction: column; align-items: stretch; padding-bottom: 10px;">
                <div class="recent__text d-flex justify-content-between align-items-center w-100" data-bs-toggle="collapse" data-bs-target="#campuses-collapse" aria-expanded="{{ in_array(request()->route('slug'), ['main_campus', 'southern_campus', 'western_campus', 'medicale_campus', 'music_drama_campus']) ? 'true' : 'false' }}" style="cursor: pointer;">
                    <h6 class="m-0"><a href="javascript:void(0)" class="text-dark" style="{{ in_array(request()->route('slug'), ['main_campus', 'southern_campus', 'western_campus', 'medicale_campus', 'music_drama_campus']) ? 'color: var(--primaryColor) !important;' : '' }}"> University Campuses </a></h6>
                    <i class="icofont-rounded-down"></i>
                </div>
                <div class="collapse mt-2 w-100 ps-2 {{ in_array(request()->route('slug'), ['main_campus', 'southern_campus', 'western_campus', 'medicale_campus', 'music_drama_campus']) ? 'show' : '' }}" id="campuses-collapse">
                    <a href="{{ route('home_dynamic_page', ['slug' => 'main_campus']) }}" class="d-block mb-2 text-dark" style="font-size: 14px; {{ request()->route('slug') == 'main_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-right me-2" style="font-size: 12px; color: var(--primaryColor);"></i>Main Campus</a>
                    <a href="{{ route('home_dynamic_page', ['slug' => 'southern_campus']) }}" class="d-block mb-2 text-dark" style="font-size: 14px; {{ request()->route('slug') == 'southern_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-right me-2" style="font-size: 12px; color: var(--primaryColor);"></i>Southern Campus</a>
                    <a href="{{ route('home_dynamic_page', ['slug' => 'western_campus']) }}" class="d-block mb-2 text-dark" style="font-size: 14px; {{ request()->route('slug') == 'western_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-right me-2" style="font-size: 12px; color: var(--primaryColor);"></i>Western Campus</a>
                    <a href="{{ route('home_dynamic_page', ['slug' => 'medicale_campus']) }}" class="d-block mb-2 text-dark" style="font-size: 14px; {{ request()->route('slug') == 'medicale_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-right me-2" style="font-size: 12px; color: var(--primaryColor);"></i>Medical Campus</a>
                    <a href="{{ route('home_dynamic_page', ['slug' => 'music_drama_campus']) }}" class="d-block text-dark" style="font-size: 14px; {{ request()->route('slug') == 'music_drama_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-right me-2" style="font-size: 12px; color: var(--primaryColor);"></i>Music and Drama Campus</a>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'sust_mission_vision_goals']) }}" style="{{ request()->route('slug') == 'sust_mission_vision_goals' ? 'color: var(--primaryColor) !important;' : '' }}"> Vision, Mission and Goals </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('former_vice_chancellors') }}" style="{{ request()->routeIs('former_vice_chancellors') ? 'color: var(--primaryColor) !important;' : '' }}"> Former Vice Chancellors </a></h6>
                </div>
            </li>

        </ul>

    </div>
</div>