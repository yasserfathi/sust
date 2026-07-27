@extends('ar/layout')

@section('content')
    <div class="breadcrumbarea" @if (isset($data['banner']) && $data['banner'])
        style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.5)),url({{ URL::to($data['banner']) }}); background-size: cover; background-position: center;"
    @else
            style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.5)),url({{ URL::to('/images/gallery/vision.jpg') }}); background-size: cover; background-position: center;"
        @endif>

        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="breadcrumb__content__wraper" data-aos="fade-up">
                        <div class="breadcrumb__title">
                            <h2 class="heading">قيادات الجامعة</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('home_ar') }}">الرئيسية</a></li>
                                <li>قيادات الجامعة</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbarea__section__end-->
    <div class="blogarea__2 sp_top_100 sp_bottom_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="tab-content tab__content__wrapper" id="myTabContent">

                        <div class="row" id="projects__two" role="tabpanel" aria-labelledby="projects__two">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mx-auto mb-4">
                                    <div class="single__service" data-aos="fade-up">
                                        <div class="service__img">
                                                <img loading="lazy" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; position: relative; z-index: 2;"
                                                    src="{{ asset('images/staff/1784996576000000.jpg') }}" alt="أ.د. شمبول عدلان">
                                        </div>
                                        <div class="service__content">
                                            <h3><a href="#">أ.د. شمبول عدلان</a></h3>
                                            <p><a href="#">رئيس مجلس الجامعة</a></p>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        @if (isset($data['vice_chancellor']))
                            <div class="row" id="projects__two" role="tabpanel" aria-labelledby="projects__two">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mx-auto mb-4">
                                    <div class="single__service" data-aos="fade-up">
                                        <div class="service__img">
                                            @if($data['vice_chancellor']->user->img)
                                                <img loading="lazy" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; position: relative; z-index: 2;"
                                                    src="{{ URL::to($data['vice_chancellor']->user->img) }}" alt="{{ $data['vice_chancellor']->user->name }}">
                                            @else
                                                <i class="icofont-businessman service__icon"></i>
                                            @endif
                                        </div>
                                        <div class="service__content">
                                            <h3><a href="{{ route('staff_home', ['name_en' => $data['vice_chancellor']->user->name_en]) }}">{{ $data['vice_chancellor']->user_title_ar }}{{ $data['vice_chancellor']->user->name }}</a></h3>
                                            <p><a href="{{ route('college_home_ar', ['name' => $data['vice_chancellor']->college->slug]) }}">مدير الجامعة</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p>No Vice-Chancellor data available.</p>

                        @endif

                        <div class="row" id="projects__two" role="tabpanel" aria-labelledby="projects__two">
                            @php $index = 0 @endphp
                            @foreach ($data['leadership'] as $key_person)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
                                    <div class="single__service" data-aos="fade-up">
                                        <div class="service__img">
                                            @if($key_person->user->img)
                                                <img loading="lazy" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; position: relative; z-index: 2;"
                                                    src="{{ URL::to($key_person->user->img) }}" alt="{{ $key_person->user->name }}">
                                            @else
                                                <i class="icofont-businessman service__icon"></i>
                                            @endif
                                        </div>
                                        <div class="service__content">
                                            <h3><a href="{{ route('staff_home', ['name_en' => $key_person->user->name_en]) }}">{{ $key_person->user_title_ar }}{{ $key_person->user->name }}</a></h3>
                                            <p><a href="{{ route('college_home_ar', ['name' => $key_person->college->slug]) }}">{{ $key_person->college->name }}</a></p>
                                            <p>{{ $key_person->position_ar }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
                @include('ar/sidebar')
            </div>
        </div>
    </div>
@endsection