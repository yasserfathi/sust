@extends('ar/college/layout')

@section('meta')
@php
$ogImage = versioned_asset('images/logo.png');
if (isset($data['ads']) && isset($data['ads']->photos[0])) {
    $ogImage = URL::to($data['ads']->photos[0]->img);
} elseif (isset($data['ads']) && !empty($data['ads']->file)) {
    $ext = strtolower(pathinfo($data['ads']->file, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $ogImage = URL::to($data['ads']->file);
    }
}
$ogDesc = strip_tags($data['ads']?->detail ?? '');
$ogDesc = \Illuminate\Support\Str::limit($ogDesc, 150);
@endphp
<meta property="og:title" content="{{ $data['ads']?->title ?? 'SUST' }}">
<meta property="og:description" content="{{ $ogDesc }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
<meta name="keywords" content="{{ $data['ads']?->keywords ?? '' }}">
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
                            <h2 class="heading">تفاصيل الإعلان</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route(($data['college_type'] ?? 'college') . '_home_ar', ['name' => $data['name']]) }}">الرئيسية</a></li>
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
                    @if(isset($data['ads']) && $data['ads'])
                    <div class="blogarae__img__2 course__details__img__2 " data-aos="fade-up">
                        @php
                            $adPhoto = $data['ads']?->photos[0]->img ?? $data['ads']?->photos[0]->thumb_img ?? $data['ads']?->photos[0]->photo ?? null;
                            if (!$adPhoto && !empty($data['ads']?->file)) {
                                $ext = strtolower(pathinfo($data['ads']->file, PATHINFO_EXTENSION));
                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                    $adPhoto = $data['ads']->file;
                                }
                            }
                        @endphp
                        @if($adPhoto)
                            <img loading="lazy" src="{{ URL::to($adPhoto) }}" alt="{{ $data['ads']->title }}">
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
                            {!! \App\Helpers\SanitizeHelper::cleanAndFormat($data['ads']->detail) !!}

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
                                    <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => $data['ads']->title]) }}"
                                        target="_blank" rel="noopener noreferrer" class="facebook-share-button">
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
                    @else
                    <div class="alert alert-warning text-center mt-5 mb-5 fs-4 rounded-3">
                        عفواً، المحتوى غير متوفر حالياً
                    </div>
                    @endif
                </div>
                @include('ar/college/sidebar')
            </div>
        </div>
    </div>
@endsection
