@extends('layout')

@section('content')
    <div class="breadcrumbarea" 
    @if (isset($data['banner']) && $data['banner'])
        style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.5)),url({{ URL::to($data['banner']) }}); background-size: cover; background-position: center;"
    @else
            style="background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.5)),url({{ URL::to('/images/gallery/vision.jpg') }}); background-size: cover; background-position: center;"
    @endif >

    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb__content__wraper" data-aos="fade-up">
                    <div class="breadcrumb__title">
                        <h2 class="heading">University Leadership</h2>
                    </div>
                    <div class="breadcrumb__inner">
                        <ul>
                            <li><a href="{{ URL::to('/')}}">Home</a></li>
                            <li>University Leadershi</li>
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
                    <div class="tab-content tab__content__wrapper" id="LeadershipContent">

                        @if (isset($data['vice_chancellor']))
                            <div class="row" id="projects__two" role="tabpanel" aria-labelledby="projects__two">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 grid-item column__custom__class mx-auto">
                                    <div class="gridarea__wraper gridarea__wraper__2" data-aos="fade-up">
                                        <div class="gridarea__img w-100">
                                            <img loading="lazy" src="{{ URL::to($data['vice_chancellor']->user->img) }}"
                                                alt="Vice Chancellor">
                                        </div>
                                        <div class="gridarea__content w-100">
                                            <div class="gridarea__heading">
                                                <h6>{{ $data['vice_chancellor']->user->name }}</h6>
                                                <h6>{{ $data['vice_chancellor']->college->name }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <p>No Vice-Chancellor data available.</p>

                        @endif

                        <div class="row" id="projects__two" role="tabpanel" aria-labelledby="projects__two">
                            @php $index = 0 @endphp
                            @foreach ($data['leadership'] as $key_person)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 grid-item column__custom__class">
                                    <div class="gridarea__wraper gridarea__wraper__2" data-aos="fade-up">
                                        <div class="gridarea__img w-100">
                                            <img loading="lazy" src="{{ URL::to($key_person->user->img) }}" alt="grid">
                                        </div>
                                        <div class="gridarea__content w-100">
                                            <div class="gridarea__heading">
                                                <h6>{{ $key_person->user->name }}</h6>
                                                <h6>{{ $key_person->college->name }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
                @include('sidebar')
            </div>
        </div>
    </div>
@endsection