@extends('staff.layout')

@section('title', 'Books & Publications - ' . ($user->name_en ?? $user->name ?? ''))

@section('content')
    <!-- Hero Section -->
    <section class="hero-modern">
        <div class="container position-relative z-2">
            <!-- Main Hero Card -->
            <div class="hero-content-card text-center" data-aos="fade-up" data-aos-duration="1000">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 bg-light rounded-circle shadow-sm" style="width: 64px; height: 64px; color: var(--sust-primary);">
                            <i class="icofont-book-alt fs-2"></i>
                        </div>
                        <h1 class="hero-name-large mb-3" data-aos="fade-up" data-aos-delay="200">
                            Books and Book Chapters
                        </h1>
                        <p class="text-muted mb-3 fs-6">
                            Published books, academic volumes, and book chapters by {{ $user->name_en ?? $user->name ?? '' }}
                        </p>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-center mb-0" data-aos="fade-up" data-aos-delay="300">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('staff_home', $user?->slug ?? '') }}" class="text-secondary text-decoration-none">
                                        <i class="icofont-home me-1"></i> Home
                                    </a>
                                </li>
                                <li class="breadcrumb-item active text-muted" aria-current="page">Books</li>
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

            <!-- Main Content (Books List) -->
            <div class="col-lg-8" data-aos="fade-right" data-aos-delay="400">
                <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm border-0 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 rounded-3 bg-primary bg-opacity-10 text-primary d-inline-flex">
                                <i class="icofont-book fs-4"></i>
                            </div>
                            <div>
                                <h3 class="section-header m-0 fs-4 fw-bold">Publications List</h3>
                                <small class="text-muted">Total items: {{ count($books ?? []) }}</small>
                            </div>
                        </div>
                        <a href="{{ route('staff_home', $user?->slug ?? '') }}"
                             class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold text-nowrap d-inline-flex align-items-center gap-1">
                            <i class="icofont-arrow-left"></i>
                            <span>Home</span>
                        </a>
                    </div>

                    @forelse ($books ?? [] as $index => $book)
                        <div class="academic-book-item p-3 mb-3 rounded-3 bg-white border border-light-subtle shadow-sm">
                            <div class="d-flex gap-3 align-items-center">
                                <!-- Book Cover / Thumbnail -->
                                <div class="book-cover-box flex-shrink-0 position-relative rounded-2 overflow-hidden border">
                                    @if (!empty($book->thumb_img) || !empty($book->img))
                                        <img src="{{ versioned_asset($book->thumb_img ?? $book->img) }}" 
                                             alt="{{ $book->item_val }}" 
                                             class="img-fluid book-cover-img"
                                             loading="lazy">
                                    @else
                                        <div class="book-cover-placeholder d-flex align-items-center justify-content-center h-100 text-white">
                                            <i class="icofont-book-alt fs-3 opacity-75"></i>
                                        </div>
                                    @endif
                                </div>

                                <!-- Book Info -->
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <h5 class="fw-bold text-dark mb-1 fs-6">
                                            {{ $book->item_val }}
                                        </h5>
                                        @if(!empty($book->year))
                                            <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.75rem;">
                                                <i class="icofont-calendar me-1"></i> {{ $book->year }}
                                            </span>
                                        @endif
                                    </div>

                                    @if (!empty($book->detail))
                                        <div class="text-muted small mb-2 book-details-text">
                                            {!! $book->detail !!}
                                        </div>
                                    @endif

                                    @if (!empty($book->url))
                                        <div class="pt-1">
                                            <a href="{{ $book->url }}" target="_blank" rel="noopener noreferrer" 
                                               class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 0.8rem;">
                                                <span>Book Link</span>
                                                <i class="icofont-external-link"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3 text-muted opacity-50">
                                <i class="icofont-book-alt" style="font-size: 3.5rem;"></i>
                            </div>
                            <h5 class="fw-bold text-secondary">No books or chapters published yet</h5>
                            <p class="text-muted small">New books and publications will appear here once added.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
@endsection

