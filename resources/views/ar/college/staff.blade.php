@extends('ar/college/layout')

@section('content')
    <!-- breadcrumbarea__section__start-->
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
                            <h2 class="heading">هيئة التدريس</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('college_home_ar', ['name' => $data['name']]) }}">الرئيسية</a></li>
                                <li>هيئة التدريس</li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
    <!-- breadcrumbarea__section__end-->

    <div class="blogarea__2 sp_top_50 sp_bottom_100">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">

                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            @if(count($data['staff']) > 0)
                                <div class="dashboard__content__wraper">
                                    <div class="dashboard__section__title">
                                        <h3 class="heading">هيئة التدريس</h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12">
                                            <div class="dashboard__table table-responsive">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>الاسم</th>
                                                            <th>الدرجة الوظيفية</th>
                                                            <th>الدرجة العلمية</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($data['staff'] as $staff)
                                                            <tr @if($loop->even) class="dashboard__table__row" @endif>
                                                                <th>
                                                                    @if($staff->user)
                                                                        <a href="{{ route('staff_home', ['name_en' => $staff->user->name_en]) }}">
                                                                            {{ str_replace('_', ' ', $staff->user->name) }}
                                                                        </a>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </th>
                                                                <td>{{ $staff->job_title ?? '-' }}</td>
                                                                <td>{{ $staff->rank ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p>لا توجد بيانات متاحة حاليا.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection