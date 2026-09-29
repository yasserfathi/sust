@extends('ar/layout')

@section('title', 'القيادة')


@section('content')
    <div class="breadcrumbarea" @if (isset($data['banner']) && $data['banner'])
        style="background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.15)),url({{ URL::to($data['banner']) }}); background-size: cover; background-position: center;"
    @else
            style="background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.15)),url({{ URL::to('/images/gallery/vision.jpg') }}); background-size: cover; background-position: center;"
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

<div class="leadership-tree" id="myTabContent">

                        <!-- Tier 1: Chairman -->
                        <div class="leadership-tier">
                            <div class="leader-card" data-aos="fade-up">
                                <img loading="lazy" src="{{ versioned_asset('images/staff/1784996576000000.jpg') }}"
                                    alt="أ.د. شمبول عدلان">
                                <a href="#" class="leader-name">أ.د. شمبول عدلان</a>
                                <a href="#" class="leader-title">رئيس مجلس الجامعة</a>
                            </div>
                        </div>

                        <!-- Tier 2: Vice Chancellor -->
                        @if (isset($data['vice_chancellor']))
                            <div class="leadership-tier">
                                <div class="leader-card" data-aos="fade-up" data-aos-delay="100">
                                    @if($data['vice_chancellor']?->user->img)
                                        <img loading="lazy" src="{{ URL::to($data['vice_chancellor']?->user->img) }}"
                                            alt="{{ $data['vice_chancellor']?->user->name }}">
                                    @else
                                        <div class="leader-icon-placeholder"><i class="icofont-businessman"></i></div>
                                    @endif
                                    <a href="{{ ($data['vice_chancellor'] && $data['vice_chancellor']->user && !empty($data['vice_chancellor']->user->slug)) ? route('staff_home', ['slug' => $data['vice_chancellor']->user->slug]) : '#' }}"
                                        class="leader-name">{{ $data['vice_chancellor']?->user_title_ar }}{{ $data['vice_chancellor']?->user->name }}</a>
                                    <a href="{{ route('college_home_ar', ['name' => $data['vice_chancellor']?->college->slug]) }}"
                                        class="leader-title">مدير الجامعة</a>
                                </div>
                            </div>
                        @else
                            <div class="leadership-tier">
                                <p>No Vice-Chancellor data available.</p>
                            </div>
                        @endif

                        <!-- Tier 3: University Ranks -->
                        @if (isset($data['university_ranks']) && $data['university_ranks']?->count() > 0)
                            <div class="leadership-tier">
                                @foreach ($data['university_ranks'] as $rank_person)
                                    <div class="leader-card" data-aos="fade-up" data-aos-delay="200">
                                        @if($rank_person->user->img)
                                            <img loading="lazy" src="{{ URL::to($rank_person->user->img) }}"
                                                alt="{{ $rank_person->user->name }}">
                                        @else
                                            <div class="leader-icon-placeholder"><i class="icofont-businessman"></i></div>
                                        @endif
                                        <a href="{{ ($rank_person->user && !empty($rank_person->user->slug)) ? route('staff_home', ['slug' => $rank_person->user->slug]) : '#' }}"
                                            class="leader-name">{{ $rank_person->user_title_ar }}{{ $rank_person->user->name }}</a>
                                        <span class="leader-title">{{ $rank_person->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Tier 4: Leadership / Deans -->
                        <div class="leadership-tier">
                            @foreach ($data['leadership'] as $key_person)
                                <div class="leader-card" data-aos="fade-up" data-aos-delay="300">
                                    @if($key_person->user->img)
                                        <img loading="lazy" src="{{ URL::to($key_person->user->img) }}"
                                            alt="{{ $key_person->user->name }}">
                                    @else
                                        <div class="leader-icon-placeholder"><i class="icofont-businessman"></i></div>
                                    @endif
                                    <a href="{{ ($key_person->user && !empty($key_person->user->slug)) ? route('staff_home', ['slug' => $key_person->user->slug]) : '#' }}"
                                        class="leader-name">{{ $key_person->user_title_ar }}{{ $key_person->user->name }}</a>
                                    <a href="{{ route('college_home_ar', ['name' => $key_person->college->slug]) }}"
                                        class="leader-title">{{ $key_person->college->name }}</a>
                                    <span class="leader-position">{{ $key_person->position_ar }}</span>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
                @include('ar/administration_sidebar')
            </div>
        </div>
    </div>
@endsection