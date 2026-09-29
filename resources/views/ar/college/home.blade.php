@extends('ar/college/layout')

@section('title', 'الصفحة الرئيسية')


@section('content')

    <!-- Hero & Announcements Section -->
    <div class="hero-section">
        <div class="container">
            <div class="row g-4">
                <!-- Slider Column -->
                <div class="col-lg-8">
                    <div class="hero-slider-wrap">
                        <div class="swiper ecommerce__slider">
                            <div class="swiper-wrapper">
                                @foreach ($data['gallery'] as $gallery)
                                    <div class="swiper-slide herobannerarea__single__slider"
                                        style="background-image: url('{{ versioned_asset($gallery->img) }}');">
                                        <div class="hero-overlay">
                                            <div class="hero-content" data-aos="fade-up">
                                                <div class="hero-subtitle">{{ $data['college_title'] ?? ($data['college']->name ?? '') }}</div>
                                                <h2 class="hero-title">{{$gallery->title}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Controls -->
                            <div class="college-slider-controls">
                                <button type="button" class="college-slider-btn college-btn-prev" aria-label="السابق">
                                    <i class="icofont-rounded-right"></i>
                                </button>
                                <div class="swiper-pagination"></div>
                                <button type="button" class="college-slider-btn college-btn-next" aria-label="التالي">
                                    <i class="icofont-rounded-left"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcements Column -->
                <div class="col-lg-4">
                    <div class="announcement-card" data-aos="fade-left">
                        <div class="announcement-header">
                            <i class="icofont-notification text-warning fs-4"></i>
                            <h4>إعلانات مهمة</h4>
                        </div>
                        <div class="announcement-list">
                            @forelse ($data['recent_ads'] as $ad)
                                <div class="announcement-item">
                                    <i class="icofont-curved-double-right announcement-icon"></i>
                                    <div class="content">
                                        <a href="{{ route(($data['college_type'] ?? 'college') . '_ads_detail_ar', ['name' => $data['name'], 'slug' => $ad->slug ?: str_replace(' ', '-', $ad->title)]) }}"
                                            class="announcement-link">
                                            {{ $ad->title }}
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted p-3 mb-0" style="font-size: 14px;">لا توجد إعلانات حالياً.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero Section End -->

    <!-- News Section -->
    <div class="section-padding">
        <div class="container">
            <div class="row" data-aos="fade-up">
                <div class="section__title text-center">
                    <div class="section-header mb-4">
                        <span class="sub-title">الأخبار</span>
                        <h2 class="section-title">أحدث <span class="highlight">الأخبار والفعاليات</span></h2>
                    </div>
                </div>
            </div>

            <div class="row g-4 justify-content-center">

                @foreach($data['news'] as $newsItem)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                        <div class="college-card">
                            <div class="news-img-wrap">
                                @if(isset($newsItem->photos[0]))
                                    <img loading="lazy" src="{{ URL::to($newsItem->photos[0]->img) }}" alt="{{ $newsItem->title }}">
                                @endif
                                <div class="news-date-badge">
                                    <span class="news-date-day">{{ Carbon\Carbon::parse($newsItem->news_date)->day}}</span>
                                    <span
                                        class="news-date-month">{{ Carbon\Carbon::parse($newsItem->news_date)->format('M')}}</span>
                                </div>
                            </div>
                            <div class="news-content">
                                <h3 class="news-title">
                                    <a
                                        href="{{ route('college_news_detail_ar', ['name' => $data['name'], 'slug' => $newsItem->slug ?: str_replace(' ', '-', $newsItem->title)]) }}">
                                        {{ Str::limit($newsItem->title, 60) }}
                                    </a>
                                </h3>
                                <div class="news-excerpt">
                                    {{ Str::limit($newsItem->detail_portion, 100) }}
                                </div>
                                <div class="news-footer mt-auto">
                                    <a href="{{ route('college_news_detail_ar', ['name' => $data['name'], 'slug' => $newsItem->slug ?: str_replace(' ', '-', $newsItem->title)]) }}"
                                        class="btn-read-more">
                                        اقرأ المزيد <i class="icofont-arrow-right"></i>
                                    </a>

                                    <!-- Share Icons -->
                                    <div class="share-icons">
                                        <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => $newsItem->title]) }}"
                                            target="_blank" class="text-muted me-2"><i class="icofont-facebook"></i></a>
                                        <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query(['text' => $newsItem->title, 'url' => urlencode(url()->current())]) }}"
                                            target="_blank" class="text-muted"><i class="icofont-twitter"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="col-xl-12 mt-5" data-aos="fade-up">
                <div class="blogarea__bottom__button text-center">
                    <a class="btn-view-all" href="{{ route('college_news_archive_ar', $data['name']) }}">المزيد من
                        الأخبار</a>
                </div>
            </div>
        </div>
    </div>
    <!-- News Section End -->

@endsection