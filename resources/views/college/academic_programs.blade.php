@extends('college/layout')

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
                            <h2 class="heading">Academic Programs</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ URL::to('/')}}">Home</a></li>
                                <li>Academic Programs</li>
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
                    <div class="blogarae__img__2 course__details__img__2 aos-init aos-animate" data-aos="fade-up">
                        <img loading="lazy" src="http://196.1.226.242/images/albums/1856596790626587.jpeg"
                            alt="Academic Programs">
                    </div>

                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">

                            @if(count($data['programs']) > 0)
                                @foreach($data['programs'] as $program)
                                    <div class="program-item mb-5">
                                        <h4>{{ $program->program_name_en }}</h4>
                                        <p><strong>Credit Hours:</strong> {{ $program->credit_hours }}</p>
                                        <p><strong>Type:</strong>
                                            @if($program->program_type == 1) Bachelor
                                            @elseif($program->program_type == 2) Master
                                            @elseif($program->program_type == 3) PhD
                                            @elseif($program->program_type == 4) Diploma
                                            @else Program
                                            @endif
                                        </p>
                                        <hr>
                                    </div>
                                @endforeach
                            @else
                                <p>No academic programs available at the moment.</p>
                            @endif

                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    Share
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote=Academic Programs"
                                        target="_blank" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'Academic Programs',
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