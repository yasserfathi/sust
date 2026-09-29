@extends('ar/layout')
@section('title', $data['page']?->title ?? '')
@section('keywords')
<meta name="keywords" content="{{ $data['page']?->keywords ?? '' }}">
@endsection

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
                            <h2 class="heading">{{ $data['page']?->title ?? '' }}</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('home_ar') }}">الرئيسية</a></li>
                                <li>{{ $data['page']?->title ?? '' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumbarea__section__end-->
    <div class="blogarea__2 sp_top_40 sp_bottom_40">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    @if(isset($data['page']?->img) && $data['page']?->img)
                        <div class="blogarae__img__2 course__details__img__2 " data-aos="fade-up">
                            <img loading="lazy" src="{{ URL::to($data['page']?->img) }}" alt="{{ $data['page']?->title ?? '' }}">
                        </div>
                    @endif
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            {!! \App\Helpers\SanitizeHelper::cleanAndFormat($data['page']?->detail ?? '') !!}
                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    مشاركة
                                </li>
                                <li>
                                    <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => $data['page']?->title ?? '']) }}"
                                        target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => $data['page']?->title ?? '',
        'hashtags' => 'جامعة_السودان_للعلوم_والتكنولوجيا'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @php
                    $leadership_slugs = ['administration', 'vice_chancellor', 'deputy_vice_chancellor', 'principal'];
                    $admin_slugs = [
                        'main_campus', 'southern_campus', 'western_campus', 'medical_campus', 'music_drama_campus',
                        'kuku_campus', 'shambat_campus', 'northern_campus', 'wad_al_maqbul_campus', 'forestry_campus',
                        'radiologic_science_campus', 'sust_mission_vision_goals', 'khartoum_state', 'about_sust', 'vice_chancellor_message'
                    ];
                    $currentSlug = request()->route('slug');
                    $isCampus = request()->routeIs('sust_campus_detail_ar') || str_ends_with((string)$currentSlug, '_campus') || in_array($currentSlug, $admin_slugs);
                @endphp
                @if(in_array($currentSlug, $leadership_slugs))
                    @include('ar/university_council_sidebar')
                @elseif($isCampus || in_array($currentSlug, $admin_slugs))
                    @include('ar/administration_sidebar')
                @else
                    @include('ar/sidebar')
                @endif
            </div>
        </div>
    </div>
@endsection