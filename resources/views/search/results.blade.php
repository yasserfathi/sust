@extends(request('lang') == 'en' ? 'layout' : 'ar/layout')

@section('content')
@php
    $isEn = request('lang') == 'en';
@endphp
<style>

    .result-card {
        background: #fff;
        border: 1px solid #eaedf1;
        border-radius: 12px;
        padding: 25px;
        margin-bottom: 25px;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
    }
    .result-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.06);
        border-color: var(--primaryColor, #a84f3a);
    }
    .result-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: {{ $isEn ? '0' : 'auto' }};
        right: {{ $isEn ? 'auto' : '0' }};
        width: 4px;
        height: 100%;
        background: var(--primaryColor, #a84f3a);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .result-card:hover::before {
        opacity: 1;
    }
    .result-type-badge {
        font-size: 0.85rem;
        background: #e9ecef;
        color: #495057;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 12px;
        font-weight: 600;
    }
    .result-type-badge i {
        margin-right: {{ $isEn ? '5px' : '0' }};
        margin-left: {{ $isEn ? '0' : '5px' }};
        color: var(--primaryColor, #a84f3a);
    }
    .result-link {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--primaryColor, #a84f3a);
        text-decoration: none;
        display: block;
        margin-bottom: 10px;
        line-height: 1.4;
    }
    .result-link:hover {
        color: var(--primaryColor, #a84f3a);
        text-decoration: underline;
        text-decoration-thickness: 2px;
        text-underline-offset: 4px;
        opacity: 0.85;
    }
    .result-desc {
        color: #495057;
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 15px;
    }
    .result-url {
        font-size: 0.95rem;
        color: #0d6efd;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: #f8f9fa;
        border-radius: 20px;
        border: 2px dashed #dee2e6;
    }
    .empty-state i {
        font-size: 5rem;
        color: #ced4da;
        margin-bottom: 25px;
        display: inline-block;
    }
    .empty-state h3 {
        font-weight: 700;
        color: #495057;
    }
    .calendar-alert {
        background: linear-gradient(135deg, #e0f7fa 0%, #b2ebf2 100%);
        border: none;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,188,212,0.15);
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 40px;
    }
    .calendar-alert i.main-icon {
        font-size: 3.5rem;
        color: #00838f;
    }
    .calendar-alert .btn-download {
        background: #00acc1;
        border: none;
        color: #fff;
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(0,172,193,0.3);
        transition: all 0.3s ease;
        display: inline-block;
        margin-top: 15px;
    }
    .calendar-alert .btn-download:hover {
        background: #00838f;
        transform: translateY(-2px);
        color: #fff;
        text-decoration: none;
    }
    
    .search-bar-wrapper {
        display: flex;
        align-items: center;
        background: #fff;
        border: 2px solid #eaeaea;
        border-radius: 50px;
        transition: all 0.3s ease;
        padding: 6px 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    }
    .search-bar-wrapper:focus-within {
        border-color: var(--primaryColor, #a84f3a);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .search-bar-input {
        border: none !important;
        box-shadow: none !important;
        font-size: 1.15rem;
        background: transparent;
        padding: 10px 20px;
        flex-grow: 1;
        outline: none;
    }
    .search-clear-btn {
        color: #adb5bd;
        font-size: 1.4rem;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: color 0.2s;
    }
    .search-clear-btn:hover {
        color: #dc3545;
    }
    .search-submit-btn {
        background: var(--primaryColor, #a84f3a);
        color: #fff;
        border: none;
        width: 48px;
        height: 48px;
        flex-shrink: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-left: 5px;
    }
    .search-submit-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
        color: #fff;
    }
    
    [dir="rtl"] .search-submit-btn {
        margin-left: 0;
        margin-right: 5px;
    }
    
    .stats-text {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 25px;
    }
</style>

<div class="breadcrumbarea" style="background: linear-gradient(rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0.15)),url({{ URL::to('/images/gallery/vision.jpg') }}); background-size: cover; background-position: center;" dir="{{ $isEn ? 'ltr' : 'rtl' }}">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb__content__wraper" data-aos="fade-up" style="text-align: {{ $isEn ? 'left' : 'right' }}">
                    <div class="breadcrumb__title">
                        <h2 class="heading">{{ $isEn ? 'Search Results' : 'نتائج البحث' }}</h2>
                    </div>
                    <div class="breadcrumb__inner">
                        <ul>
                            <li><a href="{{ url($isEn ? '/' : '/ar') }}">{{ $isEn ? 'Home' : 'الرئيسية' }}</a></li>
                            <li>{{ $isEn ? 'Search for:' : 'البحث عن:' }} "{{ $keyword }}"</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container sp_top_100 sp_bottom_100" dir="{{ $isEn ? 'ltr' : 'rtl' }}" style="text-align: {{ $isEn ? 'left' : 'right' }}">
    <div class="row">
        <div class="col-lg-9 mx-auto">

            {{-- شريط البحث داخل الصفحة --}}
            <form action="{{ route('search.index') }}" method="GET" class="mb-5 position-relative" data-aos="fade-up">
                <input type="hidden" name="lang" value="{{ request('lang', 'ar') }}">
                <div class="search-bar-wrapper">
                    <input type="text" name="q" value="{{ $keyword }}" class="search-bar-input form-control" placeholder="{{ $isEn ? 'Search for colleges, programs, news...' : 'ابحث عن الكليات، البرامج، الأخبار...' }}" autocomplete="off">
                    
                    @if($keyword)
                        <a href="javascript:void(0);" onclick="document.querySelector('.search-bar-input').value = ''; document.querySelector('.search-bar-input').focus();" class="search-clear-btn" title="{{ $isEn ? 'Clear' : 'مسح' }}">
                            <i class="icofont-close-line"></i>
                        </a>
                    @endif
                    
                    <button type="submit" class="search-submit-btn">
                        <i class="icofont-search-1"></i>
                    </button>
                </div>
            </form>
            
            @if($paginatedResults->isEmpty() && !$latestCalendar)
                <div class="empty-state">
                    <i class="icofont-search-document"></i>
                    <h3>{{ $isEn ? 'No Results Found' : 'لم يتم العثور على نتائج' }}</h3>
                    <p class="text-muted">{{ $isEn ? 'We couldn\'t find anything matching your search. Please try different keywords.' : 'عذراً، لم نتمكن من العثور على أية نتائج تطابق بحثك. جرب استخدام كلمات أخرى.' }}</p>
                </div>
            @else
                
                {{-- تنبيه التقويم الذكي --}}
                @if($latestCalendar)
                    <div class="calendar-alert">
                        <i class="icofont-calendar main-icon"></i>
                        <div>
                            <h4 style="color: #006064; font-weight: 700; margin-bottom: 8px;">
                                {{ $isEn ? 'Looking for important dates (Exams, Registration)?' : 'هل تبحث عن مواعيد هامة (امتحانات، تسجيل)؟' }}
                            </h4>
                            <p style="color: #00838f; margin-bottom: 0; font-size: 1.1rem;">
                                {{ $isEn ? 'Please refer to the Academic Calendar for year ' : 'يرجى مراجعة التقويم الأكاديمي للجامعة للعام ' }} <strong>{{ $latestCalendar->year }}</strong>.
                            </p>
                            @if($latestCalendar->file)
                                <a href="{{ asset('storage/' . $latestCalendar->file) }}" class="btn-download" target="_blank">
                                    <i class="icofont-download"></i> {{ $isEn ? 'Download Calendar' : 'تحميل التقويم' }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if($paginatedResults->isNotEmpty())
                    <p class="stats-text">
                        {{ $isEn ? 'About' : 'حوالي' }} <strong>{{ $paginatedResults->total() }}</strong> {{ $isEn ? 'results found' : 'نتيجة' }}
                    </p>

                    @foreach($paginatedResults as $result)
                        <div class="result-card">
                            <div class="result-type-badge">
                                <i class="{{ $result->icon }}"></i> 
                                {{ $isEn ? $result->type_en : $result->type_ar }}
                            </div>
                            
                            <a href="{{ $result->url_ar !== '#' ? ($isEn ? $result->url_en : $result->url_ar) : 'javascript:void(0)' }}" class="result-link" @if($result->url_ar === '#') style="cursor: pointer;" @endif>
                                {{ $isEn ? $result->title_en : $result->title_ar }}
                            </a>

                            <p class="result-desc">
                                {{ $isEn ? $result->desc_en : $result->desc_ar }}
                            </p>

                            @if($result->url_ar !== '#')
                                <div class="result-url">
                                    <i class="icofont-web"></i> {{ $isEn ? $result->url_en : $result->url_ar }}
                                </div>
                            @endif
                        </div>
                    @endforeach

                    {{-- شريط التصفح (Pagination) --}}
                    <div class="d-flex justify-content-center mt-5">
                        {{ $paginatedResults->appends(request()->query())->onEachSide(1)->links('vendor.pagination.custom') }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
@endsection
