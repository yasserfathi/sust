@extends('ar/layout')

@section('content')

    <!-- error__section__start -->
    <div class="errorarea py-5 my-5 text-center">
        <div class="container">
            <div class="row">
                <div class="col-xl-7 col-lg-9 col-md-11 col-12 m-auto">
                    <div class="errorarea__inner border-0 rounded-3 shadow-sm p-4 p-md-5 bg-white" data-aos="fade-up">
                        <div class="mb-2 position-relative d-inline-block">
                            <h1 class="error-page-message mb-0" style="letter-spacing: -2px;">404</h1>
                        </div>
                        <div class="error__text mb-4 mt-2">
                            <h3 class="fw-bold text-dark mb-2">الصفحة التي تبحث عنها غير موجودة</h3>
                            <p class="text-muted fs-6">نعتذر، يبدو أن الرابط الذي حاولت الوصول إليه غير صحيح أو تم نقله.</p>
                        </div>
                        <div class="error__button mt-4">
                            <a class="default__button rounded-pill px-5 py-3 font-weight-bold d-inline-flex align-center gap-2" href="{{ route('home_ar') }}">
                                <i class="icofont-arrow-right"></i>
                                العودة إلى الصفحة الرئيسية
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- error__section__end -->

@endsection