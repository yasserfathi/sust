<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">

        <h4 class="sidebar__title">القائمة الرئيسية</h4>
        <ul class="recent__list">
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'about_sust']) }}" style="{{ request()->route('slug') == 'about_sust' ? 'color: var(--primaryColor) !important;' : '' }}"> نبذة تاريخية </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'vice_chancellor_message']) }}" style="{{ request()->route('slug') == 'vice_chancellor_message' ? 'color: var(--primaryColor) !important;' : '' }}"> كلمة مدير الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('sust_leaders_ar') }}" style="{{ request()->routeIs('sust_leaders_ar') ? 'color: var(--primaryColor) !important;' : '' }}">قيادات الجامعة</a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'administration']) }}" style="{{ request()->route('slug') == 'administration' ? 'color: var(--primaryColor) !important;' : '' }}"> إدارة الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'khartoum_state']) }}" style="{{ request()->route('slug') == 'khartoum_state' ? 'color: var(--primaryColor) !important;' : '' }}"> عن ولاية الخرطوم </a></h6>
                </div>
            </li>
            <li style="flex-direction: column; align-items: stretch; padding-bottom: 10px;">
                <div class="recent__text d-flex justify-content-between align-items-center w-100" data-bs-toggle="collapse" data-bs-target="#campuses-collapse-ar" aria-expanded="{{ in_array(request()->route('slug'), ['main_campus', 'southern_campus', 'western_campus', 'medicale_campus', 'music_drama_campus']) ? 'true' : 'false' }}" style="cursor: pointer;">
                    <h6 class="m-0"><a href="javascript:void(0)" class="text-dark" style="{{ in_array(request()->route('slug'), ['main_campus', 'southern_campus', 'western_campus', 'medicale_campus', 'music_drama_campus']) ? 'color: var(--primaryColor) !important;' : '' }}"> مجمعات الجامعة </a></h6>
                    <i class="icofont-rounded-down"></i>
                </div>
                <div class="collapse mt-2 w-100 pe-2 {{ in_array(request()->route('slug'), ['main_campus', 'southern_campus', 'western_campus', 'medicale_campus', 'music_drama_campus']) ? 'show' : '' }}" id="campuses-collapse-ar">
                    <a href="{{ route('home_dynamic_page_ar', ['slug' => 'main_campus']) }}" class="d-block mb-2 text-dark" style="font-size: 14px; {{ request()->route('slug') == 'main_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-left me-2" style="font-size: 12px; color: var(--primaryColor);"></i>المجمع الرئيسي</a>
                    <a href="{{ route('home_dynamic_page_ar', ['slug' => 'southern_campus']) }}" class="d-block mb-2 text-dark" style="font-size: 14px; {{ request()->route('slug') == 'southern_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-left me-2" style="font-size: 12px; color: var(--primaryColor);"></i>المجمع الجنوبي</a>
                    <a href="{{ route('home_dynamic_page_ar', ['slug' => 'western_campus']) }}" class="d-block mb-2 text-dark" style="font-size: 14px; {{ request()->route('slug') == 'western_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-left me-2" style="font-size: 12px; color: var(--primaryColor);"></i>المجمع الغربي</a>
                    <a href="{{ route('home_dynamic_page_ar', ['slug' => 'medicale_campus']) }}" class="d-block mb-2 text-dark" style="font-size: 14px; {{ request()->route('slug') == 'medicale_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-left me-2" style="font-size: 12px; color: var(--primaryColor);"></i>المجمع الطبي</a>
                    <a href="{{ route('home_dynamic_page_ar', ['slug' => 'music_drama_campus']) }}" class="d-block text-dark" style="font-size: 14px; {{ request()->route('slug') == 'music_drama_campus' ? 'color: var(--primaryColor) !important;' : '' }}"><i class="icofont-rounded-double-left me-2" style="font-size: 12px; color: var(--primaryColor);"></i>مجمع الموسيقى والدراما</a>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'sust_mission_vision_goals']) }}" style="{{ request()->route('slug') == 'sust_mission_vision_goals' ? 'color: var(--primaryColor) !important;' : '' }}"> الرؤية والرسالة والأهداف </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('former_vice_chancellors_ar') }}" style="{{ request()->routeIs('former_vice_chancellors_ar') ? 'color: var(--primaryColor) !important;' : '' }}"> المدراء السابقين للجامعة </a></h6>
                </div>
            </li>

        </ul>

    </div>
</div>