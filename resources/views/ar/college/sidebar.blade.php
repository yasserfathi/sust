<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">

        @if(isset($data['recent_ads']) && count($data['recent_ads']) > 0)
            <h4 class="sidebar__title">أحدث الإعلانات</h4>
            <ul class="recent__list mb-4">
                @foreach ($data['recent_ads'] as $ad)
                    <li>
                        <div class="recent__text">
                            <h6>
                                <a href="{{ route(($data['college_type'] ?? 'college') . '_ads_detail_ar', ['name' => $data['name'] ?? Request::segment(3), 'slug' => $ad->slug ?: str_replace(' ', '-', $ad->title)]) }}"
                                   class="{{ (isset($data['ads']) && $data['ads']?->id == $ad->id) ? 'active' : '' }}">
                                    <i class="icofont-square-left"></i>
                                    {{ $ad->title }}
                                </a>
                            </h6>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <h4 class="sidebar__title">أحدث الأخبار</h4>
        @if(isset($data['recent_news']) && count($data['recent_news']) > 0)
            <ul class="recent__list">
                @foreach ($data['recent_news'] as $news)
                    <li>
                        <div class="recent__text">
                            <h6><a href="{{ route(($data['college_type'] ?? 'college') . '_news_detail_ar', ['name' => $data['name'] ?? Request::segment(3), 'slug' => $news->slug ?: str_replace(' ', '-', $news->title)]) }}"><i
                                        class="icofont-square-left"></i>
                                    {{ $news->title }} </a></h6>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted py-2 mb-0 small">لا توجد أخبار حديثة حالياً لهذا القسم.</p>
        @endif

    </div>
</div>