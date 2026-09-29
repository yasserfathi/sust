@extends('ar/layout')
@section('title', 'مجمعات الجامعة')
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
                            <h2 class="heading">مجمعات الجامعة</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('home_ar') }}">الرئيسية</a></li>
                                <li>مجمعات الجامعة</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blogarea__2 sp_top_50 sp_bottom_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="section-header text-center mb-5" data-aos="fade-up">
                        <h2 class="section-title">مجمعات <span class="highlight">الجامعة</span></h2>
                    </div>

                    @if(!empty($data['university_campuses']?->img))
                        <div class="blogarae__img__2 course__details__img__2 mb-4" data-aos="fade-up">
                            <img loading="lazy" src="{{ URL::to($data['university_campuses']?->img) }}" alt="مجمعات الجامعة" style="width: 100%; border-radius: 15px; object-fit: cover; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
                        </div>
                    @endif
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content university-page-content" id="campusMainContent">
                            {!! \App\Helpers\SanitizeHelper::cleanAndFormat($data['university_campuses']?->detail) !!}
                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    مشاركة
                                </li>
                                <li>
                                    <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => 'مجمعات الجامعة']) }}"
                                        target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'مجمعات الجامعة',
        'hashtags' => 'جامعة_السودان_للعلوم_والتكنولوجيا'
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
