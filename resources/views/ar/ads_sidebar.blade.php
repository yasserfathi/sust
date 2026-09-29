<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">
        <h4 class="sidebar__title">أحدث الإعلانات</h4>
        @php
            $currentAd = (!is_iterable($data['ads'] ?? null) && isset($data['ads']->id)) ? $data['ads'] : null;
        @endphp
        @if(isset($data['recent_ads']) && count($data['recent_ads']) > 0)
            <ul class="recent__list">
                @foreach ($data['recent_ads'] as $ads)
                    <li>
                        <div class="recent__text">
                            <h6>
                                <a href="{{ URL::to('/ar/ads/details/' . ($ads->slug ?: str_replace(' ', '-', $ads->title))) }}" class="{{ ($currentAd && ($currentAd->slug == $ads->slug || $currentAd->title == $ads->title)) ? 'active' : '' }}">
                                    <i class="icofont-simple-left me-1"></i> {{ $ads->title }}
                                </a>
                            </h6>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted py-2 mb-0" style="font-size: 13.5px;">لا توجد إعلانات حديثة حالياً.</p>
        @endif
    </div>
</div>