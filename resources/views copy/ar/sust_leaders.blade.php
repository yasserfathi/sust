@extends('ar/layout')
@section('title', 'قيادات الجامعة')
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
                            <h2 class="heading">قيادات الجامعة</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ route('home_ar') }}">الرئيسية</a></li>
                                <li>قيادات الجامعة</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .leadership-header {
            text-align: center;
            font-weight: 800;
            color: #2c3e50;
            margin: 50px 0;
            font-family: 'Tajawal', 'Cairo', sans-serif;
            font-size: 2.5rem;
            direction: rtl;
        }

        .leadership-header span {
            color: #ce6148;
        }

        .section-title-wrapper {
            text-align: center;
            margin: 60px 0 40px 0;
        }

        .section-title {
            background: linear-gradient(135deg, #4b134f 0%, #ce6148 100%);
            color: #ffffff;
            border-radius: 30px;
            padding: 12px 50px;
            box-shadow: 0 8px 20px rgba(206, 97, 72, 0.15);
            font-weight: 700;
            font-size: 1.4rem;
            display: inline-block;
            font-family: 'Tajawal', 'Cairo', sans-serif;
        }

        .leader-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 30px;
            justify-items: center;
            direction: rtl;
        }

        .leader-grid-top {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin-bottom: 50px;
            direction: rtl;
        }

        .leader-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px 20px 20px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(206, 97, 72, 0.05);
            border: 1px solid rgba(206, 97, 72, 0.05);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
            width: 100%;
            max-width: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-family: 'Tajawal', 'Cairo', sans-serif;
        }

        .leader-card.vc-card {
            max-width: 300px;
            margin: 0 auto 50px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(255, 245, 240, 1) 100%);
            box-shadow: 0 15px 35px rgba(206, 97, 72, 0.15);
            border: 1px solid rgba(206, 97, 72, 0.2);
        }

        .leader-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(206, 97, 72, 0.12);
            border-color: rgba(206, 97, 72, 0.3);
        }

        .leader-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 8px 20px rgba(206, 97, 72, 0.12);
            margin-bottom: 20px;
            transition: transform 0.4s ease;
        }

        .leader-card:hover .leader-img {
            transform: scale(1.08);
        }

        .leader-name {
            color: #ce6148;
            font-weight: 800;
            font-size: 1.15rem;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .vc-card .leader-name {
            color: #ce6148;
            font-size: 1.3rem;
        }

        .leader-role {
            font-size: 0.95rem;
            color: #555;
            font-weight: 600;
            line-height: 1.4;
        }
    </style>

    <div class="blogarea__2 sp_top_50 sp_bottom_100">
        <div class="container">
            <h1 class="leadership-header" data-aos="fade-up">قيادات <span>الجامعة</span></h1>

            @if(isset($data['vice_chancellor']))
                <div data-aos="fade-up">
                    <div class="leader-card vc-card">
                        <img class="leader-img"
                            src="{{ URL::to($data['vice_chancellor']->user->img ?? '/images/default-avatar.png') }}"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($data['vice_chancellor']->user->name ?? 'مدير') }}&background=ce6148&color=fff&size=140'">
                        <div class="leader-name">{{ $data['vice_chancellor']->user->name ?? '' }}</div>
                        <div class="leader-role">مدير الجامعة</div>
                    </div>
                </div>
            @endif

            @php
                $typeMapping = [
                    'deanship' => 'عمادات الجامعة',
                    'college' => 'كليات الجامعة',
                    'center' => 'معاهد الجامعة',
                    'secretariat' => 'إدارات الجامعة',
                ];

                // Unmapped types might be top-level roles (e.g. Deputy VC)
                $topLeaders = [];
                $groupedRemaining = [];

                foreach ($data['leaders_grouped'] as $type => $leaders) {
                    if (!array_key_exists($type, $typeMapping)) {
                        foreach ($leaders as $leader) {
                            $topLeaders[] = $leader;
                        }
                    } else {
                        $groupedRemaining[$type] = $leaders;
                    }
                }
            @endphp

            @if(count($topLeaders) > 0)
                <div class="leader-grid-top" data-aos="fade-up" data-aos-delay="100">
                    @foreach($topLeaders as $leader)
                        <div class="leader-card">
                            <img class="leader-img" src="{{ URL::to($leader->user->img ?? '/images/default-avatar.png') }}"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($leader->user->name ?? 'قيادي') }}&background=ce6148&color=fff&size=140'">
                            <div class="leader-name">{{ $leader->user->name ?? '' }}</div>
                            <div class="leader-role">{{ $leader->college->name ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @foreach(['deanship', 'college', 'center', 'secretariat'] as $type)
                @if(isset($groupedRemaining[$type]) && count($groupedRemaining[$type]) > 0)
                    <div class="section-title-wrapper" data-aos="fade-up">
                        <div class="section-title">{{ $typeMapping[$type] }}</div>
                    </div>
                    <div class="leader-grid">
                        @foreach($groupedRemaining[$type] as $leader)
                            <div class="leader-card" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 % 500 }}">
                                <img class="leader-img" src="{{ URL::to($leader->user->img ?? '/images/default-avatar.png') }}"
                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($leader->user->name ?? 'عميد') }}&background=ce6148&color=fff&size=140'">
                                <div class="leader-name">{{ $leader->user->name ?? '' }}</div>
                                <div class="leader-role">عميد</div>
                                <div class="leader-role" style="font-size: 0.85rem; color: #777; margin-top: 5px;">
                                    {{ $leader->college->name ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach

        </div>
    </div>
@endsection