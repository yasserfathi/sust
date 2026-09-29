<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">
        <h4 class="sidebar__title">أحدث {{ $data['type'] == 'conference' ? 'المؤتمرات' : ($data['type'] == 'seminar' ? 'السمنارات' : 'ورش العمل') }}</h4>
        @if(isset($data['recent_events']) && count($data['recent_events']) > 0)
            <ul class="recent__list">
                @foreach ($data['recent_events'] as $event)
                    <li>
                        <div class="recent__text">
                            <h6>
                                <a href="{{ route('events_ar_detail', ['type' => $data['type'] . 's', 'slug' => $event->slug]) }}" class="{{ (!is_iterable($data['event'] ?? null) && isset($data['event']->slug) && $data['event']->slug == $event->slug) ? 'active' : '' }}">
                                    <i class="icofont-simple-left me-1"></i> {{ $event->title }}
                                </a>
                            </h6>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted py-2 mb-0" style="font-size: 13.5px;">لا توجد {{ $data['type'] == 'conference' ? 'مؤتمرات' : ($data['type'] == 'seminar' ? 'سمنارات' : 'ورش عمل') }} حديثة حالياً.</p>
        @endif
    </div>
</div>