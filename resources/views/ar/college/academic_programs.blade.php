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
                            <h2 class="heading">البرامج الأكاديمية</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ URL::to('/ar')}}">الرئيسية</a></li>
                                <li>البرامج الأكاديمية</li>
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
                            alt="البرامج الأكاديمية">
                    </div>

                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">

                            @if(count($data['programs']) > 0)
                                @foreach($data['programs'] as $program)
                                    <div class="program-item mb-5">
                                        <h4>{{ $program->program_name }}</h4>
                                        <p><strong>الساعات المعتمدة:</strong> {{ $program->credit_hours }}</p>
                                        <p><strong>الدرجة العلمية:</strong>
                                            @if($program->program_type == 1) بكالوريوس
                                            @elseif($program->program_type == 2) ماجستير
                                            @elseif($program->program_type == 3) دكتوراه
                                            @elseif($program->program_type == 4) دبلوم
                                            @else برنامج
                                            @endif
                                        </p>
                                        <hr>
                                    </div>
                                @endforeach
                            @else
                                <p>لا توجد برامج أكاديمية متاحة حالياً.</p>
                            @endif

                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    مشاركة
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote=البرامج الأكاديمية"
                                        target="_blank" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'البرامج الأكاديمية',
        'hashtags' => 'جامعة السودان للعلوم والتكنولوجيا'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @include('ar/sidebar')
            </div>
        </div>
    </div>
@endsection