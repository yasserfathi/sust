@extends('staff.layout')

@section('title', 'Papers')


@section('content')
    <!-- Hero Section -->
    <section class="hero-modern">
        <div class="container position-relative z-2">
            <div class="hero-content-card">
                <div class="row align-items-center">
                    <div class="col-lg-8 order-2 order-lg-1">
                        <h2 class="hero-welcome">{{ $user->name }}</h2>
                        <h1 class="hero-name-large">
                            {{ $grades[$user->staff_latest?->grade ?? ''] ?? ($user->staff_latest?->grade ?? '') }}
                        </h1>
                        <p class="hero-role">
                            كلية {{ $user->staff_latest?->department?->college?->name ?? '' }}
                        </p>
                    </div>
                    <div class="col-lg-4 order-1 order-lg-2 text-center mb-4 mb-lg-0">
                        <div class="hero-profile-frame">
                            <img src="{{ versioned_asset($user->img) }}" alt="{{ $user->name }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container main-content-overlap position-relative z-3">
        <div class="row">
            <div class="col-lg-12" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-white p-4 rounded-3 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                        <h3 class="section-header m-0">كل الأبحاث والمنشورات</h3>
                        <a href="{{ route('staff_home', $user?->slug ?? '') }}"
                            class="text-primary text-decoration-none fw-bold">
                            <i class="icofont-arrow-right"></i> عودة للملف الشخصي
                        </a>
                    </div>

                    @forelse ($papers ?? [] as $paper)
                        <div class="pub-card d-flex gap-3 mb-4 align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-2">{{ $paper->item_val }}</h5>
                                <!-- <a href="#" class="btn btn-sm btn-outline-primary rounded-pill">View PDF</a> -->
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">لا توجد أبحاث منشورة حالياً.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
@endsection