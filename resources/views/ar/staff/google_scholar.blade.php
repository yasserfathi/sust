@extends('ar.staff.layout')

@section('title', 'الباحث العلمي')


@section('content')
    <!-- Hero Section -->
    <section class="hero-modern">
        <div class="container position-relative z-2">
            <!-- Main Hero Card -->
            <div class="hero-content-card text-center" data-aos="fade-up" data-aos-duration="1000">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <h1 class="hero-name-large mb-3" data-aos="fade-up" data-aos-delay="200">
                            Google Scholar
                        </h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0" data-aos="fade-up" data-aos-delay="300">
                                <li class="breadcrumb-item"><a href="{{ route('staff_home_ar', $user?->slug ?? '') }}"
                                        class="text-secondary text-decoration-none">الرئيسية</a></li>
                                <li class="breadcrumb-item active text-muted" aria-current="page">Google Scholar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <div class="container main-content-overlap position-relative z-3">
        <div class="row">
            <!-- Sidebar (Navigation Menu) -->
            @include('ar.staff.sidebar')

            <!-- Main Content (Publications) -->
            <div class="col-lg-8" data-aos="fade-right" data-aos-delay="400">
                <div class="bg-white p-4 p-md-5 rounded-3 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="section-header m-0">Google Scholar</h3>
                        <a href="{{ route('staff_home_ar', $user?->slug ?? '') }}"
                             class="text-primary text-decoration-none fw-bold text-nowrap">
                            الرئيسية <i class="icofont-arrow-left"></i>
                        </a>
                    </div>

                    @forelse ($scholar ?? [] as $item)
                        <div class="pub-card d-flex gap-3 mb-4 align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">{{ $item->item_val }}</h5>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">لا توجد سجلات Google Scholar بعد.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
@endsection
