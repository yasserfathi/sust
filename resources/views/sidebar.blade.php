<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">
        <h4 class="sidebar__title">Recent News</h4>
        @if(isset($data['recent_news']) && count($data['recent_news']) > 0)
            <ul class="recent__list">
                @foreach ($data['recent_news'] as $news)
                    <li>
                        <div class="recent__text">
                            <h6>
                                <a href="{{ URL::to('news/details/' . $news->slug)}}" class="{{ (!is_iterable($data['news'] ?? null) && isset($data['news']->slug) && $data['news']->slug == $news->slug) ? 'active' : '' }}">
                                    <i class="icofont-simple-right me-1"></i> {{ $news->title }}
                                </a>
                            </h6>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted py-2 mb-0" style="font-size: 13.5px;">No recent news available at the moment.</p>
        @endif
    </div>
</div>