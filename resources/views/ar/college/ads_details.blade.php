@extends('ar/college/layout')

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
                            <h2 class="heading">تفاصيل الإعلان</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('college_home_ar', ['name' => $data['name']]) }}">الرئيسية</a></li>
                                <li>تفاصيل الإعلان</li>
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
                    <div class="blogarae__img__2 course__details__img__2 aos-init aos-animate" data-aos="fade-up">
                        @if(isset($data['ads']->photos) && count($data['ads']->photos) > 0)
                            <img loading="lazy" src="{{ URL::to($data['ads']->photos[0]->img ?? $data['ads']->photos[0]->photo) }}" alt="{{ $data['ads']->title }}">
                        @else
                             <img loading="lazy" src="{{ URL::to('images/logos/1840372294400317.png') }}" alt="{{ $data['ads']->title }}">
                        @endif
                    </div>
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            <h5>{{ $data['ads']->title }}</h5>
                            <div class="blog__details__meta">
                                <ul>
                                    <li><i class="icofont-calendar"></i> {{ Carbon\Carbon::parse($data['ads']->ad_date)->format('Y-m-d') }}</li>
                                </ul>
                            </div>
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

                            @if(isset($data['ads']->file))
                                <div class="mt-4">
                                     <a href="{{ URL::to($data['ads']->file) }}" class="default__button" target="_blank">تحميل المرفق</a>
                                </div>
                            @endif
                        </div>
                        
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    مشاركة
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
                                        'hashtags' => 'جامعة_السودان_للعلوم_والتكنولوجيا'
                                    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
