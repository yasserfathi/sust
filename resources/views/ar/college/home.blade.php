@extends('ar/college/layout')

@section('content')

    <style>
        body {
            background-color: var(--college-bg);
        }

        /* Section Spacing */
        .section-padding {
            padding-top: 60px;
            padding-bottom: 60px;
        }

        /* Cards Common */
        .college-card {
            background: #fff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            height: 100%;
            margin-bottom: 20px;
        }

        .college-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Hero Section */
        .hero-section {
            padding-top: 30px;
            padding-bottom: 30px;
        }

        .hero-slider-wrap {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .herobannerarea__single__slider {
            height: 500px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .hero-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 43, 92, 0.9), transparent);
            padding: 40px;
            color: white;
            text-align: right;
            /* RTL */
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--white);
        }

        .hero-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Announcements Sidebar */
        .announcement-card {
            background: white;
            border-radius: 12px;
            border-top: 5px solid var(--college-primary);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .announcement-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .announcement-header h4 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--college-primary);
        }

        .announcement-list {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .announcement-item {
            padding: 15px 25px;
            border-bottom: 1px solid #f8f9fa;
            transition: background 0.2s;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .announcement-item:last-child {
            border-bottom: none;
        }

        .announcement-item:hover {
            background-color: #fcfcfc;
        }

        .announcement-icon {
            color: var(--college-accent);
            font-size: 1.2rem;
            margin-top: 2px;
            transform: rotate(180deg);
            /* Flip icon for RTL */
        }

        .announcement-link {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 500;
            font-size: 0.95rem;
            line-height: 1.5;
            transition: color 0.2s;
        }

        .announcement-link:hover {
            color: var(--college-secondary);
        }

        /* News Section */
        .section-header-wrap {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-sub-title {
            color: var(--college-accent);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 2px;
            display: block;
            margin-bottom: 10px;
        }

        .section-title-main {
            color: var(--college-primary);
            font-size: 2.2rem;
            font-weight: 800;
        }

        .news-img-wrap {
            height: 240px;
            overflow: hidden;
            position: relative;
        }

        .news-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .college-card:hover .news-img-wrap img {
            transform: scale(1.05);
        }

        .news-date-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            /* RTL */
            left: auto;
            background: var(--college-primary);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            text-align: center;
            line-height: 1.1;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .news-date-day {
            font-size: 1.2rem;
            font-weight: 700;
            display: block;
        }

        .news-date-month {
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .news-content {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            text-align: right;
            /* RTL */
        }

        .news-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .news-title a {
            color: var(--text-dark);
            text-decoration: none;
            transition: color 0.2s;
        }

        .news-title a:hover {
            color: var(--college-secondary);
        }

        .news-excerpt {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .news-footer {
            border-top: 1px solid #eee;
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-read-more {
            color: var(--college-primary);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-read-more i {
            transform: rotate(180deg);
            /* RTL arrow */
        }

        .btn-read-more:hover {
            color: var(--college-accent);
        }

        .btn-view-all {
            background: var(--college-primary);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 43, 92, 0.3);
        }

        .btn-view-all:hover {
            background: var(--college-secondary);
            transform: translateY(-2px);
            color: white;
        }

        @media (max-width: 991px) {
            .herobannerarea__single__slider {
                height: 350px;
            }

            .hero-title {
                font-size: 1.8rem;
            }
        }
    </style>

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
                                        style="background-image: url({{ URL::to($gallery->img) }});">
                                        <div class="hero-overlay">
                                            <div class="hero-content" data-aos="fade-up">
                                                <div class="hero-subtitle">جامعة السودان للعلوم والتكنولوجيا</div>
                                                <h2 class="hero-title">{{$gallery->title}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Controls -->
                            <div class="slider__controls__wrap slider__controls__pagination slider__controls__arrows">
                                <div class="swiper-pagination"></div>
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
                            @foreach ($data['recent_ads'] as $ad)
                                <div class="announcement-item">
                                    <i class="icofont-curved-double-right announcement-icon"></i>
                                    <div class="content">
                                        {{-- Assuming we want to link to Arabic ad details. --}}
                                        {{-- Note: English view used 'ads_ar_detail' which is likely 'ads_detail_ar' or
                                        'college_ads_detail_ar'. Using safe fallback --}}
                                        <a href="{{ route('college_ads_detail_ar', ['name' => $data['name'], 'slug' => str_replace(' ', '-', $ad->title)]) }}"
                                            class="announcement-link">
                                            {{ $ad->title }}
                                        </a>
                                    </div>
                                </div>
                            @endforeach
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
                                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote={{ urlencode($newsItem->title) }}"
                                            target="_blank" class="text-muted me-2"><i class="icofont-facebook"></i></a>
                                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($newsItem->title) }}&url={{ urlencode(url()->current()) }}"
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