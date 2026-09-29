@extends('layout')

@section('title', $data['ads']?->title)
@section('description', \Illuminate\Support\Str::limit(strip_tags($data['ads']?->detail ?? ''), 160))
@section('og_type', 'article')

@section('schema')
    @php
        $adPhoto = versioned_asset('images/logo.png');
        if (isset($data['ads']->photos[0])) {
            $adPhoto = URL::to($data['ads']->photos[0]->img);
        } elseif (!empty($data['ads']->file)) {
            $adPhoto = URL::to($data['ads']->file);
        }
    @endphp
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Event",
        "name": "{{ addslashes($data['ads']?->title ?? '') }}",
        "image": ["{{ $adPhoto }}"],
        "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($data['ads']?->detail ?? ''), 160)) }}",
        "startDate": "{{ isset($data['ads']->start_date) ? $data['ads']->start_date : now()->toDateString() }}",
        "organizer": {
            "@type": "Organization",
            "name": "Sudan University of Science and Technology",
            "url": "{{ url('/') }}"
        }
    }
    </script>
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
                        <h2 class="heading">Ads Details</h2>
                    </div>
                    <div class="breadcrumb__inner">
                        <ul>
                            <li><a href="{{ URL::to('/')}}">Home</a></li>
                            <li>Ads Details</li>
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
                            <img loading="lazy" src="{{ URL::to($adPhoto) }}"
                                alt="{{$data['ads']?->title}}">
                        @else
                            <img loading="lazy" src="{{ URL::to('images/logos/1840372294400317.png') }}"
                                alt="{{$data['ads']?->title}}">
                        @endif
                    </div>
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            <h5>{{$data['ads']?->title}}</h5>
                            {!! \App\Helpers\SanitizeHelper::cleanAndFormat($data['ads']?->detail) !!}

                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    Share
                                </li>
                                <li>
                                    <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => $data['ads']?->title]) }}"
                                        target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => $data['ads']?->title,
        'hashtags' => 'SUST,SudanUniversity'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>
                @include('ads_sidebar')
            </div>
        </div>
    </div>
@endsection