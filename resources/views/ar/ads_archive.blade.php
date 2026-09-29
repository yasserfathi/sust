@extends('ar/layout')

@section('title', 'الإعلانات والأحداث')


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
                        <h2 class="heading">أرشيف الإعلانات والأحداث</h2>
                    </div>
                    <div class="breadcrumb__inner">
                        <ul>
                            <li><a href="{{ URL::to('/ar')}}">الرئيسية</a></li>
                            <li>أرشيف الإعلانات والأحداث</li>
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
                            @forelse ($data['ads'] as $ad)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 grid-item column__custom__class">
                                    <div class="gridarea__wraper gridarea__wraper__2" data-aos="fade-up">
                                        <div class="gridarea__img w-100">
                                            <a href="{{ URL::to('/ar/ads/details/' . ($ad->slug ?: str_replace(' ', '-', $ad->title))) }}">
                                                <img loading="lazy" src="{{ $ad->first_image ? URL::to($ad->first_image) : URL::to('images/logos/1840372294400317.png') }}"
                                                    alt="{{ $ad->title }}">
                                            </a>
                                        </div>
                                        <div class="gridarea__content w-100">
                                            <div class="">
                                                <a href="{{ URL::to('/ar/ads/details/' . ($ad->slug ?: str_replace(' ', '-', $ad->title))) }}">
                                                    <h6>{{ $ad->title }}</h6>
                                                </a>
                                                <h6>{{ $ad->ad_date }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted fs-5">لا توجد إعلانات حالياً</p>
                                </div>
                            @endforelse
                        </div>


                        {{ $data['ads']?->onEachSide(1)->links('vendor.pagination.custom') }}

                    </div>
                </div>
                @include('ar/ads_sidebar')
            </div>
        </div>
    </div>
@endsection