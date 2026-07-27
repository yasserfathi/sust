@extends('staff.layout')

@section('content')
    <!-- Hero Section - Modern Card Design -->
    <section class="hero-modern">
        <div class="container position-relative z-2">

            <!-- Main Hero Card -->
            <div class="hero-content-card" data-aos="fade-up" data-aos-duration="1000">
                <div class="row align-items-center">

                    <!-- Text Column (Right in RTL) -->
                    <div class="col-lg-7 order-2 order-lg-1">
                        <h2 class="hero-welcome" data-aos="fade-up" data-aos-delay="200">
                            {{ $user->name }}
                        </h2>
                        <h1 class="hero-name-large" data-aos="fade-up" data-aos-delay="300">
                            {{ $job_titles[$user->staff_latest?->job_title ?? ''] ?? ($user->staff_latest?->job_title ?? '') }}
                        </h1>
                        <p class="hero-role" data-aos="fade-up" data-aos-delay="400">
                            كلية {{ $user->staff_latest?->department?->college?->name ?? '' }} - قسم {{ $user->staff_latest?->department?->name ?? '' }}
                        </p>
                        <div class="d-flex gap-3 hero-buttons" data-aos="fade-up" data-aos-delay="500">
                            <a href="#" class="btn btn-primary rounded-pill">تواصل معي</a>
                            @if($resume && $resume->file)
                            <a href="{{ asset('storage/' . $resume->file) }}" target="_blank" class="btn btn-outline-primary rounded-pill">السيرة الذاتية</a>
                            @else
                            <a href="#" class="btn btn-outline-primary rounded-pill">السيرة الذاتية (غير متوفر)</a>
                            @endif
                        </div>
                    </div>

                    <!-- Image Column (Left in RTL) -->
                    <div class="col-lg-5 order-1 order-lg-2 text-center mb-4 mb-lg-0">
                        <div class="hero-profile-frame" data-aos="zoom-in" data-aos-duration="1000">
                            <img src="{{ asset($user->img) }}" alt="{{ $user->name }}" class="img-fluid">
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
            <div class="col-lg-4 mb-4" data-aos="fade-left" data-aos-delay="200">
                <div class="about-me-card sticky-sidebar">
                    <div class="card-body p-4 text-center">
                        <h3 class="section-header mb-3">نبذة عني</h3>
                        <p class="leading-loose text-muted">
                            {!! nl2br(e($user->staff_latest?->about_me ?? '')) !!}
                        </p>
                        <hr class="my-4 opacity-25">
                        <div class="d-flex justify-content-center gap-3 social-links">
                            @if ($user->twitter)
                                <a href="{{ $user->twitter }}" class="text-secondary fs-5"><i class="icofont-twitter"></i></a>
                            @endif
                            @if ($user->linkedin)
                                <a href="{{ $user->linkedin }}" class="text-secondary fs-5"><i class="icofont-linkedin"></i></a>
                            @endif
                            <a href="#" class="text-secondary fs-5"><i class="icofont-google-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content (Publications & News) -->
            <div class="col-lg-8" data-aos="fade-right" data-aos-delay="400">
                <!-- Latest Publications -->
                <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="section-header m-0">أحدث المنشورات العلمية</h3>
                    </div>

                    @forelse ($user->staff_latest?->scientific_papers ?? [] as $paper)
                        <div class="pub-card d-flex gap-3 mb-4 align-items-start">
                            @if ($paper->thumb_img)
                                <img src="{{ asset($paper->thumb_img) }}" alt="Publication" class="rounded-3 object-fit-cover"
                                    width="120" height="90">
                            @else
                                <img src="{{ asset('img/grid/grid_1.jpg') }}" alt="Publication" class="rounded-3 object-fit-cover"
                                    width="120" height="90">
                            @endif
                            <div>
                                <h5 class="fw-bold mb-2">{{ $paper->item_val }}</h5>
                                <p class="text-muted small mb-2">{{ $paper->detail ?? '' }}</p>
                                @if($paper->url)
                                    <a href="{{ $paper->url }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary rounded-pill">View PDF</a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">لا توجد أبحاث منشورة حالياً.</p>
                    @endforelse

                </div>
                
                <!-- Academic Resume Download Box -->
                @if($resume && ($resume->file || $resume->file_en))
                <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="section-header m-0">تنزيل السيرة الذاتية كاملة</h3>
                    </div>
                    <div class="row text-center mt-3">
                        @if($resume->file)
                        <div class="col-md-6 mb-3">
                             <a href="{{ asset('storage/' . $resume->file) }}" target="_blank" class="btn btn-primary rounded-pill px-4 py-2 w-100">
                               <i class="icofont-download me-2"></i> تحميل السيرة الذاتية (عربي)
                             </a>
                        </div>
                        @endif
                        @if($resume->file_en)
                        <div class="col-md-6 mb-3">
                             <a href="{{ asset('storage/' . $resume->file_en) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-4 py-2 w-100">
                               <i class="icofont-download me-2"></i> تحميل السيرة الذاتية (انجليزي)
                             </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                
            </div>
        </div>
    </div>
@endsection
