@extends('college/layout')

@section('title', 'Department Details')


@section('keywords')
    <meta name="keywords" content="{{ $data['description']?->keywords ?? '' }}">
@endsection

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
                            <h2 class="heading">About Department
                                {{ isset($data['description']->name_en) ? '- ' . $data['description']->name_en : (isset($data['description']->name) ? '- ' . $data['description']->name : '') }}
                            </h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ URL::to('/') }}">Home</a></li>
                                <li>About Department</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- breadcrumbarea__section__end-->

    <div class="blogarea__2 sp_top_50 sp_bottom_80">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="blog__details__content__wraper">
                        <!-- Department Navigation Tabs -->
                        <div class="dept-nav-wrapper mb-4"
                            style="background: #f8f9fa; border: 1px solid #eaeaea; border-radius: 50px; padding: 5px; display: inline-flex; gap: 5px;"
                            data-aos="fade-up">
                            <a href="{{ route(($data['college_type'] ?? 'college') . '_about_department', ['name' => $data['name'], 'dept_name' => Request::segment(4)]) }}"
                                class="dept-nav-link dept-nav-link-active me-2"
                                style="background: #ce6148; color: #ffffff !important; border-radius: 50px; padding: 8px 20px; font-weight: 700; font-size: 13.5px; text-decoration: none; box-shadow: 0 4px 12px rgba(185, 74, 57, 0.35);">
                                <i class="icofont-info-circle me-1"></i> About Department
                            </a>
                            <a href="{{ route(($data['college_type'] ?? 'college') . '_department_programs', ['name' => $data['name'], 'dept_name' => Request::segment(4)]) }}"
                                class="dept-nav-link"
                                style="color: #555555; border-radius: 50px; padding: 8px 20px; font-weight: 700; font-size: 13.5px; text-decoration: none;">
                                <i class="icofont-read-book me-1"></i> Academic Programs
                            </a>
                        </div>

                        @if(isset($data['head_of_department']) && $data['head_of_department'] && $data['head_of_department']->user)
                            @php
                                $headUser = $data['head_of_department']->user;
                                $headImg = $headUser->thumb_img ?: ($headUser->img ?: 'images/gallery/vision.jpg');
                                $headTitle = $headUser->staff_latest?->grade_en ?? ($headUser->staff_latest?->grade ?? 'Head of Department');
                                $headName = $headUser->name_en ?: $headUser->name;
                            @endphp
                            <div class="dept-head-card p-3 mb-4 rounded-4 bg-white shadow-sm border d-flex align-items-center gap-3" data-aos="fade-up" style="border-left: 4px solid #ce6148 !important;">
                                <div class="dept-head-img-wrapper" style="width: 75px; height: 75px; min-width: 75px; border-radius: 50%; overflow: hidden; border: 2px solid #ce6148; box-shadow: 0 4px 10px rgba(206, 97, 72, 0.2);">
                                    <img loading="lazy" src="{{ URL::to($headImg) }}" alt="{{ $headName }}" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div class="dept-head-info flex-grow-1">
                                    <span class="badge px-2 py-1 mb-1 text-white" style="background-color: #ce6148; font-size: 11.5px; border-radius: 20px;">
                                        <i class="icofont-user-suited me-1"></i> {{ $headTitle }}
                                    </span>
                                    <h5 class="mb-1 text-dark fw-bold" style="font-size: 16px;">
                                        @if(!empty($headUser->slug))
                                            <a href="{{ URL::to('/staff/' . $headUser->slug) }}" class="text-decoration-none text-dark hover-primary" style="transition: color 0.2s ease;">
                                                {{ $headName }}
                                            </a>
                                        @else
                                            {{ $headName }}
                                        @endif
                                    </h5>
                                    @if(!empty($headUser->email))
                                        <div class="text-muted" style="font-size: 12.5px;">
                                            <i class="icofont-envelope me-1 text-secondary"></i> {{ $headUser->email }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if(isset($data['description']) && $data['description'])
                        <div class="blog__details__content">
                            {!! \App\Helpers\SanitizeHelper::cleanAndFormat($data['description']->description) !!}
                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag" style="font-size: 13.5px;">
                                    Share
                                </li>
                                <li>
                                    <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => 'Vice-Chancellor']) }}"
                                        target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'About College',
        'hashtags' => 'SUST,SudanUniversity'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @else
                        <div class="alert alert-warning text-center mt-5 mb-5" style="font-size: 1.5rem; border-radius: 10px;">
                            We apologize, the content is not available at this time.
                        </div>
                        @endif
                    </div>
                </div>
                @include('college.sidebar')
            </div>
        </div>
    </div>
@endsection