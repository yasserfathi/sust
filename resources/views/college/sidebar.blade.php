<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="sidebar-widget-card p-4 rounded-4 bg-white shadow-sm border mb-4" data-aos="fade-up">
        @if(isset($data['recent_ads']) && count($data['recent_ads']) > 0)
            <h5 class="sidebar-widget-title pb-2 mb-3 border-bottom d-flex align-items-center">
                <i class="icofont-notification me-2"></i> Recent Announcements
            </h5>
            <div class="sidebar-news-list mb-4">
                @foreach ($data['recent_ads'] as $ad)
                    <div class="sidebar-news-item py-2 border-bottom">
                        <a href="{{ route(($data['college_type'] ?? 'college') . '_ads_detail', ['name' => $data['name'] ?? Request::segment(2), 'slug' => $ad->slug ?: str_replace(' ', '-', $ad->title)]) }}"
                            class="sidebar-news-link d-flex align-items-start gap-2 text-decoration-none {{ (isset($data['ads']) && $data['ads']?->id == $ad->id) ? 'active' : '' }}">
                            <i class="icofont-simple-right mt-1 fs-6 text-danger"></i>
                            <span class="sidebar-news-title text-dark">
                                {{ $ad->title }}
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <h5 class="sidebar-widget-title pb-2 mb-3 border-bottom d-flex align-items-center">
            <i class="icofont-newspaper me-2"></i> Recent News
        </h5>

        @if(isset($data['recent_news']) && count($data['recent_news']) > 0)
            <div class="sidebar-news-list">
                @foreach ($data['recent_news'] as $news)
                    <div class="sidebar-news-item py-2 border-bottom">
                        <a href="{{ route(($data['college_type'] ?? 'college') . '_news_detail', ['name' => $data['name'] ?? Request::segment(2), 'slug' => $news->slug ?: str_replace(' ', '-', $news->title)]) }}"
                            class="sidebar-news-link d-flex align-items-start gap-2 text-decoration-none">
                            <i class="icofont-simple-right mt-1 fs-6 text-danger"></i>
                            <span class="sidebar-news-title text-dark">
                                {{ $news->title }}
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted mb-0 py-2 sidebar-empty-text">No recent news available at the moment.</p>
        @endif
    </div>
</div>