<div class="col-lg-4 mb-4" data-aos="fade-left" data-aos-delay="200">
    <div class="sidebar-menu-card sticky-sidebar">

        <!-- Section: Main Menu -->
        <div class="sidebar-section-title">القائمة الرئيسية</div>
        <ul class="sidebar-menu-list">
            <li>
                <a href="{{ route('staff_home_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff_home_ar') ? 'active' : '' }}">
                    <span><i class="icofont-home"></i> الرئيسية</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.cv_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.cv_ar') ? 'active' : '' }}">
                    <span><i class="icofont-file-document"></i> السيرة الذاتية</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.books_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.books_ar') ? 'active' : '' }}">
                    <span><i class="icofont-book"></i> الكتب وفصول الكتب</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.scientific_papers_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.scientific_papers_ar') ? 'active' : '' }}">
                    <span><i class="icofont-file-document"></i> الأوراق العلمية</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.running_projects_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.running_projects_ar') ? 'active' : '' }}">
                    <span><i class="icofont-flask"></i> مشاريع البحث الجارية</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.courses_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.courses_ar') ? 'active' : '' }}">
                    <span><i class="icofont-black-board"></i> الكورسات</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.communityservice_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.communityservice_ar') ? 'active' : '' }}">
                    <span><i class="icofont-users-social"></i> الخدمة المجتمعية</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.workshops_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.workshops_ar') ? 'active' : '' }}">
                    <span><i class="icofont-presentation"></i> الورش والمؤتمرات</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.supervising_projects_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.supervising_projects_ar') ? 'active' : '' }}">
                    <span><i class="icofont-graduate-alt"></i> إشراف مشاريع البحث</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.research_topics_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.research_topics_ar') ? 'active' : '' }}">
                    <span><i class="icofont-laboratory"></i> مواضيع البحث</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.positions_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.positions_ar') ? 'active' : '' }}">
                    <span><i class="icofont-tie"></i> المناصب الإدارية</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.committees_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.committees_ar') ? 'active' : '' }}">
                    <span><i class="icofont-users-alt-3"></i> اللجان والجمعيات</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.training_courses_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.training_courses_ar') ? 'active' : '' }}">
                    <span><i class="icofont-certificate-alt-1"></i> الكورسات التدريبية</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.certificates_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.certificates_ar') ? 'active' : '' }}">
                    <span><i class="icofont-medal"></i> الجوائز والشهادات من التقدير</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.google_scholar_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.google_scholar_ar') ? 'active' : '' }}">
                    <span><i class="icofont-graduate"></i> الباحث العلمي من Google</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.articles_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.articles_ar') ? 'active' : '' }}">
                    <span><i class="icofont-read-book"></i> المقالات</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.links_ar', $user?->slug ?? '') }}"
                    class="sidebar-link {{ request()->routeIs('staff.links_ar') ? 'active' : '' }}">
                    <span><i class="icofont-link"></i> الروابط المهمة</span>
                </a>
            </li>
        </ul>

        <!-- Mini About Snippet -->
        <div class="mt-4 pt-4 border-top text-center">
            <p class="text-muted small mb-3">{{ $user->name ?? '' }} -
                {{ $user->staff_latest?->grade ?? '' }}
            </p>
            <div class="d-flex justify-content-center gap-3 social-links">
                @if (isset($user->twitter) && $user->twitter)
                    <a href="{{ $user->twitter }}" class="text-secondary opacity-50 hover-primary"><i
                            class="icofont-twitter"></i></a>
                @endif
                @if (isset($user->linkedin) && $user->linkedin)
                    <a href="{{ $user->linkedin }}" class="text-secondary opacity-50 hover-primary"><i
                            class="icofont-linkedin"></i></a>
                @endif
                <a href="#" class="text-secondary opacity-50 hover-primary"><i class="icofont-email"></i></a>
            </div>
        </div>

    </div>
</div>