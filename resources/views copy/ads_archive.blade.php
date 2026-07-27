@extends('layout')

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
                        <h2 class="heading">Ads Archive</h2>
                    </div>
                    <div class="breadcrumb__inner">
                        <ul>
                            <li><a href="{{ URL::to('/')}}">Home</a></li>
                            <li>Ads Archive</li>
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
                            @foreach ($data['ads'] as $ad)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 grid-item column__custom__class">
                                    <div class="gridarea__wraper gridarea__wraper__2" data-aos="fade-up">
                                        <div class="gridarea__img w-100">
                                            <a href="{{ URL::to('/ads/details/' . str_replace(' ', '-', $ad->title))}}">
                                                <img loading="lazy" src="{{ URL::to(path: $ad->first_image) }}"
                                                    alt="{{ $ad->title }}">
                                            </a>
                                        </div>
                                        <div class="gridarea__content w-100">
                                            <div class="">
                                                <a href="{{ URL::to('/ads/details/' . str_replace(' ', '-', $ad->title))}}">
                                                    <h6>{{ $ad->title }}</h6>
                                                </a>
                                                <h6>{{ $ad->ad_date }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        {{ $data['ads']->links('vendor.pagination.custom') }}

                    </div>
                </div>
                @include('ads_sidebar')
            </div>
        </div>
    </div>
@endsection