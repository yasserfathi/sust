@extends('ar/layout')

@php $active_page = 'home'; @endphp

@section('title', 'الصفحة الرئيسية')

@section('content')
    <!-- herobannerarea__section__start -->
    <div class="herobannerarea herobannerarea__2 herobannerarea__university">
        <div class="swiper university__slider">

            <div class="herobannerarea__slider__wrap swiper-wrapper">

                @foreach ($data['gallery'] as $gallery)
                    <div class="swiper-slide herobannerarea__single__slider"
                        style="background-image: url('{{ versioned_asset($gallery->img) }}');">

                        <div class="container h-100">
                            <div class="row justify-content-center h-100 align-items-center">
                                <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 col-12" data-aos="zoom-in" data-aos-duration="1500">

                                    <div class="herobannerarea__content__wraper text-center premium-slider-content">
                                        <div class="herobannerarea__title">
                                            <div class="herobannerarea__small__title responsive-gallery-subtitle">
                                                <span>جامعة السودان للعلوم والتكنولوجيا</span>
                                            </div>

                                            <div class="herobannerarea__title__heading__2 herobannerarea__title__heading__3">
                                                <h2 class="responsive-gallery-title">{{ $gallery->title }}</h2>
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
            <button type="button" class="gallery-pause-btn" aria-label="Pause autoplay" title="إيقاف / تشغيل">
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
                        <span class="sub-title">روابط</span>
                        <h2 class="section-title">روابط <span class="highlight">مهمة</span> </h2>
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
                        <h3>نظام التعلم الإلكتروني</h3>
                    </a>
                </div>

                <!-- Card 2 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <a href="http://repository.sustech.edu/" target="_blank" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-database"></i>
                        </div>
                        <h3>المستودعات الرقمية</h3>
                    </a>
                </div>

                <!-- Card 3 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="http://196.1.226.242/student/login" target="_blank" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-student-alt"></i>
                        </div>
                        <h3>بوابة الطلاب</h3>
                    </a>
                </div>

                <!-- Card 4 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <a href="#" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-building"></i>
                        </div>
                        <h3>فروع الجامعة</h3>
                    </a>
                </div>

                <!-- Card 5 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <a href="https://mail01.sustech.edu/" target="_blank" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-envelope"></i>
                        </div>
                        <h3>البريد الالكتروني</h3>
                    </a>
                </div>

                <!-- Card 6 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <a href="{{ URL::to('/ar/news')}}" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-newspaper"></i>
                        </div>
                        <h3>الاخبار و الاحداث</h3>
                    </a>
                </div>

                <!-- Card 7 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="#" class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-read-book"></i>
                        </div>
                        <h3>المجلات العلمية</h3>
                    </a>
                </div>

                <!-- Card 8 -->
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <a href="http://196.1.226.111/sustech/r/sust_portal/sust-graduate-studies/open-progs" target="_blank"
                        class="premium-link-card h-100">
                        <div class="premium-icon-wrapper">
                            <i class="icofont-hat-alt"></i>
                        </div>
                        <h3>برامج كلية الدراسات العليا</h3>
                    </a>
                </div>

            </div>
        </div>
    </div>
    <div class="blogarea__2 sp_bottom_20 bg-gradient-primary pt-3">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12" data-aos="fade-left" data-aos-duration="1200">
                    <div class="about__right__wraper__2">
                        <div class="educationarea__img position-relative">
                            <img loading="lazy" src="{{ URL::to('images/gallery/vice-chancellor.jpg') }}"
                                alt="Vice Chancellor" class="img-fluid rounded shadow-lg"
                                style="border: 4px solid #fff; box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;">
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-6" data-aos="fade-right" data-aos-duration="1200" data-aos-delay="200">
                    <div class="aboutarea__content__wraper ps-lg-5">
                        <div class="section-header mb-4">
                            <span class="sub-title">رسالة الترحيب</span>
                            <h2 class="section-title">كلمة <span class="highlight">مدير الجامعة</span></h2>
                        </div>
                        <div class="about-text">
                            <p class="lead-text mb-4">
                                "أود أن أرحب بكم في جامعة السودان للعلوم والتكنولوجيا (SUST)، الجامعة الرائدة في العلوم
                                التطبيقية والنظرية."
                            </p>
                            <p class="mb-4 text-secondary lh-lg">
                                حيث يشكل خريجوها حجر الزاوية في سوق العمل في مؤسساتها المختلفة داخل السودان وخارجه. تعد
                                الجامعة من أقدم المؤسسات الرائدة في السودان، ومنذ إنشائها، تقدم كثير من البرامج التي لا توجد
                                في المؤسسات التعليمية الأخرى.
                            </p>
                            <div class="btn-wrap mt-5">
                                <a class="default__button"
                                    href="{{ route('home_dynamic_page_ar', ['slug' => 'vice_chancellor_message']) }}">
                                    قراءة الكلمة كاملة <i class="icofont-long-arrow-left ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="blogarea__2 sp_bottom_20 bg-light pt-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 text-center" data-aos="zoom-in" data-aos-duration="1000">
                    <div class="section-header mb-1">
                        <span class="sub-title">هويتنا</span>
                        <h2 class="section-title">عن <span class="highlight">الجامعة</span></h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12" data-aos="fade-up">
                    <ul class="nav  about__button__wrap justify-content-center" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="single__tab__link active" data-bs-toggle="tab" data-bs-target="#projects__one"
                                type="button">عن الجامعة</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="single__tab__link" data-bs-toggle="tab" data-bs-target="#projects__two"
                                type="button">رسالتنا</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="single__tab__link" data-bs-toggle="tab" data-bs-target="#projects__three"
                                type="button">رؤيتنا</button>
                        </li>

                    </ul>
                </div>


                <div class="tab-content tab__content__wrapper" id="myTabContent" data-aos="fade-up">

                    <div class="tab-pane fade active show" id="projects__one" role="tabpanel"
                        aria-labelledby="projects__one">
                        <div class="col-xl-12">
                            <div class="aboutarea__content__tap__wraper">
                                <p class="paragraph__1">يعود تأسيس جامعة السودان إلى جذور عميقة في تاريخ السودان الحديث،
                                    خلال المراحل الأولى لتطور التعليم في البلاد. بدأ الأمر بمدرسة الخرطوم الفنية، ثم المدرسة
                                    التجارية عام 1902، ومدرسة الأشعة عام 1932، ومدرسة الفنون عام 1946، ومعهد الخرطوم التقني
                                    عام 1950، ومعهد شمبات الزراعي عام 1954، ومعهد الموسيقى والمسرح، وأخيراً المعهد العالي
                                    للتربية البدنية للمعلمين.</p>
                                <img loading="lazy" src="{{ URL::to('images/gallery/overview.jpg') }}" alt="">
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="projects__two" role="tabpanel" aria-labelledby="projects__two">

                        <div class="col-xl-12">
                            <div class="aboutarea__content__tap__wraper">
                                <p class="paragraph__1">تقدم جامعة السودان للعلوم والتكنولوجيا برامج تعليمية في المعرفة
                                    التطبيقية في مجالات العلوم الأساسية والهندسية والطبية، والعلوم الإنسانية والموارد
                                    الطبيعية، وتواكب البرامج الحديثة.</p>
                                <p class="paragraph__1">تُنتج جامعة السودان للعلوم والتكنولوجيا (SUST) قدراً كبيراً من
                                    البحوث العلمية الأصلية ذات الطابع العملي التي تؤدي إلى التنمية المستدامة والقدرة على
                                    مواكبة التكنولوجيا الجديدة، مما يؤدي إلى ظهور علماء بارزين ومتميزين ذوي مستوى وسمعة
                                    دولية عالية. وتساهم جامعة السودان للعلوم والتكنولوجيا (SUST) بدورها في التنمية العلمية
                                    والتكنولوجية والصناعية والخدمات العامة في السودان، وبالتالي تخدم المجتمع.</p>
                                <img loading="lazy" src="{{ URL::to('images/gallery/mission.jpg') }}" alt="Mission">
                            </div>

                        </div>

                    </div>

                    <div class="tab-pane fade" id="projects__three" role="tabpanel" aria-labelledby="projects__three">
                        <div class="col-xl-12">
                            <div class="aboutarea__content__tap__wraper">
                                <p class="paragraph__1">تقع جامعة السودان للعلوم والتكنولوجيا (SUST) في ولاية الخرطوم، في
                                    قلب السودان. وستصبح الجامعة مؤسسة متميزة في العلوم التطبيقية، ومركزاً عالمياً للتميز في
                                    البحث العلمي، وملتزمة بخدمة المجتمع.</p>
                                <img loading="lazy" src="{{ URL::to('images/gallery/vision.jpg') }}" alt="Mission">
                            </div>

                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>
    <!-- counter__section__start -->
    <div class="counterarea sp_bottom_40 sp_top_40">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="flip-up" data-aos-duration="1000">
                    <div class="counterarea__text__wraper">
                        <div class="counter__img">
                            <i class="icofont-location-pin" style="font-size: 3em"></i>
                        </div>
                        <div class="counter__content__wraper">
                            <div class="counter__number">
                                <span class="counter">5</span>+
                            </div>
                            <p>المجمعات</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="flip-up" data-aos-duration="1000">
                    <div class="counterarea__text__wraper">
                        <div class="counter__img">
                            <i class="icofont-graduate-alt" style="font-size: 3em"></i>
                        </div>
                        <div class="counter__content__wraper">
                            <div class="counter__number">
                                <span class="counter">45</span>+
                            </div>
                            <p>عدد الطلاب</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="flip-up" data-aos-duration="1000">
                    <div class="counterarea__text__wraper">
                        <div class="counter__img">
                            <i class="icofont-university" style="font-size: 3em"></i>
                        </div>
                        <div class="counter__content__wraper">
                            <div class="counter__number">
                                <span class="counter">27</span>
                            </div>
                            <p>عدد الكليات</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="flip-up" data-aos-duration="1000">
                    <div class="counterarea__text__wraper">
                        <div class="counter__img">
                            <i class="icofont-university" style="font-size: 3em"></i>
                        </div>
                        <div class="counter__content__wraper">
                            <div class="counter__number">
                                <span class="counter">12</span>+
                            </div>
                            <p>المراكز و المعاهد</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- counter__section__end -->
    <div class="blogarea sp_bottom_40 sp_top_20">
        <div class="container">
            <div class="row">
                <div class="row justify-content-center">
                    <div class="col-xl-8 text-center" data-aos="fade-up">
                        <div class="section-header mb-1">
                            <span class="sub-title">المركز الاعلامي</span>
                            <h2 class="section-title">أحدث <span class="highlight">الأخبار</span></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-8 col-lg-8" data-aos="fade-left" data-aos-duration="1200">
                    <div class="blogarea__content__wraper h-100 d-flex flex-column shadow-sm rounded-4 overflow-hidden bg-white" style="transition: 0.3s; margin-bottom: 0;">
                        @php
                            \Carbon\Carbon::setLocale('ar');
                        @endphp

                        @if (isset($data['news'][0]) && $data['news'][0]->photos->isNotEmpty())
                                            <div class="blogarea__img">
                                                <a href="{{ url('/ar/news/details/' . $data['news'][0]->slug) }}">
                                                    <img loading="lazy" src="{{ URL::to($data['news'][0]->photos[0]->img) }}"
                                                        alt="{{ $data['news'][0]->title }}" style="width: 100%; height: 450px; object-fit: cover;">
                                                </a>
                                                <div class="blogarea__date text-white" style="color: #fff !important;">
                                                    {{ Carbon\Carbon::parse($data['news'][0]->news_date)->day}}
                                                    <span style="color: #fff !important;">{{ Carbon\Carbon::parse($data['news'][0]->news_date)->translatedFormat('M Y')}}</span>
                                                </div>
                                            </div>
                                            <div class="blogarea__text__wraper flex-grow-1 d-flex flex-column justify-content-between p-4">
                                                <h3><a
                                                        href="{{ url('/ar/news/details/' . $data['news'][0]->slug) }}">{{ $data['news'][0]->title }}</a>
                                                </h3>
                                                <div class="blogarea__para">
                                                    <p>{{ $data['news'][0]->detail_portion }}
                                                        <a href="{{ url('/ar/news/details/' . $data['news'][0]->slug) }}">... المزيد <i
                                                                class="icofont-long-arrow-left"></i></a>
                                                    </p>
                                                </div>

                                                <div class="blogarea__icon">
                                                    <div class="blogarea__person">

                                                    </div>
                                                    <div class="blogarea__list">
                                                        <ul>
                                                            <li>
                                                                <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url('/ar/news/details/' . $data['news'][0]->slug), 'quote' => $data['news'][0]->title]) }}"
                                                                    target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                                                    <i class="icofont-facebook"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
                                 'url' => url('/ar/news/details/' . $data['news'][0]->slug),
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
                <div class="col-xl-4 col-lg-4 d-flex flex-column" style="gap: 24px;" data-aos="fade-right" data-aos-duration="1200" data-aos-delay="200">

                    <div class="blogarea__content__wraper m-0 h-100 d-flex flex-column shadow-sm rounded-4 overflow-hidden bg-white" style="transition: 0.3s;">
                        @if (isset($data['news'][1]) && $data['news'][1]->photos->isNotEmpty())
                            <div class="blogarea__img">
                                <a href="{{ url('/ar/news/details/' . $data['news'][1]->slug) }}">
                                    <img loading="lazy" src="{{ URL::to($data['news'][1]->photos[0]->img) }}"
                                        alt="{{ $data['news'][1]->title }}" style="width: 100%; height: 210px; object-fit: cover;">
                                </a>
                                <div class="blogarea__date small__date text-white" style="color: #fff !important;">
                                    {{ Carbon\Carbon::parse($data['news'][1]->news_date)->day}}
                                    <span style="color: #fff !important;">{{ Carbon\Carbon::parse($data['news'][1]->news_date)->translatedFormat('M Y')}}</span>
                                </div>
                            </div>
                            <div class="blogarea__text__wraper blogarea__text__wraper__2">
                                <h3><a
                                        href="{{ url('/ar/news/details/' . $data['news'][1]->slug) }}">{{ Str::limit($data['news'][1]->title, 62) }}</a>
                                </h3>
                            </div>
                        @endif
                    </div>

                    <div class="blogarea__content__wraper m-0 h-100 d-flex flex-column shadow-sm rounded-4 overflow-hidden bg-white" style="transition: 0.3s;">
                        @if (isset($data['news'][2]) && $data['news'][2]->photos->isNotEmpty())
                            <div class="blogarea__img">
                                <a href="{{ url('/ar/news/details/' . $data['news'][2]->slug) }}">
                                    <img loading="lazy" src="{{ URL::to($data['news'][2]->photos[0]->img) }}"
                                        alt="{{ $data['news'][2]->title }}" style="width: 100%; height: 210px; object-fit: cover;">
                                </a>
                                <div class="blogarea__date small__date text-white" style="color: #fff !important;">
                                    {{ Carbon\Carbon::parse($data['news'][2]->news_date)->day}}
                                    <span style="color: #fff !important;">{{ Carbon\Carbon::parse($data['news'][2]->news_date)->translatedFormat('M Y')}}</span>
                                </div>
                            </div>
                            <div class="blogarea__text__wraper blogarea__text__wraper__2">
                                <h3><a
                                        href="{{ url('/ar/news/details/' . $data['news'][2]->slug) }}">{{ Str::limit($data['news'][2]->title, 62) }}</a>
                                </h3>
                            </div>
                        @endif
                    </div>

                </div>
                <div class="col-xl-12" data-aos="fade-up">
                    <div class="blogarea__bottom__button sp_20">
                        <a class="default__button w-100" href="{{ url('/ar/news') }}">المزيد من الأخبار</a>
                    </div>
                </div>
                @if(isset($data['ads']) && $data['ads']->isNotEmpty())
                <hr class="col-xl-12 my-3" style="padding: 4px 0">
                @endif
            </div>
        </div>
    </div>
    @if(isset($data['ads']) && $data['ads']->isNotEmpty())
    <div class="blogarea__2 sp_top_20 bg-light pt-3">
        <div class="container">
            <div class="row">
                <div class="col-xl-12" data-aos="fade-up">
                    <div class="section-header mb-4 text-center">
                        <span class="sub-title">الفعاليات</span>
                        <h2 class="section-title">الإعلانات <span class="highlight">والأحداث</span></h2>
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
                                <a href="{{ url('/ar/ads/details/' . $ad->slug) }}">
                                    <img loading="lazy" src="{{ $adHomeImg ? URL::to($adHomeImg) : URL::to('images/logos/1840372294400317.png') }}" alt="{{ $ad->title }}">
                                </a>
                            </div>
                            <div class="single__blog__content">
                                <p>{{ Carbon\Carbon::parse($ad->ad_date)->translatedFormat('j F Y') }}</p>
                                <h6> <a href="{{ url('/ar/ads/details/' . $ad->slug) }}"
                                        alt="{{ $ad->title }}">{{ $ad->title }}</a></h6>
                                <div class="single__blog__bottom__button">
                                    <a href="{{ url('/ar/ads/details/' . $ad->slug) }}">... المزيد
                                        <i class="icofont-long-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-xl-12 sp_20" data-aos="fade-up">
                    <div class="blogarea__bottom__button">
                        <a class="default__button w-100" href="{{ url('/ar/ads') }}">المزيد من الإعلانات والأحداث</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
@endsection