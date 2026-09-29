@extends('college/layout')

@section('title', 'Ads & Events Archive')


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
                            <h2 class="heading">Announcements</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('college_home', ['name' => $data['name']]) }}">Home</a></li>
                                <li>Announcements</li>
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
                @if(isset($data['ads']) && count($data['ads']) > 0)
                    @foreach ($data['ads'] as $ad)
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12" data-aos="fade-up">
                            <div class="single__blog__wraper">
                                <div class="single__blog__img">
                                    <a href="{{ route(($data['college_type'] ?? 'college') . '_ads_detail', ['name' => $data['name'], 'slug' => $ad->slug ?: str_replace(' ', '-', $ad->title)]) }}">
                                        @if(isset($ad->first_image) && $ad->first_image)
                                            <img loading="lazy" src="{{ URL::to($ad->first_image) }}"
                                                alt="{{ $ad->title }}">
                                        @else
                                            <img loading="lazy" src="{{ URL::to('images/logos/1840372294400317.png') }}"
                                                alt="{{ $ad->title }}">
                                        @endif
                                    </a>
                                    <div class="blogarea__date">
                                        {{ Carbon\Carbon::parse($ad->ad_date)->day }}
                                        <span>{{ Carbon\Carbon::parse($ad->ad_date)->format('M') }}</span>
                                    </div>
                                </div>
                                <div class="blogarea__text__wraper">
                                    <h3>
                                        <a href="{{ route(($data['college_type'] ?? 'college') . '_ads_detail', ['name' => $data['name'], 'slug' => $ad->slug ?: str_replace(' ', '-', $ad->title)]) }}">
                                            {{ Str::limit($ad->title, 60) }}
                                        </a>
                                    </h3>
                                    <div class="blogarea__para">
                                        <p>{{ Str::limit(strip_tags($ad->detail_portion), 100) }}</p>
                                    </div>
                                    <div class="blogarea__icon">
                                        <div class="blogarea__person">
                                            <a href="{{ route(($data['college_type'] ?? 'college') . '_ads_detail', ['name' => $data['name'], 'slug' => $ad->slug ?: str_replace(' ', '-', $ad->title)]) }}">
                                                Read More
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="col-xl-12">
                        <div class="blogarea__pagination sp_top_30">
                            @if(method_exists($data['ads'], 'links'))
                                {{ $data['ads']?->onEachSide(1)->links() }}
                            @endif
                        </div>
                    </div>
                @else
                    <div class="col-xl-12">
                        <div class="alert alert-warning">No announcements available at the moment.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
