<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">

        <h4 class="sidebar__title">Main Menu</h4>
        <ul class="recent__list">
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'about_sust']) }}"
                            class="{{ (request()->routeIs('about_sust*') || request()->route('slug') === 'about_sust' || request()->is('*/about_sust')) ? 'active' : '' }}">
                            About SUST </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'vice_chancellor_message']) }}"
                            class="{{ (request()->routeIs('vice_chancellor_message*') || request()->route('slug') === 'vice_chancellor_message' || request()->is('*/vice_chancellor_message')) ? 'active' : '' }}">
                            Vice Chancellor Message </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('sust_leaders') }}"
                            class="{{ (request()->routeIs('sust_leaders*') || request()->route('slug') === 'sust_leaders' || request()->is('*/sust_leaders')) ? 'active' : '' }}">
                            SUST Leaders </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'khartoum_state']) }}"
                            class="{{ (request()->routeIs('khartoum_state*') || request()->route('slug') === 'khartoum_state' || request()->is('*/khartoum_state')) ? 'active' : '' }}">
                            About Khartoum State </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('sust_campuses') }}"
                            class="{{ (request()->routeIs('sust_campuses*') || request()->routeIs('sust_campus_detail*') || request()->routeIs('university_campuses*') || request()->routeIs('medical_campus*') || str_ends_with((string)request()->route('slug'), '_campus') || request()->route('slug') === 'sust_campuses' || request()->is('*/sust_campuses*') || request()->is('*/university_campuses*')) ? 'active' : '' }}">
                            SUST Campuses </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'sust_mission_vision_goals']) }}"
                            class="{{ (request()->routeIs('sust_mission_vision_goals*') || request()->route('slug') === 'sust_mission_vision_goals' || request()->is('*/sust_mission_vision_goals')) ? 'active' : '' }}">
                            Vision, Mission and Goals </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('former_vice_chancellors') }}"
                            class="{{ (request()->routeIs('former_vice_chancellors*') || request()->route('slug') === 'former_vice_chancellors' || request()->is('*/former_vice_chancellors')) ? 'active' : '' }}">
                            Former Vice Chancellors </a></h6>
                </div>
            </li>
        </ul>

    </div>
</div>
