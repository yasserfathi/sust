@extends('college/layout')

@section('title', $data['news']?->title ?? 'News')

@section('meta')
@php
$ogImage = versioned_asset('images/logo.png');
if (isset($data['news']) && isset($data['news']->photos[0])) {
    $ogImage = URL::to($data['news']->photos[0]->img);
} elseif (isset($data['news']) && !empty($data['news']->file)) {
    $ext = strtolower(pathinfo($data['news']->file, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
        $ogImage = URL::to($data['news']->file);
    }
}
$ogDesc = strip_tags($data['news']?->detail ?? '');
$ogDesc = \Illuminate\Support\Str::limit($ogDesc, 150);
@endphp
<meta property="og:title" content="{{ $data['news']?->title ?? 'SUST' }}">
<meta property="og:description" content="{{ $ogDesc }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
<meta name="keywords" content="{{ $data['news']?->keywords ?? '' }}">
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
                            <h2 class="heading">News Details</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('college_home', $data['name']) }}">Home</a></li>
                                <li>News Details</li>
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
                    @if(isset($data['news']) && $data['news'])
                    <div class="blogarae__img__2 course__details__img__2 " data-aos="fade-up">
                        @if (isset($data['news']->photos[0]))
                            <img loading="lazy" src="{{ URL::to($data['news']->photos[0]->img) }}"
                                alt="{{$data['news']->title}}">
                        @endif
                    </div>
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            <h5>{{$data['news']->title}}</h5>
                            {!! \App\Helpers\SanitizeHelper::cleanAndFormat($data['news']->detail) !!}

                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    Share
                                </li>
                                <li>
                                    <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => $data['news']->title]) }}"
                                        target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => $data['news']->title,
        'hashtags' => 'SUST,SudanUniversity'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                    @else
                    <div class="alert alert-warning text-center mt-5 mb-5" style="font-size: 1.5rem; border-radius: 10px;">
                        We apologize, the content is not available at this time.
                    </div>
                    @endif
                </div>
                @include('college.sidebar')
            </div>
        </div>
    </div>
@endsection