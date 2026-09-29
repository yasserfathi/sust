@if ($paginator->hasPages())
@php
    $isAr = (request()->is('ar') || request()->is('ar/*') || request('lang') === 'ar');
    $prevIcon = $isAr ? 'icofont-rounded-right' : 'icofont-rounded-left';
    $nextIcon = $isAr ? 'icofont-rounded-left' : 'icofont-rounded-right';
    $prevText = $isAr ? 'السابق' : __('Previous');
    $nextText = $isAr ? 'التالي' : __('Next');
@endphp

<div class="modern-pagination-container mt-5 mb-5" data-aos="fade-up">
    <ul class="modern-pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-nav-link">
                    <i class="{{ $prevIcon }}"></i> {{ $prevText }}
                </span>
            </li>
        @else
            <li class="page-item">
                <a class="page-nav-link" href="{{ $paginator->previousPageUrl() }}">
                    <i class="{{ $prevIcon }}"></i> {{ $prevText }}
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link dots">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-nav-link" href="{{ $paginator->nextPageUrl() }}">
                    {{ $nextText }} <i class="{{ $nextIcon }}"></i>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-nav-link">
                    {{ $nextText }} <i class="{{ $nextIcon }}"></i>
                </span>
            </li>
        @endif
    </ul>

    <div class="pagination-results">
        @if($isAr)
            عرض {{ $paginator->count() }} من {{ number_format($paginator->total()) }} نتيجة
        @else
            Showing {{ $paginator->count() }} of {{ number_format($paginator->total()) }} results
        @endif
    </div>
</div>
@endif