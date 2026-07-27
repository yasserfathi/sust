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
                            <h3 class="heading">تفاصيل الإعلانات والأحداث</h3>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ URL::to('/ar')}}">الرئيسية</a></li>
                                <li>تفاصيل الإعلانات والأحداث</li>
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
                    <div class="blogarae__img__2 course__details__img__2 aos-init aos-animate" data-aos="fade-up">
                        @if(isset($data['ads']->photos[0]))
                            <img loading="lazy" src="{{ URL::to($data['ads']->photos[0]->photo) }}"
                                alt="{{$data['ads']->title}}">
                        @else
                            <img loading="lazy" src="{{ URL::to('images/logos/1840372294400317.png') }}"
                                alt="{{$data['ads']->title}}">
                        @endif
                    </div>
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            <h5>{{$data['ads']->title}}</h5>
                            @php
                                $content = preg_replace(
                                    '/<p([^>]*)>/',
                                    '<p$1 data-aos="fade-up">',
                                    $data['ads']->detail
                                );
                                $content = preg_replace(
                                    '/<img([^>]+)(?<!loading="\w")(\/?)>/i',
                                    '<img$1 loading="lazy"$2>',
                                    $content
                                );
                            @endphp

                            {!! $content !!}

                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    Share
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote={{ urlencode($data['ads']->title) }}"
                                        target="_blank" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => $data['ads']->title,
        'hashtags' => 'جامعة السودان للعلوم والتكنولوجيا'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>







                    </div>
                </div>
                @include('ar/ads_sidebar')
            </div>
        </div>
    </div>
@endsection