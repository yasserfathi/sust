<div class="col-lg-4 mb-4" data-aos="fade-left" data-aos-delay="200">
    <div class="sidebar-menu-card sticky-sidebar">

        <!-- Section: Main Menu -->
        <div class="sidebar-section-title">Main Menu</div>
        <ul class="sidebar-menu-list">
            <li>
                <a href="{{ route('staff_home', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff_home') ? 'active' : '' }}">
                    <span><i class="icofont-user"></i> About me</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.cv', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.cv') ? 'active' : '' }}">
                    <span><i class="icofont-file-document"></i> CV</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.books', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.books') ? 'active' : '' }}">
                    <span><i class="icofont-book"></i> Books and book chapters</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.scientific_papers', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.scientific_papers') ? 'active' : '' }}">
                    <span><i class="icofont-file-document"></i> Scientific papers</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.running_projects', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.running_projects') ? 'active' : '' }}">
                    <span><i class="icofont-flask"></i> Ongoing research projects</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.courses', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.courses') ? 'active' : '' }}">
                    <span><i class="icofont-black-board"></i> Courses</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.communityservice', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.communityservice') ? 'active' : '' }}">
                    <span><i class="icofont-users-social"></i> Community service</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.workshops', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.workshops') ? 'active' : '' }}">
                    <span><i class="icofont-presentation"></i> Workshops and conferences</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.supervising_projects', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.supervising_projects') ? 'active' : '' }}">
                    <span><i class="icofont-graduate-alt"></i> Supervision of research projects</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.research_topics', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.research_topics') ? 'active' : '' }}">
                    <span><i class="icofont-laboratory"></i> Research topics</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.positions', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.positions') ? 'active' : '' }}">
                    <span><i class="icofont-tie"></i> Administrative positions</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.committees', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.committees') ? 'active' : '' }}">
                    <span><i class="icofont-users-alt-3"></i> Committees and associations</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.training_courses', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.training_courses') ? 'active' : '' }}">
                    <span><i class="icofont-certificate-alt-1"></i> Training courses</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.certificates', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.certificates') ? 'active' : '' }}">
                    <span><i class="icofont-medal"></i> Awards and certificates of appreciation</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.google_scholar', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.google_scholar') ? 'active' : '' }}">
                    <span><i class="icofont-google-plus"></i> Google Scholar</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.articles', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.articles') ? 'active' : '' }}">
                    <span><i class="icofont-read-book"></i> Articles</span>
                </a>
            </li>
            <li>
                <a href="{{ route('staff.links', $user?->name_en ?? '') }}" class="sidebar-link {{ request()->routeIs('staff.links') ? 'active' : '' }}">
                    <span><i class="icofont-link"></i> Important links</span>
                </a>
            </li>
        </ul>

        <!-- Mini About Snippet -->
        <div class="mt-4 pt-4 border-top text-center">
            <p class="text-muted small mb-3">{{ $user->name ?? '' }} -
                {{ $user->staff_latest?->job_title ?? '' }}
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
                @if (isset($user->email) && $user->email)
                    <a href="mailto:{{ $user->email }}" class="text-secondary opacity-50 hover-primary"><i class="icofont-email"></i></a>
                @else
                    <a href="#" class="text-secondary opacity-50 hover-primary"><i class="icofont-email"></i></a>
                @endif
            </div>
        </div>

    </div>
</div>