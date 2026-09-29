<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">

        <h4 class="sidebar__title">Main Menu</h4>
        <ul class="recent__list">
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'administration']) }}"
                            class="{{ (request()->route('slug') === 'administration' || request()->is('*/administration') || request()->is('administration')) ? 'active' : '' }}">
                            University Council </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'vice_chancellor']) }}"
                            class="{{ (request()->route('slug') === 'vice_chancellor' || request()->is('*/vice_chancellor') || request()->is('vice_chancellor')) ? 'active' : '' }}">
                            Vice Chancellor </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'deputy_vice_chancellor']) }}"
                            class="{{ (request()->route('slug') === 'deputy_vice_chancellor' || request()->is('*/deputy_vice_chancellor') || request()->is('deputy_vice_chancellor')) ? 'active' : '' }}">
                            Deputy Vice Chancellor </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="{{ route('home_dynamic_page', ['slug' => 'principal']) }}"
                            class="{{ (request()->route('slug') === 'principal' || request()->is('*/principal') || request()->is('principal')) ? 'active' : '' }}">
                            Principal </a></h6>
                </div>
            </li>
            <li>
                <div class="recent__text">
                    <h6><a href="#">Council of Deans</a></h6>
                </div>
            </li>
        </ul>

    </div>
</div>
