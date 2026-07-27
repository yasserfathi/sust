@extends('ar/layout')

@section('content')

    <!-- error__section__start -->
        <div class="errorarea sp_top_100 sp_bottom_100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-10 col-sm-12 col-12 m-auto">
                        <div class="errorarea__inner" data-aos="fade-up">
                            <h1 class="error-page-message">
                                404
                            </h1>
                            <div class="error__text">
                                <h3>الصفحة التي تبحث عنها غير موجودة</h3>
                            </div>
                            <div class="error__button">
                                <a class="default__button" href="{{ route('home_ar') }}">العودة إلى الصفحة الرئيسية
                                <i class="icofont-simple-right"></i>
                            </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- error__section__end -->
        
@endsection