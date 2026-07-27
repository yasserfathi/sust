@extends('college/layout')

@section('content')
<style>
    /* Premium Theme Styles for Details Page */
    :root {
        --college-primary: #ce6148;
        --college-secondary: #a84f3a;
        --college-accent: #C5A059;
        --college-bg: #f5f7fa;
        --text-dark: #2c3e50;
        --text-muted: #6c757d;
    }

    .breadcrumbarea {
        background: linear-gradient(to right, var(--college-primary), var(--college-secondary));
        padding: 60px 0;
        margin-bottom: 40px;
    }

    .breadcrumb__title .heading {
        color: white;
        font-family: 'Figtree', sans-serif;
        font-weight: 700;
        font-size: 2.5rem;
    }

    .breadcrumb__inner ul li {
        display: inline-block;
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
    }

    .breadcrumb__inner ul li a {
        color: white;
        font-weight: 600;
    }

    .breadcrumb__inner ul li:after {
        content: '/';
        margin: 0 10px;
        color: rgba(255, 255, 255, 0.5);
    }

    .breadcrumb__inner ul li:last-child:after {
        display: none;
    }

    .blogarae__img__2 img {
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        width: 100%;
    }

    .blog__details__content h5 {
        color: var(--college-primary);
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.3;
    }

    .blog__details__content p {
        color: var(--text-dark);
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 20px;
    }
</style>

@section('title', $data['news']->title)

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
                    <div class="blogarae__img__2 course__details__img__2 aos-init aos-animate" data-aos="fade-up">
                        @if (isset($data['news']->photos[0]))
                            <img loading="lazy" src="{{ URL::to($data['news']->photos[0]->img) }}"
                                alt="{{$data['news']->title}}">
                        @endif
                    </div>
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            <h5>{{$data['news']->title}}</h5>
                            @php
                                $content = preg_replace(
                                    '/<p([^>]*)>/',
                                    '<p$1 data-aos="fade-up">',
                                    $data['news']->detail
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
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote={{ urlencode($data['news']->title) }}"
                                        target="_blank" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => $data['news']->title,
        'hashtags' => 'Sudan University of Science and Technology'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
                @include('college.sidebar')
            </div>
        </div>
    </div>
@endsection