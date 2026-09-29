@extends('college/layout')

@section('title', 'Academic Programs')


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
                            <h2 class="heading">Academic Programs
                                {{ isset($data['department']) ? '- ' . ($data['department']->name_en ?: $data['department']->name) : '' }}
                            </h2>
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

    <div class="blogarea__2 sp_top_50 sp_bottom_80">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="blog__details__content__wraper">
                        @if(isset($data['department']) && Request::segment(4))
                            <!-- Department Navigation Tabs -->
                            <div class="dept-nav-wrapper mb-4"
                                style="background: #f8f9fa; border: 1px solid #eaeaea; border-radius: 50px; padding: 5px; display: inline-flex; gap: 5px;"
                                data-aos="fade-up">
                                <a href="{{ route(($data['college_type'] ?? 'college') . '_about_department', ['name' => $data['name'], 'dept_name' => Request::segment(4)]) }}"
                                    class="dept-nav-link me-2"
                                    style="color: #555555; border-radius: 50px; padding: 8px 20px; font-weight: 700; font-size: 13.5px; text-decoration: none;">
                                    <i class="icofont-info-circle me-1"></i> About Department
                                </a>
                                <a href="{{ route(($data['college_type'] ?? 'college') . '_department_programs', ['name' => $data['name'], 'dept_name' => Request::segment(4)]) }}"
                                    class="dept-nav-link dept-nav-link-active"
                                    style="background: #ce6148; color: #ffffff !important; border-radius: 50px; padding: 8px 20px; font-weight: 700; font-size: 13.5px; text-decoration: none; box-shadow: 0 4px 12px rgba(185, 74, 57, 0.35);">
                                    <i class="icofont-read-book me-1"></i> Academic Programs
                                </a>
                            </div>
                        @endif

                        <div class="blog__details__content">

                            @if(isset($data['programs']) && count($data['programs']) > 0)
                                <div class="program-table-card"
                                    style="background: #ffffff; border-radius: 12px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06); border: 1px solid #eaedf1; overflow: hidden;"
                                    data-aos="fade-up">
                                    <table class="table program-table align-middle mb-0">
                                        <thead>
                                            <tr style="background: #ce6148;">
                                                <th scope="col" class="text-center"
                                                    style="background: #ce6148 !important; color: #ffffff !important; font-weight: 700; font-size: 13.5px; padding: 14px 12px; border: none; width: 45px;">
                                                    #</th>
                                                <th scope="col"
                                                    style="background: #ce6148 !important; color: #ffffff !important; font-weight: 700; font-size: 13.5px; padding: 14px 12px; border: none;">
                                                    Academic Program Name</th>
                                                <th scope="col" class="text-center"
                                                    style="background: #ce6148 !important; color: #ffffff !important; font-weight: 700; font-size: 13.5px; padding: 14px 12px; border: none;">
                                                    Degree</th>
                                                <th scope="col" class="text-center"
                                                    style="background: #ce6148 !important; color: #ffffff !important; font-weight: 700; font-size: 13.5px; padding: 14px 12px; border: none;">
                                                    Years</th>
                                                <th scope="col" class="text-center"
                                                    style="background: #ce6148 !important; color: #ffffff !important; font-weight: 700; font-size: 13.5px; padding: 14px 12px; border: none;">
                                                    Semesters</th>
                                                <th scope="col" class="text-center"
                                                    style="background: #ce6148 !important; color: #ffffff !important; font-weight: 700; font-size: 13.5px; padding: 14px 12px; border: none;">
                                                    Study Plan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data['programs'] as $index => $program)
                                                <tr style="border-bottom: 1px solid #f0f2f5;">
                                                    <td class="text-center fw-bold text-muted py-3" style="font-size: 13.5px;">
                                                        {{ $index + 1 }}</td>
                                                    <td class="py-3">
                                                        <span class="fw-bold text-dark d-block"
                                                            style="font-size: 14px; color: #222222;">{{ $program->program_name_en ?: $program->program_name }}</span>
                                                    </td>
                                                    <td class="text-center py-3">
                                                        <span class="badge"
                                                            style="background: #ce6148 !important; color: #ffffff !important; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 12.5px;">
                                                            @if($program->program_type == 1) Bachelor
                                                            @elseif($program->program_type == 2) Master
                                                            @elseif($program->program_type == 3) PhD
                                                            @elseif($program->program_type == 4) Diploma
                                                            @else Program
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td class="text-center py-3">
                                                        <span
                                                            style="background: #f1f4f8; color: #334155; padding: 4px 12px; border-radius: 15px; font-weight: 600; font-size: 12.5px; display: inline-block;">{{ $program->NOOFYEARSNO ? $program->NOOFYEARSNO . ' Years' : '-' }}</span>
                                                    </td>
                                                    <td class="text-center py-3">
                                                        <span
                                                            style="background: #f1f4f8; color: #334155; padding: 4px 12px; border-radius: 15px; font-weight: 600; font-size: 12.5px; display: inline-block;">{{ $program->NOOFSEM ? $program->NOOFSEM . ' Semesters' : '-' }}</span>
                                                    </td>
                                                    <td class="text-center py-3">
                                                        @if(!empty($program->file))
                                                            <a href="{{ URL::to('storage/' . $program->file) }}" target="_blank"
                                                                style="background: #ce6148 !important; color: #ffffff !important; border-radius: 20px; padding: 5px 15px; font-size: 12.5px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;">
                                                                <i class="icofont-file-pdf"></i> Download PDF
                                                            </a>
                                                        @else
                                                            <span class="text-muted" style="font-size: 13px;">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info text-center p-4 rounded-3 shadow-sm" data-aos="fade-up"
                                    style="font-size: 14px;">
                                    <i class="icofont-info-circle fs-4 d-block mb-2"></i>
                                    No academic programs available at the moment for this department.
                                </div>
                            @endif

                        </div>
                        <div class="blog__details__tag mt-4" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag" style="font-size: 13.5px;">
                                    Share
                                </li>
                                <li>
                                    <a href="{{ 'https://www.facebook.com/sharer/sharer.php?' . http_build_query(['u' => url()->current(), 'quote' => 'Academic Programs']) }}"
                                        target="_blank" rel="noopener noreferrer" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'Academic Programs',
        'hashtags' => 'SUST,SudanUniversity'
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