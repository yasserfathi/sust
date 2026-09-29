<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">

        <h4 class="sidebar__title">القائمة الرئيسية</h4>
        <ul class="recent__list">
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'administration']) }}"
                            class="{{ (request()->route('slug') === 'administration' || request()->is('*/administration') || request()->is('administration')) ? 'active' : '' }}">
                            مجلس الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'vice_chancellor']) }}"
                            class="{{ (request()->route('slug') === 'vice_chancellor' || request()->is('*/vice_chancellor') || request()->is('vice_chancellor')) ? 'active' : '' }}">
                            مدير الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'deputy_vice_chancellor']) }}"
                            class="{{ (request()->route('slug') === 'deputy_vice_chancellor' || request()->is('*/deputy_vice_chancellor') || request()->is('deputy_vice_chancellor')) ? 'active' : '' }}">
                            نائب مدير الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page_ar', ['slug' => 'principal']) }}"
                            class="{{ (request()->route('slug') === 'principal' || request()->is('*/principal') || request()->is('principal')) ? 'active' : '' }}">
                            وكيل الجامعة </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="#">مجلس العمداء</a></h6>
                </div>
            </li>
        </ul>

    </div>
</div>
