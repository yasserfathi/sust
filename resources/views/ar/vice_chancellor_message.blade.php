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
                            <h2 class="heading">كلمة مدير الجامعة</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('home_ar') }}">الرئيسية</a></li>
                                <li>كلمة مدير الجامعة</li>
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
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            @if(isset($data['vice_chancellor_message']) && $data['vice_chancellor_message'])
                                <div class="blogarae__img__2 course__details__img__2 aos-init aos-animate" data-aos="fade-up"
                                    style="float: right; margin-left: 30px; margin-bottom: 20px; max-width: 40%;">
                                    <img loading="lazy" src="{{ URL::to($data['vice_chancellor_message']->img) }}"
                                        alt="About College" style="width: 100%;">
                                </div>
                                @php
                                    $content = preg_replace(
                                        '/<p([^>]*)>/',
                                        '<p$1 data-aos="fade-up">',
                                        $data['vice_chancellor_message']->detail
                                    );
                                @endphp

                                {!! $content !!}
                            @else
                                <div class="alert alert-warning">المحتوى غير متوفر.</div>
                            @endif
                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    Share
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote=Vice-Chancellor"
                                        target="_blank" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'كلمة مدير الجامعة',
        'hashtags' => 'جامعة السودان للعلوم والتكنولوجيا'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @include('ar/sidebar')
            </div>
        </div>
    </div>
@endsection