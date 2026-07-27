<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
    <div class="blogsidebar__content__wraper__2" data-aos="fade-up">

        <h4 class="sidebar__title">أحدث الأخبار</h4>
        <ul class="recent__list">
            @foreach ($data['recent_news'] as $news)
                <li>
                    <div class="recent__text">
                        <h6><a href="{{ URL::to('ar/news/details/' . $news->slug)}}"><i class="icofont-square-left"></i>
                                {{ $news->title }} </a></h6>
                    </div>
                </li>
            @endforeach
        </ul>

    </div>
</div>