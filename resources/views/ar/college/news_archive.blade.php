@extends('ar/college/layout')

@section('title', 'أرشيف الأخبار')


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
                            <h2 class="heading">أرشيف الأخبار</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a
                                        href="{{ route('college_home_ar', ['name' => $data['name'] ?? request('name')]) }}">الرئيسية</a>
                                </li>
                                <li>أرشيف الأخبار</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blogarea sp_top_100 sp_bottom_100">
        <div class="container">
            <div class="row">
                @if(isset($data['news']) && count($data['news']) > 0)
                    @foreach($data['news'] as $news)
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 grid-item column__custom__class" data-aos="fade-up">
                            <div class="gridarea__wraper gridarea__wraper__2">
                                <div class="gridarea__img w-100">
                                    <a
                                        href="{{ route('college_news_detail_ar', ['name' => $data['name'], 'slug' => str_replace(' ', '-', $news->title)]) }}">
                                        @if(isset($news->photos) && isset($news->photos[0]))
                                            <img loading="lazy" src="{{ URL::to($news->photos[0]->img) }}" alt="{{ $news->title }}">
                                        @else
                                            <img loading="lazy" src="{{ versioned_asset('images/default-news.jpg') }}" alt="{{ $news->title }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="gridarea__content w-100">
                                    <div class="">
                                        <a
                                            href="{{ route('college_news_detail_ar', ['name' => $data['name'], 'slug' => str_replace(' ', '-', $news->title)]) }}">
                                            <h6>{{ $news->title }}</h6>
                                        </a>
                                        <h6>{{ $news->news_date }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-xl-12">
                        <div class="blogarea__pagination sp_top_30">
                            @if(method_exists($data['news'], 'links'))
                                {{ $data['news']?->onEachSide(1)->links() }}
                            @endif
                        </div>
                    </div>
                @else
                    <div class="col-xl-12">
                        <div class="alert alert-warning">لا توجد أخبار لعرضها حالياً.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection