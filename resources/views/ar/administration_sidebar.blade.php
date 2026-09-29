<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">

        <h4 class="sidebar__title">القائمة الرئيسية</h4>
        <ul class="recent__list">
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'about_sust']) }}"
                            class="{{ (request()->routeIs('about_sust*') || request()->route('slug') === 'about_sust' || request()->is('*/about_sust')) ? 'active' : '' }}">
                            عن الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'vice_chancellor_message']) }}"
                            class="{{ (request()->routeIs('vice_chancellor_message*') || request()->route('slug') === 'vice_chancellor_message' || request()->is('*/vice_chancellor_message')) ? 'active' : '' }}">
                            كلمة مدير الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('sust_leaders_ar') }}"
                            class="{{ (request()->routeIs('sust_leaders*') || request()->route('slug') === 'sust_leaders' || request()->is('*/sust_leaders')) ? 'active' : '' }}">
                            قيادات الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'khartoum_state']) }}"
                            class="{{ (request()->routeIs('khartoum_state*') || request()->route('slug') === 'khartoum_state' || request()->is('*/khartoum_state')) ? 'active' : '' }}">
                            عن ولاية الخرطوم </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('sust_campuses_ar') }}"
                            class="{{ (request()->routeIs('sust_campuses*') || request()->routeIs('sust_campus_detail_ar*') || request()->routeIs('university_campuses*') || request()->routeIs('medical_campus_ar*') || str_ends_with((string)request()->route('slug'), '_campus') || request()->route('slug') === 'sust_campuses' || request()->is('*/sust_campuses*') || request()->is('*/university_campuses*')) ? 'active' : '' }}">
                            مجمعات الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'sust_mission_vision_goals']) }}"
                            class="{{ (request()->routeIs('sust_mission_vision_goals*') || request()->route('slug') === 'sust_mission_vision_goals' || request()->is('*/sust_mission_vision_goals')) ? 'active' : '' }}">
                            الرؤية والرسالة والأهداف </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('former_vice_chancellors_ar') }}"
                            class="{{ (request()->routeIs('former_vice_chancellors*') || request()->route('slug') === 'former_vice_chancellors' || request()->is('*/former_vice_chancellors')) ? 'active' : '' }}">
                            المدراء السابقين للجامعة </a></h6>
                </div>
            </li>
        </ul>

    </div>
</div>
