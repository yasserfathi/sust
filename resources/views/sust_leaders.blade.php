@extends('layout')
@section('title', 'SUST Leaders')
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
                            <h2 class="heading">SUST Leaders</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ URL::to('/')}}">Home</a></li>
                                <li>SUST Leaders</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="blogarea__2 sp_top_50 sp_bottom_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="section-header text-center mb-5" data-aos="fade-up">
                        <h2 class="section-title">Our Qualified <span class="highlight">Leaders</span></h2>
                    </div>

                    <div class="leadership-tier">
                        <div class="leader-card" data-aos="fade-up">
                            <img loading="lazy" src="{{ versioned_asset('images/staff/1784996576000000.jpg') }}" alt="Prof. Dr. Shamboul Adlan" class="leader-img">
                            <div class="leader-info">
                                <a href="#" target="_blank" class="leader-name">Prof. Dr. Shamboul Adlan</a>
                                <a href="#" target="_blank" class="leader-role">Chairman of the University Council</a>
                            </div>
                        </div>
                    </div>

                    <div class="leadership-tree">

                        @if(isset($data['vice_chancellor']))
                            <div class="leadership-tier" data-aos="fade-up">
                                <div class="leader-card vc-card">
                                    @if(!empty($data['vice_chancellor']?->user?->slug))
                                        <a href="{{ URL::to('/staff/' . $data['vice_chancellor']->user->slug) }}">
                                            <img class="leader-img"
                                                src="{{ URL::to($data['vice_chancellor']?->user->img ?? '/images/default-avatar.png') }}"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($data['vice_chancellor']?->user->name_en ?? ($data['vice_chancellor']?->user->name ?? 'VC')) }}&background=ce6148&color=fff&size=140'">
                                        </a>
                                    @else
                                        <img class="leader-img"
                                            src="{{ URL::to($data['vice_chancellor']?->user->img ?? '/images/default-avatar.png') }}"
                                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($data['vice_chancellor']?->user->name_en ?? ($data['vice_chancellor']?->user->name ?? 'VC')) }}&background=ce6148&color=fff&size=140'">
                                    @endif
                                    <div class="leader-info">
                                        <div class="leader-name">
                                            @if(!empty($data['vice_chancellor']?->user?->slug))
                                                <a href="{{ URL::to('/staff/' . $data['vice_chancellor']->user->slug) }}" class="text-decoration-none text-dark hover-primary" style="transition: color 0.2s ease;">
                                                    {{ $data['vice_chancellor']?->user_title_en ?? '' }}{{ $data['vice_chancellor']?->user->name_en ?: $data['vice_chancellor']?->user->name }}
                                                </a>
                                            @else
                                                {{ $data['vice_chancellor']?->user_title_en ?? '' }}{{ $data['vice_chancellor']?->user->name_en ?: $data['vice_chancellor']?->user->name }}
                                            @endif
                                        </div>
                                        <div class="leader-role">Vice Chancellor</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if(isset($data['university_ranks']) && $data['university_ranks']?->count() > 0)
                            <div class="leadership-tier leadership-tier-ranks" data-aos="fade-up">
                                @foreach($data['university_ranks'] as $rank_person)
                                    <div class="leader-card ranks-card">
                                        @if(!empty($rank_person->user?->slug))
                                            <a href="{{ URL::to('/staff/' . $rank_person->user->slug) }}">
                                                <img class="leader-img"
                                                    src="{{ URL::to($rank_person->user->img ?? '/images/default-avatar.png') }}"
                                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($rank_person->user->name_en ?? ($rank_person->user->name ?? 'Leader')) }}&background=ce6148&color=fff&size=140'">
                                            </a>
                                        @else
                                            <img class="leader-img"
                                                src="{{ URL::to($rank_person->user->img ?? '/images/default-avatar.png') }}"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($rank_person->user->name_en ?? ($rank_person->user->name ?? 'Leader')) }}&background=ce6148&color=fff&size=140'">
                                        @endif
                                        <div class="leader-info">
                                            <div class="leader-name">
                                                @if(!empty($rank_person->user?->slug))
                                                    <a href="{{ URL::to('/staff/' . $rank_person->user->slug) }}" class="text-decoration-none text-dark hover-primary" style="transition: color 0.2s ease;">
                                                        {{ $rank_person->user_title_en ?? '' }}{{ $rank_person->user->name_en ?: $rank_person->user->name }}
                                                    </a>
                                                @else
                                                    {{ $rank_person->user_title_en ?? '' }}{{ $rank_person->user->name_en ?: $rank_person->user->name }}
                                                @endif
                                            </div>
                                            <div class="leader-role">{{ $rank_person->administrativePosition->title_en ?? $rank_person->administrativePosition->title ?? '' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @php
                            $typeMapping = [
                                'college' => 'Deans of <span class="highlight">Colleges</span>',
                                'deanship' => 'Deans of <span class="highlight">Deanships</span>',
                                'center' => 'Deans of <span class="highlight">Centers</span>',
                                'institute' => 'Deans of <span class="highlight">Institutes</span>',
                                'secretariat' => 'Deans of <span class="highlight">Secretariats</span>',
                            ];

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
                            <div class="leadership-tier" data-aos="fade-up" data-aos-delay="100">
                                @foreach($topLeaders as $leader)
                                    <div class="leader-card">
                                        @if(!empty($leader->user?->slug))
                                            <a href="{{ URL::to('/staff/' . $leader->user->slug) }}">
                                                <img class="leader-img" src="{{ URL::to($leader->user->img ?? '/images/default-avatar.png') }}"
                                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($leader->user->name_en ?? ($leader->user->name ?? 'Leader')) }}&background=ce6148&color=fff&size=140'">
                                            </a>
                                        @else
                                            <img class="leader-img" src="{{ URL::to($leader->user->img ?? '/images/default-avatar.png') }}"
                                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($leader->user->name_en ?? ($leader->user->name ?? 'Leader')) }}&background=ce6148&color=fff&size=140'">
                                        @endif
                                        <div class="leader-info">
                                            <div class="leader-name">
                                                @if(!empty($leader->user?->slug))
                                                    <a href="{{ URL::to('/staff/' . $leader->user->slug) }}" class="text-decoration-none text-dark hover-primary" style="transition: color 0.2s ease;">
                                                        {{ $leader->user_title_en ?? '' }}{{ $leader->user->name_en ?: $leader->user->name }}
                                                    </a>
                                                @else
                                                    {{ $leader->user_title_en ?? '' }}{{ $leader->user->name_en ?: $leader->user->name }}
                                                @endif
                                            </div>
                                            <div class="leader-role">{{ $leader->college->name_en ?? $leader->college->name ?? '' }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @foreach(['college', 'deanship', 'institute', 'center', 'secretariat'] as $type)
                            @if(isset($groupedRemaining[$type]) && count($groupedRemaining[$type]) > 0)
                                </div>
                                <div class="section-header text-center my-5" data-aos="fade-up">
                                    <h2 class="section-title">{!! $typeMapping[$type] !!}</h2>
                                </div>
                                <div class="leadership-tree">
                                <div class="leadership-tier">
                                    @foreach($groupedRemaining[$type] as $leader)
                                        <div class="leader-card" data-aos="fade-up" data-aos-delay="{{ ($loop->iteration % 3) * 100 }}">
                                            @if(!empty($leader->user?->slug))
                                                <a href="{{ URL::to('/staff/' . $leader->user->slug) }}">
                                                    <img class="leader-img" src="{{ URL::to($leader->user->img ?? '/images/default-avatar.png') }}"
                                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($leader->user->name_en ?? ($leader->user->name ?? 'Leader')) }}&background=ce6148&color=fff&size=140'">
                                                </a>
                                            @else
                                                <img class="leader-img" src="{{ URL::to($leader->user->img ?? '/images/default-avatar.png') }}"
                                                    onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($leader->user->name_en ?? ($leader->user->name ?? 'Leader')) }}&background=ce6148&color=fff&size=140'">
                                            @endif
                                            <div class="leader-info">
                                                <div class="leader-name">
                                                    @if(!empty($leader->user?->slug))
                                                        <a href="{{ URL::to('/staff/' . $leader->user->slug) }}" class="text-decoration-none text-dark hover-primary" style="transition: color 0.2s ease;">
                                                            {{ $leader->user->name_en ?: $leader->user->name }}
                                                        </a>
                                                    @else
                                                        {{ $leader->user->name_en ?: $leader->user->name }}
                                                    @endif
                                                </div>
                                                <div class="leader-role">Dean</div>
                                                <div class="leader-role" style="font-size: 0.85rem; color: #777; margin-top: 5px;">
                                                    {{ $leader->college->name_en ?? $leader->college->name ?? '' }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @include('administration_sidebar')
            </div>
        </div>
    </div>
@endsection