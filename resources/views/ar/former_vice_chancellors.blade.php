@extends('ar/layout')
@section('title', 'المدراء السابقين للجامعة')
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
                            <h2 class="heading">المدراء السابقين للجامعة</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('home_ar') }}">الرئيسية</a></li>
                                <li>المدراء السابقين للجامعة</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="blogarea__2 sp_top_100 sp_bottom_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="blog__details__content__wraper mt-5">
                        <h3 class="mb-4" data-aos="fade-up" style="font-family: 'Tajawal', 'Cairo', sans-serif;">المدراء
                            السابقين لجامعتنا</h3>

                        <div class="row mt-4">
                            @foreach($data['vice_chancellors'] as $vc)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 % 1000 }}">
                                    <div class="leadership-card">
                                        <div class="leadership-photo-wrapper">
                                            @if($vc->user && $vc->user->img)
                                                <img class="leadership-photo" src="{{ URL::to($vc->user->img) }}" alt="{{ $vc->user->name }}">
                                            @else
                                                <div class="leadership-placeholder">
                                                    <i class="icofont-businessman"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="leadership-info">
                                            <h3 class="leadership-name">
                                                <a href="{{ ($vc->user && $vc->user->name_en) ? route('staff_home', ['name_en' => $vc->user->name_en]) : '#' }}">
                                                    {{ $vc->user ? $vc->user->name : 'مدير غير معروف' }}
                                                </a>
                                            </h3>
                                            <p class="leadership-title" style="color: var(--primaryColor, #0056b3);">
                                                <i class="icofont-calendar"></i>
                                                {{ \Carbon\Carbon::parse($vc->start_date)->format('Y') }} -
                                                {{ $vc->end_date ? \Carbon\Carbon::parse($vc->end_date)->format('Y') : 'حتى الآن' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="blog__details__tag mt-5" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    مشاركة
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote={{ urlencode('المدراء السابقين للجامعة') }}"
                                        target="_blank" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'المدراء السابقين للجامعة',
        'hashtags' => 'جامعة السودان للعلوم والتكنولوجيا'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @include('ar/administration_sidebar')
            </div>
        </div>
    </div>
@endsection