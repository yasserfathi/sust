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
                        style="background-image: url('{{ asset($gallery->img) }}');">

                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 col-12" data-aos="fade-up">

                                    <div class="herobannerarea__content__wraper text-center">

                                        <div class="herobannerarea__title">
                                            <div class="herobannerarea__small__title">
                                                <span>جامعة السودان للعلوم والتكنولوجيا</span>
                                            </div>

                                            <div class="herobannerarea__title__heading__2 herobannerarea__title__heading__3">
                                                <h2>{{ $gallery->title }}</h2>
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


        {{-- Thumbs Slider --}}
        <div thumbsSlider class="swiper university__slider__thumb">
            <div class="swiper-wrapper">

                @foreach ($data['gallery'] as $gallery)
                    <div class="swiper-slide">
                        <img loading="lazy" src="{{ asset($gallery->thumb_img) }}" alt="{{ $gallery->title }}">
                    </div>
                @endforeach

            </div>
        </div>


        {{-- Controls --}}
        <div class="slider__controls__wrap slider__controls__pagination slider__controls__arrows">
            <div class="swiper-button-next arrow-btn"></div>
            <div class="swiper-button-prev arrow-btn"></div>
            <div class="swiper-pagination"></div>
        </div>

    </div>
    <!-- herobannerarea__section__end-->
    <div class="populerarea sp_top_80 sp_bottom_50 bg-light">
        <div class="container">
            <div class="row aos-init aos-animate" data-aos="fade-up">
                <div class="col-12">
                    <div class="section-header mb-4">
                        <span class="sub-title">روابط</span>
                        <h2 class="section-title"> <span class="highlight">روابط</span> مهمة</h2>
                    </div>
                </div>



            </div>
            <div class="row">

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 aos-init aos-animate" data-aos="fade-up">
                    <div class="single__service">
                        <div class="service__img">

                            <i class="icofont-laptop service__icon"></i>



                            <div class="service__bg__img">
                                <svg class="service__icon__bg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M63.3775 44.4535C54.8582 58.717 39.1005 53.2202 23.1736 47.5697C7.2467 41.9192 -5.18037 32.7111 3.33895 18.4477C11.8583 4.18418 31.6595 -2.79441 47.5803 2.85105C63.5011 8.49652 71.8609 30.2313 63.3488 44.4865L63.3775 44.4535Z"
                                        fill="#ce6148" fill-opacity="0.05"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="service__content">
                            <h3><a href="https://el.sustech.edu" target="_blank">نظام التعلم الإلكتروني</a>
                            </h3>
                        </div>
                        <div class="service__small__img">
                            <svg class="icon__hover__img" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5961 10.265L19 1.33069L10.0022 3.73285L1 6.1306L7.59393 12.6627L14.1879 19.1992L16.5961 10.265Z"
                                    stroke="#FFB31F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 aos-init aos-animate" data-aos="fade-up">
                    <div class="single__service">
                        <div class="service__img">
                            <i class="icofont-database service__icon"></i>



                            <div class="service__bg__img">
                                <svg class="service__icon__bg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M63.3775 44.4535C54.8582 58.717 39.1005 53.2202 23.1736 47.5697C7.2467 41.9192 -5.18037 32.7111 3.33895 18.4477C11.8583 4.18418 31.6595 -2.79441 47.5803 2.85105C63.5011 8.49652 71.8609 30.2313 63.3488 44.4865L63.3775 44.4535Z"
                                        fill="#ce6148" fill-opacity="0.05"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="service__content">
                            <h3><a href="http://repository.sustech.edu/" target="_blank">المستودعات الرقمية</a></h3>
                        </div>
                        <div class="service__small__img">
                            <svg class="icon__hover__img" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5961 10.265L19 1.33069L10.0022 3.73285L1 6.1306L7.59393 12.6627L14.1879 19.1992L16.5961 10.265Z"
                                    stroke="#FFB31F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 aos-init aos-animate" data-aos="fade-up">
                    <div class="single__service">
                        <div class="service__img">
                            <i class="icofont-student-alt service__icon"></i>



                            <div class="service__bg__img">
                                <svg class="service__icon__bg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M63.3775 44.4535C54.8582 58.717 39.1005 53.2202 23.1736 47.5697C7.2467 41.9192 -5.18037 32.7111 3.33895 18.4477C11.8583 4.18418 31.6595 -2.79441 47.5803 2.85105C63.5011 8.49652 71.8609 30.2313 63.3488 44.4865L63.3775 44.4535Z"
                                        fill="#ce6148" fill-opacity="0.05"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="service__content">
                            <h3><a href="http://196.1.226.242/student/login" target="_blank">بوابة الطالب</a></h3>
                        </div>
                        <div class="service__small__img">
                            <svg class="icon__hover__img" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5961 10.265L19 1.33069L10.0022 3.73285L1 6.1306L7.59393 12.6627L14.1879 19.1992L16.5961 10.265Z"
                                    stroke="#FFB31F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 aos-init aos-animate" data-aos="fade-up">
                    <div class="single__service">
                        <div class="service__img">
                            <i class="icofont-envelope service__icon"></i>



                            <div class="service__bg__img">
                                <svg class="service__icon__bg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M63.3775 44.4535C54.8582 58.717 39.1005 53.2202 23.1736 47.5697C7.2467 41.9192 -5.18037 32.7111 3.33895 18.4477C11.8583 4.18418 31.6595 -2.79441 47.5803 2.85105C63.5011 8.49652 71.8609 30.2313 63.3488 44.4865L63.3775 44.4535Z"
                                        fill="#ce6148" fill-opacity="0.05"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="service__content">
                            <h3><a href="#">مجمعات الجامعة</a></h3>
                        </div>
                        <div class="service__small__img">
                            <svg class="icon__hover__img" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5961 10.265L19 1.33069L10.0022 3.73285L1 6.1306L7.59393 12.6627L14.1879 19.1992L16.5961 10.265Z"
                                    stroke="#FFB31F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 aos-init aos-animate" data-aos="fade-up">
                    <div class="single__service">
                        <div class="service__img">
                            <i class="icofont-building service__icon"></i>



                            <div class="service__bg__img">
                                <svg class="service__icon__bg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M63.3775 44.4535C54.8582 58.717 39.1005 53.2202 23.1736 47.5697C7.2467 41.9192 -5.18037 32.7111 3.33895 18.4477C11.8583 4.18418 31.6595 -2.79441 47.5803 2.85105C63.5011 8.49652 71.8609 30.2313 63.3488 44.4865L63.3775 44.4535Z"
                                        fill="#ce6148" fill-opacity="0.05"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="service__content">
                            <h3><a href="https://mail01.sustech.edu/" target="_blank">البريد الالكتروني</a></h3>
                        </div>
                        <div class="service__small__img">
                            <svg class="icon__hover__img" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5961 10.265L19 1.33069L10.0022 3.73285L1 6.1306L7.59393 12.6627L14.1879 19.1992L16.5961 10.265Z"
                                    stroke="#FFB31F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 aos-init aos-animate" data-aos="fade-up">
                    <div class="single__service">
                        <div class="service__img">
                            <i class="icofont-newspaper service__icon"></i>



                            <div class="service__bg__img">
                                <svg class="service__icon__bg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M63.3775 44.4535C54.8582 58.717 39.1005 53.2202 23.1736 47.5697C7.2467 41.9192 -5.18037 32.7111 3.33895 18.4477C11.8583 4.18418 31.6595 -2.79441 47.5803 2.85105C63.5011 8.49652 71.8609 30.2313 63.3488 44.4865L63.3775 44.4535Z"
                                        fill="#ce6148" fill-opacity="0.05"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="service__content">
                            <h3><a href="{{ URL::to('/ar/news')}}">الاخبار و الاحداث</a></h3>
                        </div>
                        <div class="service__small__img">
                            <svg class="icon__hover__img" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5961 10.265L19 1.33069L10.0022 3.73285L1 6.1306L7.59393 12.6627L14.1879 19.1992L16.5961 10.265Z"
                                    stroke="#FFB31F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 aos-init aos-animate" data-aos="fade-up">
                    <div class="single__service">
                        <div class="service__img">
                            <i class="icofont-read-book service__icon"></i>



                            <div class="service__bg__img">
                                <svg class="service__icon__bg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M63.3775 44.4535C54.8582 58.717 39.1005 53.2202 23.1736 47.5697C7.2467 41.9192 -5.18037 32.7111 3.33895 18.4477C11.8583 4.18418 31.6595 -2.79441 47.5803 2.85105C63.5011 8.49652 71.8609 30.2313 63.3488 44.4865L63.3775 44.4535Z"
                                        fill="#ce6148" fill-opacity="0.05"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="service__content">
                            <h3><a href="#">المجلات العلمية</a></h3>
                        </div>
                        <div class="service__small__img">
                            <svg class="icon__hover__img" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5961 10.265L19 1.33069L10.0022 3.73285L1 6.1306L7.59393 12.6627L14.1879 19.1992L16.5961 10.265Z"
                                    stroke="#FFB31F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 aos-init aos-animate" data-aos="fade-up">
                    <div class="single__service">
                        <div class="service__img">

                            <i class="icofont-briefcase service__icon"></i>



                            <div class="service__bg__img">
                                <svg class="service__icon__bg" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M63.3775 44.4535C54.8582 58.717 39.1005 53.2202 23.1736 47.5697C7.2467 41.9192 -5.18037 32.7111 3.33895 18.4477C11.8583 4.18418 31.6595 -2.79441 47.5803 2.85105C63.5011 8.49652 71.8609 30.2313 63.3488 44.4865L63.3775 44.4535Z"
                                        fill="#ce6148" fill-opacity="0.05"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="service__content">
                            <h3><a href="{{ URL::to('/ar/administration')}}">ادارة الجامعة</a></h3>
                        </div>
                        <div class="service__small__img">
                            <svg class="icon__hover__img" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.5961 10.265L19 1.33069L10.0022 3.73285L1 6.1306L7.59393 12.6627L14.1879 19.1992L16.5961 10.265Z"
                                    stroke="#FFB31F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
    <div class="blogarea__2 sp_bottom_20 bg-gradient-primary pt-3">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12">
                    <div class="about__right__wraper__2">
                        <div class="educationarea__img">
                            <img loading="lazy" src="{{ URL::to('images/gallery/vice-chancellor.jpg') }}"
                                alt="Vice Chancellor">
                        </div>
                    </div>
                </div>
                <div class="col-xl-7 col-lg-6" data-aos="fade-up">
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
                <div class="col-xl-8 text-center" data-aos="fade-up">
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
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="fade-up">
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
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="fade-up">
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
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="fade-up">
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
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="fade-up">
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
            <div class="row" data-aos="fade-up">
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
                <div class="col-xl-8 col-lg-8" data-aos="fade-up">
                    <div class="blogarea__content__wraper">
                        @php
                            \Carbon\Carbon::setLocale('ar');
                        @endphp

                        @if (isset($data['news'][0]) && $data['news'][0]->photos->isNotEmpty())
                                            <div class="blogarea__img">
                                                <img loading="lazy" src="{{ URL::to($data['news'][0]->photos[0]->img) }}"
                                                    alt="{{ $data['news'][0]->title }}">
                                                <div class="blogarea__date">
                                                    {{ Carbon\Carbon::parse($data['news'][0]->news_date)->day}}
                                                    <span>{{ Carbon\Carbon::parse($data['news'][0]->news_date)->translatedFormat('M')}}</span>
                                                </div>
                                            </div>
                                            <div class="blogarea__text__wraper">
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
                                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/ar/news/details/' . $data['news'][0]->slug) }}&quote={{ urlencode($data['news'][0]->title) }}"
                                                                    target="_blank" class="facebook-share-button">
                                                                    <i class="icofont-facebook"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
                                'url' => url('/ar/news/details/' . $data['news'][0]->slug),
                                'text' => $data['news'][0]->title,
                                'hashtags' => 'Sudan University of Science and Technology'
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
                <div class="col-xl-4 col-lg-4" data-aos="fade-up">

                    <div class="blogarea__content__wraper">
                        @if (isset($data['news'][1]) && $data['news'][1]->photos->isNotEmpty())
                            <div class="blogarea__img">
                                <img loading="lazy" src="{{ URL::to($data['news'][1]->photos[0]->img) }}"
                                    alt="{{ $data['news'][1]->title }}">
                                <div class="blogarea__date small__date">
                                    {{ Carbon\Carbon::parse($data['news'][1]->news_date)->day}}
                                    <span>{{ Carbon\Carbon::parse($data['news'][1]->news_date)->translatedFormat('M')}}</span>
                                </div>
                            </div>
                            <div class="blogarea__text__wraper blogarea__text__wraper__2">
                                <h3><a
                                        href="{{ url('/ar/news/details/' . $data['news'][1]->slug) }}">{{ Str::limit($data['news'][1]->title, 62) }}</a>
                                </h3>
                            </div>
                        @endif
                    </div>

                    <div class="blogarea__content__wraper">
                        @if (isset($data['news'][2]) && $data['news'][2]->photos->isNotEmpty())
                            <div class="blogarea__img">
                                <img loading="lazy" src="{{ URL::to($data['news'][2]->photos[0]->img) }}"
                                    alt="{{ $data['news'][2]->title }}">
                                <div class="blogarea__date small__date">
                                    {{ Carbon\Carbon::parse($data['news'][2]->news_date)->day}}
                                    <span>{{ Carbon\Carbon::parse($data['news'][2]->news_date)->translatedFormat('M')}}</span>
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
                <hr class="col-xl-12 my-3" style="padding: 4px 0">
            </div>
        </div>
    </div>
    <div class="blogarea__2 sp_top_20 bg-light pt-3">
        <div class="container">
            <div class="row">
                <div class="col-xl-12" data-aos="fade-up">
                    <div class="section__title text-center">
                        <div class="section__title__button">
                            <div class="default__small__button">الفعاليات</div>
                        </div>
                        <div class="section__title__heading heading__underline">
                            <h2>الإعلانات والأحداث</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($data['ads'] as $ad)

                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12" data-aos="fade-up">
                        <div class="single__blog__wraper">
                            @if ($ad->photos->isNotEmpty())
                                <div class="single__blog__img">
                                    <img loading="lazy" src="{{ URL::to($ad->photos[0]->img) }}" alt="{{ $ad->title }}">
                                </div>
                            @endif
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
@endsection