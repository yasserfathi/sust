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
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 col-12" data-aos="fade-up">
                                    <div class="herobannerarea__content__wraper text-center">


                                        <div class="herobannerarea__title">
                                            <div class="herobannerarea__small__title">
                                                <span>Sudan University of Science & Technology</span>
                                            </div>
                                            <div class="herobannerarea__title__heading__2 herobannerarea__title__heading__3">
                                                <h2>{{$gallery->title_en}}</h2>
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


        <div thumbsSlider="" class="swiper university__slider__thumb">
            <div class="swiper-wrapper">
                @foreach ($data['gallery'] as $gallery)
                    <div class="swiper-slide">
                        <img loading="lazy" src="{{ URL::to($gallery->thumb_img) }}" />
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
                        <span class="sub-title">Links</span>
                        <h2 class="section-title"> <span class="highlight">Important</span> Links</h2>
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
                            <h3><a href="https://el.sustech.edu" target="_blank">E-Learning System</a></h3>
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
                            <h3><a href="http://repository.sustech.edu/" target="_blank">SUST Repository</a></h3>
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
                            <h3><a href="http://196.1.226.242/student/login" target="_blank">Students Portal</a></h3>
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
                            <h3><a href="#">SUST Campuses</a></h3>
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
                            <h3><a href="https://mail01.sustech.edu/" target="_blank">Staff Webmail</a></h3>
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
                            <h3><a href="{{ URL::to('/news')}}">SUST News</a></h3>
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
                            <h3><a href="#">SUST Journals</a></h3>
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
                            <h3><a href="{{ URL::to('/administration')}}">SUST Administration</a></h3>
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
    <!-- aboutarea__2__section__start -->
    <div class="populerarea sp_top_80 sp_bottom_50">
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
                <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12" data-aos="fade-up">

                    <div class="aboutarea__content__wraper">
                        <div class="section-header mb-4">
                            <span class="sub-title">welcome to sust</span>
                            <h2 class="section-title"> <span class="highlight">Vice-Chancellor's</span> Message</h2>
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
                <div class="col-xl-8 text-center" data-aos="fade-up">
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
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="fade-up">
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
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="fade-up">
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
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="fade-up">
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
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12" data-aos="fade-up">
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
                <div class="col-xl-8 col-lg-8" data-aos="fade-up">
                    <div class="blogarea__content__wraper">
                        @if (isset($data['news'][0]) && $data['news'][0]->photos->isNotEmpty())
                                            <div class="blogarea__img">
                                                <img loading="lazy" src="{{ URL::to($data['news'][0]->photos[0]->img) }}"
                                                    alt="{{ $data['news'][0]->title }}">
                                                <div class="blogarea__date">
                                                    {{ Carbon\Carbon::parse($data['news'][0]->news_date)->day}}
                                                    <span>{{ Carbon\Carbon::parse($data['news'][0]->news_date)->format('M')}}</span>
                                                </div>
                                            </div>
                                            <div class="blogarea__text__wraper">
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
                                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url('/news/details/' . $data['news'][0]->slug) }}&quote={{ urlencode($data['news'][0]->title) }}"
                                                                    target="_blank" class="facebook-share-button">
                                                                    <i class="icofont-facebook"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
                                'url' => url('/news/details/' . $data['news'][0]->slug),
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
                                    <span>{{ Carbon\Carbon::parse($data['news'][1]->news_date)->format('M')}}</span>
                                </div>
                            </div>
                            <div class="blogarea__text__wraper blogarea__text__wraper__2">
                                <h3><a
                                        href="{{ url('/news/details/' . $data['news'][1]->slug) }}">{{ Str::limit($data['news'][1]->title, 62) }}</a>
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
                                    <span>{{ Carbon\Carbon::parse($data['news'][2]->news_date)->format('M')}}</span>
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

                <hr class="col-xl-12 my-3" style="padding: 4px 0">
            </div>
        </div>

    </div>
    <!-- news__section__end -->

    <!-- announcements_and_events_start -->
    <div class="blogarea__2 sp_bottom_20">
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
                            @if ($ad->photos->isNotEmpty())
                                <div class="single__blog__img">
                                    <img loading="lazy" src="{{ URL::to($ad->photos[0]->img) }}" alt="{{ $ad->title }}">
                                </div>
                            @endif
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
                        <a class="default__button w-100" href="{{ URL::to('/news/archive') }}">More Announcements and
                            Events</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- announcements_and_events_end -->
@endsection