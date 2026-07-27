@extends('layout')

@section('title', 'About Sust')

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
                            <h2 class="heading">About Sust</h2>
                        </div>
                        <div class="breadcrumb__inner">
                            <ul>
                                <li><a href="{{ URL::to('/')}}">Home</a></li>
                                <li>About Sust</li>
                            </ul>
                        </div>
                    </div>



                </div>
            </div>
        </div>



    </div>
    <!-- breadcrumbarea__section__end-->

    <div class="blogarea__2 sp_top_100 sp_bottom_100">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="blogarae__img__2 course__details__img__2 aos-init aos-animate" data-aos="fade-up">
                        <img loading="lazy" src="{{ URL::to('images/gallery/overview.jpg') }}" alt="About Sust">
                    </div>
                    <div class="blog__details__content__wraper">
                        <div class="blog__details__content">
                            <p data-aos="fade-up">The establishment of Sudan University goes back deep
                                into the modern history of Sudan, during the early stages of education development in the
                                country. Starting with Khartoum Technical School (KTI), and then the Trade School in 1902,
                                the Radiology School in 1932, the Art School in 1946, Khartoum Technical Institute in 1950,
                                Shambat Agricultural Institute in 1954, the Institute of Music and Theater and finally the
                                High Institute for Physical Education for Teachers.</p>
                            <p data-aos="fade-up">Later on, in 1975, all technical institutes in Sudan
                                were merged with KTI to form what was known as Khartoum Polytechnique (KP). In 1990, KP was
                                upgraded to a university named Sudan University of Science and Technology, with all the
                                institutes getting changed to Collages.</p>
                            <p data-aos="fade-up">Currently, the university includes 25 colleges, that
                                offer programs at postgraduate levels (doctorate, masters and higher diplomas) and other
                                degrees at the bachelor’s and technical diploma levels, as well as training and continuing
                                studies programs. The university graduates are distinguished by their possession of
                                scientific knowledge in addition to possession of the required skills for applying
                                knowledge, which made our graduates desirable and preferred in the labor market. The
                                university has preserved this advantage through its educational traditions and components of
                                its programs and its possession of the latest laboratories, workshops and technical
                                frameworks, its training for faculty members and its permanent interaction with society.</p>
                            <p data-aos="fade-up">The university offers its programs through traditional
                                methods and by affiliation (distanced). It also offers many programs based on e-learning
                                methods. The university's majors range from well-established programs in engineering,
                                commerce, agriculture, veterinary, education, science and languages ​​to specialized ones,
                                as well as in radiology, medical laboratories, water and oil technology, and forests, as
                                well as creative majors such as art, music, drama and sports education, in addition to
                                medical fields and pharmacy.</p>
                            <p data-aos="fade-up">The university culminated in its scientific output by
                                adding modern specializations such as computer and communication sciences, and the
                                university includes many institutes and research centers that take care of the university’s
                                connection with society, such as the Peace Studies and Culture Center, the Institute for
                                Women and Community Development, Laser Institute and Distance Education Center. Furthermore,
                                the university is continuously developing, updating and expanding its programs with steady
                                and firm steps towards achieving its goals and achieving its mission of spreading knowledge,
                                serving society, preparing specialized frameworks and well-qualified assistance.</p>
                        </div>
                        <div class="blog__details__tag" data-aos="fade-up">
                            <ul class="share__list" data-aos="fade-up">
                                <li class="heading__tag">
                                    Share
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&quote=Vice-Chancellor"
                                        target="_blank" class="facebook-share-button">
                                        <i class="icofont-facebook"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ 'https://twitter.com/intent/tweet?' . http_build_query([
        'url' => url()->current(),
        'text' => 'Vice Chancellor',
        'hashtags' => 'Sudan University of Science and Technology'
    ]) }}" target="_blank" rel="noopener noreferrer">
                                        <i class="icofont-twitter"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>







                    </div>
                </div>
                @include('sidebar')
            </div>
        </div>
    </div>
@endsection