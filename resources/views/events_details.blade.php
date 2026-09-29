@extends('layout')

@section('title', $data['event']?->title)
@section('description', \Illuminate\Support\Str::limit(strip_tags($data['event']?->detail ?? ''), 160))
@section('og_type', 'article')

@section('meta')
    @php
        $ogImage = versioned_asset('images/logo.png');
        if (isset($data['event']->photos[0])) {
            $ogImage = URL::to($data['event']->photos[0]->img);
        } elseif (!empty($data['event']->file)) {
            $ext = strtolower(pathinfo($data['event']->file, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $ogImage = URL::to($data['event']->file);
            }
        }
    @endphp
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    @if(!empty($data['event']?->keywords))
        <meta name="keywords" content="{{ $data['event']->keywords }}">
    @endif
@endsection

@section('schema')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Event",
        "name": "{{ addslashes($data['event']?->title ?? '') }}",
        "startDate": "{{ isset($data['event']->workshop_date) ? \Carbon\Carbon::parse($data['event']->workshop_date)->toIso8601String() : (isset($data['event']->created_at) ? $data['event']->created_at->toAtomString() : now()->toAtomString()) }}",
        "image": [
            "{{ $ogImage }}"
        ],
        "organizer": {
            "@type": "Organization",
            "name": "Sudan University of Science and Technology",
            "url": "{{ url('/') }}"
        },
        "location": {
            "@type": "Place",
            "name": "Sudan University of Science and Technology",
            "address": "Khartoum, Sudan"
        },
        "description": "{{ addslashes(\Illuminate\Support\Str::limit(strip_tags($data['event']?->detail ?? ''), 160)) }}",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "{{ url()->current() }}"
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
                            <h2 class="heading">{{ ucfirst($data['type'] . 's') }}</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ URL::to('/')}}">Home</a></li>
                                <li><a href="{{ route('events_archive', ['type' => $data['type'] . 's']) }}">{{ ucfirst($data['type'] . 's') }}</a></li>
                                <li>{{ Str::limit($data['event']?->title, 40) }}</li>
                            </ul>
                        </div>
                    </div>


                </div>
            </div>
        </div>


    </div>
    <!-- breadcrumbarea__section__end-->

    <div class="blogarea__2 sp_top_50 sp_bottom_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="blogarae__img__2 course__details__img__2 " data-aos="fade-up">
                        @if (isset($data['event']?->photos[0]))
                            <img loading="lazy" src="{{ URL::to($data['event']?->photos[0]->img) }}"
                                alt="{{$data['event']?->title}}">
                        @endif
                    </div>
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            <h5>{{$data['event']?->title}}</h5>
                            @php
                                $content = \App\Helpers\SanitizeHelper::cleanAndFormat($data['event']?->detail);

                                $extraPhotos = collect($data['event']?->photos ?? [])->skip(1)->values();

                                // Include the attached file if it's an image
                                if (!empty($data['event']?->file)) {
                                    $ext = strtolower(pathinfo($data['event']->file, PATHINFO_EXTENSION));
                                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                        $extraPhotos->push((object) ['img' => $data['event']->file]);
                                    }
                                }

                                if ($extraPhotos->isNotEmpty()) {
                                    $paragraphs = explode('</p>', $content);
                                    if (count($paragraphs) > 1) {
                                        $newContent = '';
                                        foreach ($paragraphs as $index => $para) {
                                            $newContent .= $para;
                                            if ($index < count($paragraphs) - 1) {
                                                $newContent .= '</p>';
                                            }
                                            if (($index + 1) % 2 == 0 && $extraPhotos->isNotEmpty()) {
                                                $photo = $extraPhotos->shift();
                                                if ($photo) {
                                                    $newContent .= '<div class="blogarae__img__2 course__details__img__2 my-4 w-75 mx-auto" data-aos="fade-up"><img loading="lazy" src="' . e(URL::to($photo->img)) . '" alt="' . e($data['event']?->title ?? '') . '"></div>';
                                                }
                                            }
                                        }

                                        foreach ($extraPhotos as $photo) {
                                            $newContent .= '<div class="blogarae__img__2 course__details__img__2 my-4 w-75 mx-auto" data-aos="fade-up"><img loading="lazy" src="' . e(URL::to($photo->img)) . '" alt="' . e($data['event']?->title ?? '') . '"></div>';
                                        }

                                        $content = $newContent;
                                    } else {
                                        foreach ($extraPhotos as $photo) {
                                            $content .= '<div class="blogarae__img__2 course__details__img__2 my-4 w-75 mx-auto" data-aos="fade-up"><img loading="lazy" src="' . e(URL::to($photo->img)) . '" alt="' . e($data['event']?->title ?? '') . '"></div>';
                                        }
                                    }
                                }
                            @endphp

                            {!! $content !!}

                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    Share
                                </li>
                                <li>
                                    <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => $data['event']?->title]) }}"
                                        target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => $data['event']?->title,
        'hashtags' => 'SUST,SudanUniversity'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>


                    </div>
                </div>
                @include('events_sidebar')
            </div>
        </div>
    </div>
@endsection