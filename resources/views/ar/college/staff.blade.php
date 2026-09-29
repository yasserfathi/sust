@extends('ar/college/layout')

@section('title', 'هيئة التدريس')


@section('content')
    <!-- breadcrumbarea__section__start -->
    <div class="breadcrumbarea"
        style="background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.15)), url({{ !empty($data['banner']) ? URL::to($data['banner']) : URL::to('/images/gallery/vision.jpg') }}); background-size: cover; background-position: center;">
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
    <!-- breadcrumbarea__section__end -->

    <div class="blogarea__2 sp_top_30 sp_bottom_80">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="section-header text-center mb-4" data-aos="fade-up">
                        <h2 class="section-title">أعضاء <span class="highlight">هيئة التدريس</span></h2>
                    </div>

                    @if(count($data['staff']) > 0)
                        @php
                            $dean = $data['staff']->firstWhere('is_dean', true);
                            $otherStaff = $data['staff']->filter(function($item) {
                                return empty($item->is_dean);
                            });
                            $staffByDept = $otherStaff->groupBy(function($item) {
                                return $item->department->name ?? 'أخرى';
                            });
                        @endphp

                        @if($dean && $dean->user)
                            <div class="d-flex justify-content-center mb-5" data-aos="fade-up">
                                @php
                                    $deanImg = $dean->user->thumb_img ?: ($dean->user->img ?: '/images/default-avatar.png');
                                    $deanName = str_replace('_', ' ', $dean->user->name);
                                @endphp
                                <div class="leader-card vc-card w-100" style="max-width: 320px; margin: 0; padding: 22px 18px 18px;">
                                    @if(!empty($dean->user->slug))
                                        <a href="{{ route('staff_home_ar', ['slug' => $dean->user->slug]) }}">
                                            <img class="leader-img"
                                                src="{{ URL::to($deanImg) }}"
                                                style="width: 100px; height: 100px;"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($deanName) }}&background=ce6148&color=fff&size=140'">
                                        </a>
                                    @else
                                        <img class="leader-img"
                                            src="{{ URL::to($deanImg) }}"
                                            style="width: 100px; height: 100px;"
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($deanName) }}&background=ce6148&color=fff&size=140'">
                                    @endif
                                    <div class="leader-info mt-2">
                                        <div class="leader-name font-weight-bold" style="font-size: 1.05rem;">
                                            @if(!empty($dean->user->slug))
                                                <a href="{{ route('staff_home_ar', ['slug' => $dean->user->slug]) }}" class="text-decoration-none text-dark hover-primary">
                                                    {{ $deanName }}
                                                </a>
                                            @else
                                                {{ $deanName }}
                                            @endif
                                        </div>
                                        <div class="leader-role badge text-bg-primary mt-1" style="background-color: var(--primaryColor) !important; font-size: 0.8rem; font-weight: normal; padding: 4px 10px;">عميد الكلية</div>
                                        <div class="text-muted small mt-1">
                                            {{ $dean->grade ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @foreach($staffByDept as $deptName => $deptStaff)
                            @php
                                $trimmedName = trim($deptName);
                                if (preg_match('/^(قسم|القسم|مدرسة|شعبة)\b/u', $trimmedName)) {
                                    $deptDisplayTitle = $trimmedName;
                                } else {
                                    $deptDisplayTitle = 'قسم ' . $trimmedName;
                                }
                            @endphp
                            <div class="section-header text-center my-4" data-aos="fade-up">
                                <h3 class="section-title" style="font-size: 1.45rem;"><span class="highlight">{{ $deptDisplayTitle }}</span></h3>
                            </div>

                            <div class="row g-3 justify-content-center mb-5">
                                @foreach($deptStaff as $staff)
                                    @if($staff->user)
                                        @php
                                            $staffImg = $staff->user->thumb_img ?: ($staff->user->img ?: '/images/default-avatar.png');
                                            $staffName = str_replace('_', ' ', $staff->user->name);
                                        @endphp
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
                                            <div class="leader-card w-100 mb-0" style="max-width: 100%; padding: 18px 14px 15px;">
                                                @if(!empty($staff->user->slug))
                                                    <a href="{{ route('staff_home_ar', ['slug' => $staff->user->slug]) }}">
                                                        <img class="leader-img" src="{{ URL::to($staffImg) }}"
                                                            style="width: 85px; height: 85px;"
                                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($staffName) }}&background=ce6148&color=fff&size=140'">
                                                    </a>
                                                @else
                                                    <img class="leader-img" src="{{ URL::to($staffImg) }}"
                                                        style="width: 85px; height: 85px;"
                                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($staffName) }}&background=ce6148&color=fff&size=140'">
                                                @endif
                                                <div class="leader-info mt-2 text-center w-100">
                                                    <div class="leader-name" style="font-size: 0.95rem; font-weight: 600; line-height: 1.4;">
                                                        @if(!empty($staff->user->slug))
                                                            <a href="{{ route('staff_home_ar', ['slug' => $staff->user->slug]) }}" class="text-decoration-none text-dark hover-primary">
                                                                {{ $staffName }}
                                                            </a>
                                                        @else
                                                            {{ $staffName }}
                                                        @endif
                                                    </div>
                                                    
                                                    @php
                                                        $adminPos = \App\Models\HeadAdministrativePosition::with('administrativePosition')->where('user_id', $staff->user->id)->whereNull('end_date')->first();
                                                        $isHead = \App\Models\HeadOfDepartment::where('user_id', $staff->user->id)->whereNull('end_date')->first();
                                                    @endphp
                                                    
                                                    @if($adminPos && $adminPos->administrativePosition)
                                                        <div class="leader-role badge text-bg-primary mt-1" style="background-color: var(--primaryColor) !important; font-size: 0.75rem; font-weight: normal; padding: 4px 10px;">{{ $adminPos->administrativePosition->title ?? $adminPos->administrativePosition->title_en }}</div>
                                                    @elseif($isHead)
                                                        <div class="leader-role badge text-bg-primary mt-1" style="background-color: var(--primaryColor) !important; font-size: 0.75rem; font-weight: normal; padding: 4px 10px;">رئيس القسم</div>
                                                    @endif
                                                    
                                                    <div class="leader-role" style="font-size: 0.85rem; color: var(--primaryColor); font-weight: 500; margin-top: 4px;">{{ !empty($staff->grade) ? $staff->grade : 'عضو هيئة تدريس' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <div class="alert alert-info text-center my-4" style="border-radius: 10px;">لا توجد بيانات متاحة حالياً.</div>
                    @endif
                </div>

                @include('ar/college/sidebar')
            </div>
        </div>
    </div>
@endsection