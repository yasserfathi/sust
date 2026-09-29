@extends('layout')

@php $active_page = 'home'; @endphp

@section('title', 'Home')

@section('content')
    <!-- herobannerarea__section__start -->
    <div class="herobannerarea herobannerarea__2 herobannerarea__university">
        <div class="swiper university__slider">

            <div class="herobannerarea__slider__wrap swiper-wrapper">

                @foreach ($data['gallery'] as $gallery)
                    <div class="swiper-slide herobannerarea__single__slider"
                        style="background: url({{ URL::to($gallery->img) }});">
                        <div class="container h-100">
                            <div class="row justify-content-center h-100 align-items-center">
                                <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 col-12" data-aos="zoom-in" data-aos-duration="1500">
                                    <div class="herobannerarea__content__wraper text-center premium-slider-content">
                                        <div class="herobannerarea__title">
                                            <div class="herobannerarea__small__title responsive-gallery-subtitle">
                                                <span>Sudan University of Science and Technology</span>
                                            </div>
                                            <div class="herobannerarea__title__heading__2 herobannerarea__title__heading__3">
                                                <h2 class="responsive-gallery-title">{{$gallery->title_en}}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>


        {{-- Controls --}}
        <div class="slider__controls__wrap slider__controls__arrows">
            <div class="swiper-button-next arrow-btn"></div>
            <div class="swiper-button-prev arrow-btn"></div>
        </div>

        {{-- Segmented Progress Bullets & Autoplay Controls --}}
        <div class="gallery-hero-controls">
            <div class="gallery-progress-bullets swiper-pagination"></div>
            <button type="button" class="gallery-pause-btn" aria-label="Pause autoplay" title="Pause / Play">
                <span class="pause-icon">
                    <span class="bar"></span>
                    <span class="bar"></span>
                </span>
                <span class="play-icon"></span>
            </button>
        </div>

    </div>
    <!-- herobannerarea__section__end-->
    <div class="populerarea sp_top_30 sp_bottom_50 bg-light">
        <div class="container">
            <div class="row" data-aos="fade-down" data-aos-duration="1000">
                <div class="col-12">
                    <div class="section-header mb-4">
                        <span class="sub-title">Links</span>
                        <h2 class="section-title"> Important <span class="highlight">Links</span></h2>
                    </div>
                </div>


            </div>

            <div class="row">
                <!-- Card 1 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <a href="https://el.sustech.edu" target="_blank" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-laptop"></i>
                        </div>
                        <h3>E-Learning System</h3>
                    </a>
                </div>

                <!-- Card 2 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <a href="http://repository.sustech.edu/" target="_blank" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-database"></i>
                        </div>
                        <h3>SUST Repository</h3>
                    </a>
                </div>

                <!-- Card 3 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="http://196.1.226.242/student/login" target="_blank" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-student-alt"></i>
                        </div>
                        <h3>Students Portal</h3>
                    </a>
                </div>

                <!-- Card 4 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <a href="#" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-building"></i>
                        </div>
                        <h3>SUST Campuses</h3>
                    </a>
                </div>

                <!-- Card 5 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <a href="https://mail01.sustech.edu/" target="_blank" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-envelope"></i>
                        </div>
                        <h3>Staff Webmail</h3>
                    </a>
                </div>

                <!-- Card 6 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ URL::to('/news')}}" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-newspaper"></i>
                        </div>
                        <h3>SUST News</h3>
                    </a>
                </div>

                <!-- Card 7 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="#" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-read-book"></i>
                        </div>
                        <h3>SUST Journals</h3>
                    </a>
                </div>

                <!-- Card 8 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <a href="http://196.1.226.111/sustech/r/sust_portal/sust-graduate-studies/open-progs" target="_blank"
                        class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-hat-alt"></i>
                        </div>
                        <h3>Programs Electronic Index</h3>
                    </a>
                </div>

            </div>
        </div>
    </div>
    <!-- aboutarea__2__section__start -->
    <div class="populerarea sp_top_80 sp_bottom_50">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12" data-aos="fade-right" data-aos-duration="1200">
                    <div class="about__right__wraper__2">
                        <div class="educationarea__img position-relative">
                            <img loading="lazy" src="{{ URL::to('images/gallery/vice-chancellor.jpg') }}"
                                alt="Vice Chancellor" class="img-fluid rounded shadow-lg"
                                style="border: 4px solid #fff; box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;">
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">

                    <div class="aboutarea__content__wraper">
                        <div class="section-header mb-4">
                            <span class="sub-title">welcome to sust</span>
                            <h2 class="section-title">Vice-Chancellor's <span class="highlight">Message</span></h2>
                        </div>
                        <div>
                            <p>I would like to welcome you to Sudan University of Science and Technology (SUST), the leading
                                university in applied and theoretical sciences, where its graduates form a cornerstone of
                                the labor market in its various institutions inside and outside Sudan.</p>
                            <p>The University is one of the oldest and leading institutions in Sudan. Since its
                                establishment, it has been offering some programs that are not found in other educational
                                institutions at the national and regional levels.</p>
                            <a class="default__button"
                                href="{{ route('home_dynamic_page', ['slug' => 'vice_chancellor_message']) }}">Explore More
                                <i class="icofont-long-arrow-right"></i>
                            </a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- aboutarea__2__section__end -->

    <!-- about__tap__section__start -->
    <div class="blogarea__2 sp_bottom_20 bg-light pt-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 text-center" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="section-header mb-4">
                        <span class="sub-title">Our Identity</span>
                        <h2 class="section-title">About <span class="highlight">University</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12" data-aos="fade-up">
                    <ul class="nav  about__button__wrap justify-content-center" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="single__tab__link active" data-bs-toggle="tab" data-bs-target="#projects__one"
                                type="button">About</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="single__tab__link" data-bs-toggle="tab" data-bs-target="#projects__two"
                                type="button">Our Mission</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="single__tab__link" data-bs-toggle="tab" data-bs-target="#projects__three"
                                type="button">Our Vision</button>
                        </li>

                    </ul>
                </div>


                <div class="tab-content tab__content__wrapper" id="myTabContent" data-aos="fade-up">

                    <div class="tab-pane fade active show" id="projects__one" role="tabpanel"
                        aria-labelledby="projects__one">
                        <div class="col-xl-12">
                            <div class="aboutarea__content__tap__wraper">
                                <p class="paragraph__1">The establishment of Sudan University goes back deep into the modern
                                    history of Sudan, during the early stages of education development in the country.
                                    Starting with Khartoum Technical School (KTI), and then the Trade School in 1902, the
                                    Radiology School in 1932, the Art School in 1946, Khartoum Technical Institute in 1950,
                                    Shambat Agricultural Institute in 1954, the Institute of Music and Theater and finally
                                    the High Institute for Physical Education for Teachers.</p>
                                <img loading="lazy" src="images/gallery/overview.jpg" alt="">
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="projects__two" role="tabpanel" aria-labelledby="projects__two">

                        <div class="col-xl-12">
                            <div class="aboutarea__content__tap__wraper">
                                <p class="paragraph__1">SUST provides educational programs in applied knowledge in the
                                    fields of Basic, Engineering & Medical sciences, and humanities & natural resources, and
                                    keeps pace with modern programs</p>
                                <p class="paragraph__1">SUST produces a great deal of original scientific research of
                                    practical nature that leads to sustained development and the ability to cope with new
                                    technology, thus leading to the emergence of prominent & distinguished scientists of
                                    high international caliber and reputation
                                    SUST accomplishes its share in the scientific, technological and industerial development
                                    and public services in SUDAN, thus serving the community.</p>
                                <img loading="lazy" src="images/gallery/mission.jpg" alt="Mission">
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="projects__three" role="tabpanel" aria-labelledby="projects__three">
                        <div class="col-xl-12">
                            <div class="aboutarea__content__tap__wraper">
                                <p class="paragraph__1">Sudan University of Science and Technology (SUST) is situated in
                                    Khartoum State, in the heart of Sudan. SUST will become a distinguished institution of
                                    applied sciences, a global center of excellence in scientific research, and committed to
                                    community services.</p>
                                <img loading="lazy" src="images/gallery/vision.jpg" alt="Mission">
                            </div>

                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>
    <!-- .about__tap__section__end -->

    <!-- counter__section__start -->
    <div class="counterarea sp_bottom_40 sp_top_40">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="flip-up" data-aos-duration="1000">
                    <div class="counterarea__text__wraper">
                        <div class="counter__img">
                            <i class="icofont-location-pin" style="font-size: 3em;color:#832611"></i>
                        </div>
                        <div class="counter__content__wraper">
                            <div class="counter__number">
                                <span class="counter">5</span>+

                            </div>
                            <p>campuses</p>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="flip-up" data-aos-duration="1000">
                    <div class="counterarea__text__wraper">
                        <div class="counter__img">
                            <i class="icofont-graduate-alt" style="font-size: 3em;color:#832611"></i>
                        </div>
                        <div class="counter__content__wraper">
                            <div class="counter__number">
                                <span class="counter">45</span>+

                            </div>
                            <p>TOTAL STUDENTS</p>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="flip-up" data-aos-duration="1000">
                    <div class="counterarea__text__wraper">
                        <div class="counter__img">
                            <i class="icofont-university" style="font-size: 3em;color:#832611"></i>
                        </div>
                        <div class="counter__content__wraper">
                            <div class="counter__number">
                                <span class="counter">27</span>

                            </div>
                            <p>Colleges</p>

                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="flip-up" data-aos-duration="1000">
                    <div class="counterarea__text__wraper">
                        <div class="counter__img">
                            <i class="icofont-university" style="font-size: 3em;color:#832611"></i>
                        </div>
                        <div class="counter__content__wraper">
                            <div class="counter__number">
                                <span class="counter">12</span>+

                            </div>
                            <p>Centers & institutes</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- counter__section__end-->

    <!-- news__section__start -->
    <div class="blogarea sp_bottom_40 sp_top_20">
        <div class="container">
            <div class="row" data-aos="fade-up">
                <div class="col-xl-12">
                    <div class="section__title text-center">
                        <div class="section-header mb-4">
                            <span class="sub-title">News</span>
                            <h2 class="section-title">Latest <span class="highlight">News</span></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8 col-lg-8" data-aos="fade-right" data-aos-duration="1200">
                    <div class="blogarea__content__wraper h-100 d-flex flex-column shadow-sm rounded-4 overflow-hidden bg-white" style="transition: 0.3s; margin-bottom: 0;">
                        @if (isset($data['news'][0]) && $data['news'][0]->photos->isNotEmpty())
                                            <div class="blogarea__img">
                                                <a href="{{ url('/news/details/' . $data['news'][0]->slug) }}">
                                                    <img loading="lazy" src="{{ URL::to($data['news'][0]->photos[0]->img) }}"
                                                        alt="{{ $data['news'][0]->title }}" style="width: 100%; height: 450px; object-fit: cover;">
                                                </a>
                                                <div class="blogarea__date text-white" style="color: #fff !important;">
                                                    {{ Carbon\Carbon::parse($data['news'][0]->news_date)->day}}
                                                    <span style="color: #fff !important;">{{ Carbon\Carbon::parse($data['news'][0]->news_date)->format('M Y')}}</span>
                                                </div>
                                            </div>
                                            <div class="blogarea__text__wraper flex-grow-1 d-flex flex-column justify-content-between p-4">
                                                <h3><a
                                                        href="{{ url('/news/details/' . $data['news'][0]->slug) }}">{{ $data['news'][0]->title }}</a>
                                                </h3>
                                                <div class="blogarea__para">
                                                    <p>{{ $data['news'][0]->detail_portion }}
                                                        <a href="{{ url('/news/details/' . $data['news'][0]->slug) }}">more ... <i
                                                                class="icofont-long-arrow-right"></i></a>
                                                    </p>
                                                </div>

                                                <div class="blogarea__icon">
                                                    <div class="blogarea__person">

                                                    </div>
                                                    <div class="blogarea__list">
                                                        <ul>
                                                            <li>
                                                                <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url('/news/details/' . $data['news'][0]->slug), 'quote' => $data['news'][0]->title]) }}"
                                                                    target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                                                    <i class="icofont-facebook"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
                                'url' => url('/news/details/' . $data['news'][0]->slug),
                                'text' => $data['news'][0]->title,
                                'hashtags' => 'SUST,SudanUniversity'
                            ]) }}" target="_blank" rel="noopener noreferrer">
                                                                    <i class="icofont-twitter"></i>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xl-4 col-lg-4 d-flex flex-column" style="gap: 24px;" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                    <div class="blogarea__content__wraper m-0 h-100 d-flex flex-column shadow-sm rounded-4 overflow-hidden bg-white" style="transition: 0.3s;">
                        @if (isset($data['news'][1]) && $data['news'][1]->photos->isNotEmpty())
                            <div class="blogarea__img">
                                <a href="{{ url('/news/details/' . $data['news'][1]->slug) }}">
                                    <img loading="lazy" src="{{ URL::to($data['news'][1]->photos[0]->img) }}"
                                        alt="{{ $data['news'][1]->title }}" style="width: 100%; height: 210px; object-fit: cover;">
                                </a>
                                <div class="blogarea__date small__date text-white" style="color: #fff !important;">
                                    {{ Carbon\Carbon::parse($data['news'][1]->news_date)->day}}
                                    <span style="color: #fff !important;">{{ Carbon\Carbon::parse($data['news'][1]->news_date)->format('M Y')}}</span>
                                </div>
                            </div>
                            <div class="blogarea__text__wraper blogarea__text__wraper__2">
                                <h3><a
                                        href="{{ url('/news/details/' . $data['news'][1]->slug) }}">{{ Str::limit($data['news'][1]->title, 62) }}</a>
                                </h3>
                            </div>
                        @endif

                    </div>

                    <div class="blogarea__content__wraper m-0 h-100 d-flex flex-column shadow-sm rounded-4 overflow-hidden bg-white" style="transition: 0.3s;">
                        @if (isset($data['news'][2]) && $data['news'][2]->photos->isNotEmpty())
                            <div class="blogarea__img">
                                <a href="{{ url('/news/details/' . $data['news'][2]->slug) }}">
                                    <img loading="lazy" src="{{ URL::to($data['news'][2]->photos[0]->img) }}"
                                        alt="{{ $data['news'][2]->title }}" style="width: 100%; height: 210px; object-fit: cover;">
                                </a>
                                <div class="blogarea__date small__date text-white" style="color: #fff !important;">
                                    {{ Carbon\Carbon::parse($data['news'][2]->news_date)->day}}
                                    <span style="color: #fff !important;">{{ Carbon\Carbon::parse($data['news'][2]->news_date)->format('M Y')}}</span>
                                </div>
                            </div>
                            <div class="blogarea__text__wraper blogarea__text__wraper__2">
                                <h3><a
                                        href="{{ url('/news/details/' . $data['news'][2]->slug) }}">{{ Str::limit($data['news'][2]->title, 62) }}</a>
                                </h3>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="col-xl-12" data-aos="fade-up">
                    <div class="blogarea__bottom__button">
                        <a class="default__button w-100" href="{{ URL::to('/news') }}">More News</a>
                    </div>
                </div>

                @if(isset($data['ads']) && $data['ads']->isNotEmpty())
                <hr class="col-xl-12 my-3" style="padding: 4px 0">
                @endif
            </div>
        </div>

    </div>
    <!-- news__section__end -->

    <!-- announcements_and_events_start -->
    @if(isset($data['ads']) && $data['ads']->isNotEmpty())
    <div class="blogarea__2 sp_top_50 sp_bottom_20">
        <div class="container">
            <div class="row">
                <div class="col-xl-12" data-aos="fade-up">
                    <div class="section__title text-center">
                        <div class="section-header mb-4">
                            <span class="sub-title">Announcements and Events</span>
                            <h2 class="section-title">Announcements and <span class="highlight">Events</span></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($data['ads'] as $ad)

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12" data-aos="fade-up">
                        <div class="single__blog__wraper">
                            @php
                                $adHomeImg = $ad->photos->first()?->img ?? $ad->photos->first()?->thumb_img;
                                if (!$adHomeImg && !empty($ad->file)) {
                                    $ext = strtolower(pathinfo($ad->file, PATHINFO_EXTENSION));
                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                        $adHomeImg = $ad->file;
                                    }
                                }
                            @endphp
                            <div class="single__blog__img">
                                <a href="{{ url('/ads/details/' . $ad->slug) }}">
                                    <img loading="lazy" src="{{ $adHomeImg ? URL::to($adHomeImg) : URL::to('images/logos/1840372294400317.png') }}" alt="{{ $ad->title }}">
                                </a>
                            </div>
                            <div class="single__blog__content">
                                <p>{{ Carbon\Carbon::parse($ad->ad_date)->format('j F Y') }}</p>
                                <h6> <a href="{{ url('/ads/details/' . $ad->slug) }}"
                                        alt="{{ $ad->title }}">{{ $ad->title }}</a>
                                </h6>
                                <div class="single__blog__bottom__button">
                                    <a href="{{ url('/ads/details/' . $ad->slug) }}">Read More
                                        <i class="icofont-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-xl-12" data-aos="fade-up">
                    <div class="blogarea__bottom__button">
                        <a class="default__button w-100" href="{{ route('ads_archive') }}">More Announcements and
                            Events</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- announcements_and_events_end -->
@endsection