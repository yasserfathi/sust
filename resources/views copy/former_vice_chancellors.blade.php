@extends('layout')
@section('title', 'Former Vice Chancellors')
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
                            <h2 class="heading">Former Vice Chancellors</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ URL::to('/')}}">Home</a></li>
                                <li>Former Vice Chancellors</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .vc-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            padding: 25px 15px;
            text-align: center;
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .vc-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(201, 75, 75, 0.2);
        }

        .vc-img-container {
            width: 140px;
            height: 140px;
            margin: 0 auto 20px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #fff;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            transition: transform 0.4s ease;
        }

        .vc-card:hover .vc-img-container {
            transform: scale(1.05);
        }

        .vc-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .vc-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 12px;
            font-family: 'Inter', sans-serif;
        }

        .vc-period {
            font-size: 0.95rem;
            color: #ce6148;
            background: rgba(201, 75, 75, 0.08);
            padding: 6px 16px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .vc-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }
    </style>

    <div class="blogarea__2 sp_top_100 sp_bottom_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="blog__details__content__wraper mt-5">
                        <h3 class="mb-4" data-aos="fade-up">Our Former Vice Chancellors</h3>

                        <div class="vc-grid">
                            @foreach($data['vice_chancellors'] as $vc)
                                <div data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 % 1000 }}">
                                    <div class="vc-card">
                                        <div class="vc-img-container">
                                            @if($vc->user && $vc->user->img)
                                                <img src="{{ URL::to($vc->user->img) }}" alt="{{ $vc->user->name_en }}">
                                            @else
                                                <img src="{{ URL::to('/images/default-avatar.png') }}" alt="Default Avatar"
                                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($vc->user ? $vc->user->name_en : 'VC') }}&background=ce6148&color=fff&size=128'">
                                            @endif
                                        </div>
                                        <div class="vc-name">
                                            {{ $vc->user ? $vc->user->name_en : 'Unknown Leader' }}
                                        </div>
                                        <div class="vc-period">
                                            <i class="icofont-calendar"></i>
                                            {{ \Carbon\Carbon::parse($vc->start_date)->format('Y') }} -
                                            {{ $vc->end_date ? \Carbon\Carbon::parse($vc->end_date)->format('Y') : 'Present' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="blog__details__tag mt-5" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    Share
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote={{ urlencode('Former Vice Chancellors') }}"
                                        target="_blank" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'Former Vice Chancellors',
        'hashtags' => 'Sudan University of Science and Technology'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                @include('administration_sidebar')
            </div>
        </div>
    </div>
@endsection