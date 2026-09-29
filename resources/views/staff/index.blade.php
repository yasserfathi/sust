@extends('staff.layout')

@section('title', 'Home')


@section('content')
    <!-- Hero Section - Modern Card Design -->
    <section class="hero-modern">
        <div class="container position-relative z-2">

            <!-- Main Hero Card -->
            <div class="hero-content-card p-4 p-lg-5 shadow-sm border-0">
                <div class="row align-items-center">

                    <!-- Text Column (Right in RTL) -->
                    <div class="col-lg-7 order-2 order-lg-1">
                        <h2 class="hero-welcome">
                            {{ $user->name_en ?? $user->name }}
                        </h2>
                        <h1 class="hero-name-large">
                            {{ $user->staff_latest?->grade_en ?? 'Staff Member' }}
                        </h1>
                        <p class="hero-role">
                            {{ $user->staff_latest?->department?->college?->name_en ?? '' }} -
                            {{ $user->staff_latest?->department?->name_en ?? '' }}
                        </p>
                        <div class="d-flex gap-3 hero-buttons flex-wrap">
                            <a href="mailto:{{ $user->email }}" class="btn btn-primary rounded-pill">Contact</a>
                            <a href="{{ route('staff.scientific_papers', $user->slug) }}"
                                class="btn btn-outline-primary rounded-pill">View Research</a>
                        </div>
                    </div>

                    <!-- Image-->
                    <div class="col-lg-5 order-1 order-lg-2 text-center mb-4 mb-lg-0">
                        <div class="hero-profile-frame" data-aos="zoom-in" data-aos-duration="1000">
                            <img src="{{ versioned_asset($user->img) }}" alt="{{ $user->name }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Main Content Section -->
    <div class="container main-content-overlap position-relative z-3">
        <div class="row">
            <!-- Sidebar (About Me) - Now Sticky -->
            <div class="col-lg-4 mb-4">
                <div class="sticky-sidebar sticky-top" style="top: 100px; z-index: 10;">
                    <div class="about-me-card shadow-sm border-0 overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <h3 class="section-header mb-3">About Me</h3>
                            <p class="leading-loose text-muted text-break">
                                {!! nl2br(e($user->staff_latest?->about_me ?? '')) !!}
                            </p>
                            <hr class="my-4 opacity-25">
                            <div class="d-flex justify-content-center gap-3 social-links flex-wrap">
                                @if ($user->twitter)
                                    <a href="{{ $user->twitter }}" class="text-secondary fs-5"><i
                                            class="icofont-twitter"></i></a>
                                @endif
                                @if ($user->linkedin)
                                    <a href="{{ $user->linkedin }}" class="text-secondary fs-5"><i
                                            class="icofont-linkedin"></i></a>
                                @endif

                                <a href="{{ route('staff.google_scholar', $user->slug) }}" class="text-secondary fs-5" title="Google Scholar"><i class="icofont-graduate"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content (Publications) -->
            <div class="col-lg-8">
                <!-- Latest Publications -->
                <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="section-header m-0">Publications</h3>
                        @if(count($user->staff_latest?->scientific_papers ?? []) > 0)
                            <a href="{{ route('staff.scientific_papers', $user->slug) }}"
                                class="text-primary text-decoration-none fw-bold">View All <i
                                    class="icofont-arrow-left"></i></a>
                        @endif
                    </div>

                    @forelse ($user->staff_latest?->scientific_papers ?? [] as $paper)
                        <div class="pub-card d-flex gap-3 mb-4 align-items-start">
                            @if ($paper->thumb_img)
                                <img src="{{ versioned_asset($paper->thumb_img) }}" alt="Publication"
                                    class="rounded-3 object-fit-cover flex-shrink-0" width="120" height="90">
                            @else
                                <img src="{{ versioned_asset('img/grid/grid_1.jpg') }}" alt="Publication"
                                    class="rounded-3 object-fit-cover flex-shrink-0" width="120" height="90">
                            @endif
                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="fw-bold mb-2">{{ $paper->item_val }}</h5>
                                <p class="text-muted small mb-2">{{ $paper->detail ?? '' }}</p>
                                <a href="{{ $paper->url }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary rounded-pill">View
                                    PDF</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No publications available at the moment.</p>
                    @endforelse

                </div>

                {{-- News & Updates (Hidden until dynamic source is ready)
                <div class="bg-white p-4 rounded-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="section-header m-0">News & Updates</h3>
                    </div>

                    <div class="news-list">
                        <div class="news-item p-3 mb-3 bg-light rounded-3 border-start border-4 border-primary">
                            <span class="badge bg-primary mb-2">New</span>
                            <h6 class="fw-bold">Registration Open for Artificial Intelligence Course (CS402)</h6>
                            <p class="text-muted small m-0">I am pleased to announce that registration is now open for
                                students interested in joining...
                            </p>
                        </div>
                        <div class="news-item p-3 bg-light rounded-3 border-start border-4 border-warning">
                            <span class="badge bg-warning text-dark mb-2">Important</span>
                            <h6 class="fw-bold">Midterm Exam Schedule for Current Semester</h6>
                            <p class="text-muted small m-0">Please refer to the attached schedule to know the midterm exam
                                dates...
                            </p>
                        </div>
                    </div>
                </div>
                --}}
            </div>
        </div>
    </div>
@endsection