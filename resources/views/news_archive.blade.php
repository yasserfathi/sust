@extends('layout')

@section('title', 'News Archive')


@section('content')
    <div class="breadcrumbarea"
    @if (isset($data['banner']) && $data['banner'])
        style="background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.15)),url({{ URL::to($data['banner']) }}); background-size: cover; background-position: center;"
    @else
            style="background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.15)),url({{ URL::to('/images/gallery/vision.jpg') }}); background-size: cover; background-position: center;"
    @endif >

    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb__content__wraper" data-aos="fade-up">
                    <div class="breadcrumb__title">
                        <h2 class="heading">News</h2>
                    </div>
                    <div class="breadcrumb__inner">
                        <ul>
                            <li><a href="{{ URL::to('/')}}">Home</a></li>
                            <li>News</li>
                        </ul>
                    </div>
                </div>


            </div>
        </div>
    </div>


    </div>
    <!-- breadcrumbarea__section__end-->

    <div class="blogarea__2 sp_top_30 sp_bottom_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="tab-content tab__content__wrapper" id="NewsContent">

                        <div class="row" id="projects__two" role="tabpanel" aria-labelledby="projects__two">
                            @php $index = 0 @endphp
                            @forelse ($data['news'] as $news)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 grid-item column__custom__class mb-4">
                                    <div class="blogarea__content__wraper m-0 h-100 d-flex flex-column shadow-sm rounded-4 overflow-hidden bg-white" style="transition: 0.3s;" data-aos="fade-up">
                                        <div class="blogarea__img">
                                            <a href="{{ URL::to('/news/details/' . $news->slug)}}">
                                                <img loading="lazy" src="{{ URL::to($news->first_image) }}"
                                                    alt="{{ $news->title }}" style="width: 100%; height: 210px; object-fit: cover;">
                                            </a>
                                            <div class="blogarea__date small__date text-white" style="color: #fff !important; font-size: 18px; padding: 10px 15px; line-height: 1.2;">
                                                {{ \Carbon\Carbon::parse($news->news_date)->day }}
                                                <span style="color: #fff !important; font-size: 11px; display: block; margin-top: 2px;">{{ \Carbon\Carbon::parse($news->news_date)->translatedFormat('M Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="blogarea__text__wraper blogarea__text__wraper__2 flex-grow-1">
                                            <h3 style="font-size: 16px; line-height: 1.6; margin-bottom: 0;">
                                                <a href="{{ URL::to('/news/details/' . $news->slug)}}">
                                                    {{ Str::limit($news->title, 70) }}
                                                </a>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted" style="font-size: 18px;">No news available at the moment.</p>
                                </div>
                            @endforelse
                        </div>


                        {{ $data['news']?->onEachSide(1)->links('vendor.pagination.custom') }}

                    </div>
                </div>
                @include('sidebar')
            </div>
        </div>
    </div>
@endsection