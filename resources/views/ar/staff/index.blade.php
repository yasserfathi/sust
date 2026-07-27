@extends('ar/staff/layout')

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
                            {{ $user->name }}
                        </h2>
                        <h1 class="hero-name-large">
                            {{ $job_titles[$user->staff_latest?->job_title ?? ''] ?? ($user->staff_latest?->job_title ?? '') }}
                        </h1>
                        <p class="hero-role">
                            كلية {{ $user->staff_latest?->department?->college?->name ?? '' }} - قسم
                            {{ $user->staff_latest?->department?->name ?? '' }}
                        </p>
                        <div class="d-flex gap-3 hero-buttons flex-wrap">
                            <a href="#" class="btn btn-primary rounded-pill">تواصل معي</a>
                            <a href="{{ route('staff.scientific_papers_ar', $user->name_en) }}"
                                class="btn btn-outline-primary rounded-pill">شاهد الأبحاث</a>
                        </div>
                    </div>

                    <!-- Image-->
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
            <div class="col-lg-4 mb-4">
                <div class="sticky-sidebar sticky-top" style="top: 100px; z-index: 10;">
                    <div class="about-me-card shadow-sm border-0 overflow-hidden">
                        <div class="card-body p-4 text-center">
                            <h3 class="section-header mb-3">نبذة عني</h3>
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
                                <!-- Add dynamic social links here later if available -->
                                <a href="#" class="text-secondary fs-5"><i class="icofont-google-plus"></i></a>
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
                        <h3 class="section-header m-0">أحدث المنشورات</h3>
                        <a href="{{ route('staff.scientific_papers', $user->name_en) }}"
                            class="text-primary text-decoration-none fw-bold">عرض الكل <i
                                class="icofont-arrow-left"></i></a>
                    </div>

                    @forelse ($user->staff_latest?->scientific_papers ?? [] as $paper)
                        <div class="pub-card d-flex gap-3 mb-4 align-items-start">
                            @if ($paper->thumb_img)
                                <img src="{{ asset($paper->thumb_img) }}" alt="Publication"
                                    class="rounded-3 object-fit-cover flex-shrink-0" width="120" height="90">
                            @else
                                <img src="{{ asset('img/grid/grid_1.jpg') }}" alt="Publication"
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
                        <p class="text-muted text-center">لا توجد أبحاث منشورة حالياً.</p>
                    @endforelse

                </div>

                <!-- News & Updates (Hidden until dynamic source is ready) -->

                <div class="bg-white p-4 rounded-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="section-header m-0">الأخبار والإعلانات</h3>
                    </div>

                    <div class="news-list">
                        <div class="news-item p-3 mb-3 bg-light rounded-3 border-start border-4 border-primary">
                            <span class="badge bg-primary mb-2">جديد</span>
                            <h6 class="fw-bold">تم فتح باب التسجيل لمقرر الذكاء الاصطناعي (CS402)</h6>
                            <p class="text-muted small m-0">يسرني إعلان فتح باب التسجيل للطلاب الراغبين في الانضمام...
                            </p>
                        </div>
                        <div class="news-item p-3 bg-light rounded-3 border-start border-4 border-warning">
                            <span class="badge bg-warning text-dark mb-2">مهم</span>
                            <h6 class="fw-bold">مواعيد الاختبارات النصفية للفصل الدراسي الحالي</h6>
                            <p class="text-muted small m-0">يرجى الاطلاع على الجدول المرفق لمعرفة مواعيد الاختبارات...
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection