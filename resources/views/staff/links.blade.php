@extends('staff.layout')

@section('title', 'Important Links - ' . ($user->name ?? ''))

@section('content')
    <!-- Hero Section -->
    <section class="hero-modern">
        <div class="container position-relative z-2">
            <!-- Main Hero Card -->
            <div class="hero-content-card text-center" data-aos="fade-up" data-aos-duration="1000">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 bg-light rounded-circle shadow-sm" style="width: 64px; height: 64px; color: var(--sust-primary);">
                            <i class="icofont-link fs-2"></i>
                        </div>
                        <h1 class="hero-name-large mb-3" data-aos="fade-up" data-aos-delay="200">
                            Important Links
                        </h1>
                        <p class="text-muted mb-3 fs-6">
                            Academic profiles, portfolios, and external reference links for {{ $user->name ?? '' }}
                        </p>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0" data-aos="fade-up" data-aos-delay="300">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('staff_home', $user?->slug ?? '') }}" class="text-secondary text-decoration-none">
                                        <i class="icofont-home me-1"></i> Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-muted" aria-current="page">Important Links</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content Section -->
    <div class="container main-content-overlap position-relative z-3 pb-5">
        <div class="row">
            <!-- Sidebar (Navigation Menu) -->
            @include('staff.sidebar')

            <!-- Main Content (Links List) -->
            <div class="col-lg-8" data-aos="fade-right" data-aos-delay="400">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border-0 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-3 bg-primary bg-opacity-10 text-primary d-inline-flex">
                                <i class="icofont-globe fs-4"></i>
                            </div>
                            <div>
                                <h3 class="section-header m-0 fs-4 fw-bold">Links & Portals</h3>
                                <small class="text-muted">Total: {{ count($links ?? []) }}</small>
                            </div>
                        </div>
                        <a href="{{ route('staff_home', $user?->slug ?? '') }}"
                             class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold text-nowrap d-inline-flex align-items-center gap-1">
                            <i class="icofont-arrow-left"></i>
                            <span>Home</span>
                        </a>
                    </div>

                    @forelse ($links ?? [] as $index => $link)
                        <div class="link-item-card p-3 p-md-4 mb-3 rounded-4 border bg-light bg-opacity-50 transition-all hover-shadow d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="link-icon-box rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary flex-shrink-0" style="width: 48px; height: 48px;">
                                    <i class="icofont-external-link fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1 text-dark fs-6">{{ $link->item_val }}</h5>
                                    @if (!empty($link->url))
                                        <span class="text-muted small d-inline-block text-truncate" style="max-width: 320px;">
                                            {{ $link->url }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if (!empty($link->url))
                                <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer" 
                                   class="btn btn-sm btn-primary rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 shadow-sm text-nowrap">
                                    <span>Visit Link</span>
                                    <i class="icofont-external-link"></i>
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3 text-muted opacity-50">
                                <i class="icofont-link-alt" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="fw-bold text-secondary">No important links found</h5>
                            <p class="text-muted small">New links and platforms will appear here once added.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
@endsection

